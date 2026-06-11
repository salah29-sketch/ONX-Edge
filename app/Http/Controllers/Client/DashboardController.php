<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingFile;
use App\Models\Booking\BookingPhoto;
use App\Models\Client\ClientMediaSeen;
use App\Models\Client\ClientMessage;
use App\Models\Client\ClientMessagesSeen;
use App\Models\Client\ClientSelectedPhoto;
use App\Models\Content\Testimonial;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionRenewal;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DashboardController extends Controller
{
    private ?array $cachedOrderMap = null;

    public function __construct()
    {
        $this->middleware('client.auth');
    }

    private function client(): \App\Models\Client\Client
    {
        /** @var \App\Models\Client\Client $client */
        $client = Auth::guard('client')->user();
        return $client;
    }

    // ─── Dashboard ───────────────────────────────────────────────

    public function dashboard()
    {
        $client = $this->client();

        // ✅ إضافة service.category لجلب اسم وأيقونة التصنيف ديناميكياً
        $bookings = $client->bookings()
            ->with(['photos', 'service.category'])
            ->latest()->take(5)->get();

        $unreadMessages = $this->clientUnreadMessagesCount($client);

        // ✅ إضافة service.category للحجز النشط أيضاً
        $activeBooking = $client->bookings()
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->with(['photos', 'visibleFiles', 'payments', 'service.category'])
            ->latest()->first();

        if (!$activeBooking) {
            $activeBooking = $client->bookings()
                ->with(['photos', 'visibleFiles', 'payments', 'service.category'])
                ->latest()->first();
        }

        $lastMessage = $client->messages()->latest()->first();

        $hasNewFilesOrVideo = $activeBooking && (
            (filled($activeBooking->final_video_path) || $activeBooking->visibleFiles->isNotEmpty())
            && !$client->mediaSeen()->where('booking_id', $activeBooking->id)->exists()
        );

        $clientOrderMap = $this->clientOrderMap($client);

        $subscriptions = $client->subscriptions()
            ->with('package')
            ->orderByRaw("CASE status WHEN 'active' THEN 0 WHEN 'expired' THEN 1 ELSE 2 END")
            ->orderByDesc('next_billing_date')
            ->take(5)
            ->get();

        return view('client.dashboard', compact(
            'client', 'bookings', 'unreadMessages', 'activeBooking',
            'lastMessage', 'hasNewFilesOrVideo', 'clientOrderMap', 'subscriptions'
        ));
    }

    private function clientUnreadMessagesCount(\App\Models\Client\Client $client): int
    {
        $query = $client->messages()->whereNotNull('admin_replied_at');
        $lastSeen = ClientMessagesSeen::where('client_id', $client->id)->value('last_seen_at');
        if ($lastSeen) {
            $query->where('admin_replied_at', '>', $lastSeen);
        }
        return $query->count();
    }

    private function clientOrderMap(\App\Models\Client\Client $client): array
    {
        if ($this->cachedOrderMap !== null) {
            return $this->cachedOrderMap;
        }

        $ids = $client->bookings()->orderBy('created_at')->orderBy('id')->pluck('id');
        $map = [];
        foreach ($ids as $i => $id) {
            $map[$id] = $i + 1;
        }

        return $this->cachedOrderMap = $map;
    }

    // ─── Profile ─────────────────────────────────────────────────

    public function profile()
    {
        $client = $this->client();
        return view('client.profile', compact('client'));
    }

    public function updateProfile(Request $request)
    {
        $client = $this->client();
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'required|string|max:50',
            'is_company'    => 'nullable|boolean',
            'business_name' => 'nullable|string|max:255',
        ]);
        $data = $request->only('name', 'email', 'phone');
        $data['is_company']    = $request->boolean('is_company');
        $data['business_name'] = $data['is_company'] ? $request->input('business_name') : null;
        $client->update($data);
        return back()->with('success', 'تم تحديث البيانات.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:6|confirmed',
        ]);
        $client = $this->client();
        if (!Hash::check($request->current_password, $client->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.']);
        }
        $client->password = $request->password;
        $client->save();
        return back()->with('success', 'تم تغيير كلمة المرور.');
    }

    // ─── Bookings ────────────────────────────────────────────────

    public function bookings()
    {
        $client         = $this->client();
        $bookings       = $client->bookings()->latest()->paginate(10);
        $clientOrderMap = $this->clientOrderMap($client);
        return view('client.bookings.index', compact('bookings', 'clientOrderMap'));
    }

    public function bookingDetail(Booking $booking)
    {
        $client = $this->client();
        if ((int)$booking->client_id !== (int)$client->id) abort(404);
        $booking->load(['photos', 'payments', 'visibleFiles', 'subscription']);
        $meta = app(\App\Services\BookingService::class)->getBookingMeta($booking);
        $booking->load(['package']);
        $idx = $client->bookings()->orderBy('created_at')->orderBy('id')->pluck('id')->search($booking->id);
        $clientOrderNumber = $idx !== false ? $idx + 1 : 1;

        return view('client.bookings.detail', compact('booking', 'meta', 'clientOrderNumber'));
    }

    // ─── Subscriptions ───────────────────────────────────────────

    public function subscriptions()
    {
        $client        = $this->client();
        $subscriptions = $client->subscriptions()->with('package')->latest('start_date')->paginate(10);
        return view('client.subscriptions.index', compact('subscriptions'));
    }

    public function renewSubscription(Subscription $subscription)
    {
        $client = $this->client();
        if ($subscription->client_id !== $client->id) abort(403);
        if (!$subscription->isRenewable()) {
            return back()->withErrors(['subscription' => 'لا يمكن تجديد هذا الاشتراك.']);
        }

        $oldNext = $subscription->next_billing_date;
        $newNext = Carbon::parse($oldNext)->addMonth();

        $subscription->update(['next_billing_date' => $newNext]);

        SubscriptionRenewal::create([
            'subscription_id'   => $subscription->id,
            'renewed_at'        => now(),
            'next_billing_date' => $newNext,
            'renewal_type'      => 'manual',
            'amount'            => $subscription->plan_price,
        ]);

        return back()->with('success', 'تم تجديد الاشتراك حتى ' . $newNext->format('d/m/Y'));
    }

    public function updateSubscriptionRenewalType(Request $request, Subscription $subscription)
    {
        $client = $this->client();
        if ($subscription->client_id !== $client->id) abort(403);
        $request->validate(['renewal_type' => 'required|in:manual,automatic']);
        $subscription->update(['renewal_type' => $request->renewal_type]);
        return back()->with('success', 'تم تحديث نوع التجديد.');
    }

    // ─── Payments ────────────────────────────────────────────────

    public function payments()
    {
        $client         = $this->client();
        $bookings       = $client->bookings()->with(['payments', 'photos'])->latest()->get();
        $clientOrderMap = $this->clientOrderMap($client);
        return view('client.payments', compact('bookings', 'clientOrderMap'));
    }

    // ─── Media ───────────────────────────────────────────────────

    public function media(Request $request)
    {
        $client = $this->client();
        $filter = $request->get('filter', 'all');

        $bookingsWithPhotos = $client->bookings()
            ->whereHas('photos')
            ->with(['photos' => fn ($q) => $q->orderBy('sort_order')])
            ->latest()->get();

        $videoFiles = BookingFile::whereHas('booking', fn ($q) => $q->where('client_id', $client->id))
            ->where('is_visible', true)->where('type', 'video')
            ->with('booking.service')->orderByDesc('created_at')->get();

        $otherFiles = BookingFile::whereHas('booking', fn ($q) => $q->where('client_id', $client->id))
            ->where('is_visible', true)->whereIn('type', ['pdf', 'zip', 'other'])
            ->with('booking.service')->orderByDesc('created_at')->get();

        $galleryItems = [];
        foreach ($bookingsWithPhotos as $booking) {
            foreach ($booking->photos as $photo) {
                $galleryItems[] = [
                    'url'        => asset($photo->path),
                    'download'   => asset($photo->path),
                    'booking_id' => $booking->id,
                    'photo_id'   => $photo->id,
                ];
            }
        }
        $selectedPhotoIds = $client->selectedPhotos()->pluck('booking_photo_id')->toArray();

        $bookingsWithFiles = $client->bookings()
            ->whereHas('visibleFiles')
            ->with(['visibleFiles' => fn ($q) => $q->orderByDesc('created_at')])
            ->latest()->get();

        $bookingIdsWithMedia = $client->bookings()
            ->where(function ($q) {
                $q->whereNotNull('final_video_path')->where('final_video_path', '!=', '')
                    ->orWhereHas('visibleFiles');
            })->pluck('id');

        foreach ($bookingIdsWithMedia as $bookingId) {
            ClientMediaSeen::updateOrCreate(
                ['client_id' => $client->id, 'booking_id' => $bookingId],
                ['seen_at' => now()]
            );
        }

        $clientOrderMap = $this->clientOrderMap($client);

        return view('client.media.index', compact(
            'bookingsWithPhotos', 'videoFiles', 'otherFiles', 'bookingsWithFiles',
            'clientOrderMap', 'filter', 'galleryItems', 'selectedPhotoIds'
        ));
    }

    public function files()
    {
        return redirect()->route('client.media', ['filter' => 'videos']);
    }

    // ─── Invoice / PDF ────────────────────────────────────────────

    public function invoicePdf(Booking $booking)
{
    $client = $this->client();
    if ($booking->client_id !== $client->id) abort(404);
    $booking->load(['payments', 'package', 'eventBooking.venue']);
    $meta = app(\App\Services\BookingService::class)->getBookingMeta($booking);
        $booking->load(['package']);
    $companySettings = \App\Models\Content\Company::first();
    $pdf = Pdf::loadView('client.bookings.invoice-pdf', compact('booking', 'meta', 'client', 'companySettings'));
    return $pdf->download('invoice-' . $booking->id . '.pdf');
}

    public function bookingSummary(Booking $booking)
    {
        $client = $this->client();
        if ($booking->client_id !== $client->id) abort(404);
        $booking->load(['photos', 'payments', 'visibleFiles', 'subscription']);
        $meta = app(\App\Services\BookingService::class)->getBookingMeta($booking);
        $booking->load(['package']);
        return view('client.bookings.summary', compact('booking', 'meta', 'client'));
    }

    public function bookingPdf(Booking $booking)
    {
        $client = $this->client();
        if ($booking->client_id !== $client->id) abort(404);
        $meta = app(\App\Services\BookingService::class)->getBookingMeta($booking);
        $booking->load(['package']);
        $clientLogin    = $client->email ?: $client->phone;
        $clientPassword = '— (لديك حساب في منطقة العملاء)';
        $pdf = Pdf::loadView('front.booking.pdf', [
            'booking'        => $booking,
            'packageName'    => $meta['packageName'],
            'packagePrice'   => $meta['packagePrice'],
            'locationName'   => $meta['locationName'],
            'clientLogin'    => $clientLogin,
            'clientPassword' => $clientPassword,
        ]);
        return $pdf->download('booking-' . $booking->id . '.pdf');
    }

    // ─── File Download ────────────────────────────────────────────

    public function downloadFile(BookingFile $file)
    {
        $client  = $this->client();
        $booking = $file->booking;
        if ($booking->client_id !== $client->id) abort(403);
        if (!$file->is_visible) abort(403);

        if (str_starts_with($file->path, 'storage/')) {
            $rel = str_replace('storage/', '', $file->path);
            if (!Storage::disk('public')->exists($rel)) {
                return back()->withErrors(['file' => 'الملف غير موجود.']);
            }
            return Storage::disk('public')->download($rel, $file->label);
        }

        $fullPath = public_path($file->path);
        if (!file_exists($fullPath)) {
            return back()->withErrors(['file' => 'الملف غير موجود.']);
        }
        return response()->download($fullPath, $file->label);
    }

    // ─── ZIP Download ─────────────────────────────────────────────

    public function downloadSelectedPhotosZip(Booking $booking)
    {
        $client = $this->client();
        if ($booking->client_id !== $client->id) abort(403);

        $selectedIds = $client->selectedPhotos()
            ->whereIn('booking_photo_id', $booking->photos->pluck('id'))
            ->pluck('booking_photo_id');

        $photos = $booking->photos->whereIn('id', $selectedIds);

        if ($photos->isEmpty()) {
            return back()->withErrors(['zip' => 'لم تختر أي صور بعد.']);
        }

        $zipName = 'photos-booking-' . $booking->id . '-' . time() . '.zip';
        $zipPath = storage_path('app/tmp/' . $zipName);

        if (!is_dir(storage_path('app/tmp'))) {
            mkdir(storage_path('app/tmp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->withErrors(['zip' => 'فشل إنشاء ملف ZIP.']);
        }

        $addedCount = 0;
        foreach ($photos as $photo) {
            $filePath = str_starts_with($photo->path, 'storage/')
                ? public_path($photo->path)
                : $photo->path;
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath));
                $addedCount++;
            }
        }
        $zip->close();

        if ($addedCount === 0) {
            @unlink($zipPath);
            return back()->withErrors(['zip' => 'لم يتم العثور على ملفات الصور على الخادم.']);
        }

        return response()->download($zipPath, 'صوري-المميزة.zip')->deleteFileAfterSend(true);
    }

    // ─── Messages ─────────────────────────────────────────────────

    public function messages()
    {
        $client   = $this->client();
        $messages = $client->messages()->latest()->paginate(15);
        ClientMessagesSeen::updateOrCreate(
            ['client_id' => $client->id],
            ['last_seen_at' => now()]
        );
        return view('client.messages', compact('messages'));
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);
        $client = $this->client();
        $client->messages()->create([
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        return back()->with('success', 'تم إرسال رسالتك.');
    }

    // ─── Reviews ──────────────────────────────────────────────────

    public function createReview()
    {
        $client    = $this->client();
        $canReview = $client->bookings()->where('status', 'completed')->exists();
        if (!$canReview) {
            return redirect()->route('client.dashboard')
                ->with('info', 'يمكنك إضافة رأيك بعد اكتمال حجز واحد على الأقل.');
        }
        return view('client.review-create');
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            'rating'  => 'required|integer|min:1|max:5',
        ]);
        $client = $this->client();
        Testimonial::create([
            'client_id'   => $client->id,
            'client_name' => $client->name,
            'content'     => $request->content,
            'rating'      => $request->rating,
            'initial'     => mb_substr($client->name, 0, 1),
            'status'      => Testimonial::STATUS_PENDING,
            'is_active'   => false,
        ]);
        return redirect()->route('client.dashboard')
            ->with('success', 'تم إرسال رأيك. سيظهر في الموقع بعد المصادقة.');
    }

    // ─── Project Photos ───────────────────────────────────────────

    public function projectPhotos()
    {
        $client        = $this->client();
        $bookings      = $client->bookings()->whereHas('photos')->latest()->get();
        $selectedCount = $client->selectedPhotos()->count();
        return view('client.media.project-photos', compact('bookings', 'selectedCount'));
    }

    public function projectPhotosBooking(Booking $booking)
    {
        $client = $this->client();
        if ($booking->client_id !== $client->id) abort(404);
        $photos        = $booking->photos()->orderBy('sort_order')->get();
        $selectedIds   = $client->selectedPhotos()->pluck('booking_photo_id')->toArray();
        $selectedCount = $client->selectedPhotos()->count();
        return view('client.media.project-photos-booking',
            compact('booking', 'photos', 'selectedIds', 'selectedCount'));
    }

    public function toggleSelectedPhoto(Request $request)
    {
        $request->validate(['booking_photo_id' => 'required|exists:booking_photos,id']);
        $client = $this->client();
        $photo  = BookingPhoto::findOrFail($request->booking_photo_id);

        if ($photo->booking->client_id !== $client->id) {
            return response()->json(['ok' => false], 403);
        }

        $existing = ClientSelectedPhoto::where('client_id', $client->id)
            ->where('booking_photo_id', $photo->id)->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'ok' => true, 'selected' => false,
                'count' => $client->selectedPhotos()->count(),
            ]);
        }

        if ($client->selectedPhotos()->count() >= 200) {
            return response()->json([
                'ok'      => false,
                'message' => 'الحد الأقصى 200 صورة مميزة.',
            ], 422);
        }

        ClientSelectedPhoto::create(['client_id' => $client->id, 'booking_photo_id' => $photo->id]);

        return response()->json([
            'ok' => true, 'selected' => true,
            'count' => $client->selectedPhotos()->count(),
        ]);
    }
}