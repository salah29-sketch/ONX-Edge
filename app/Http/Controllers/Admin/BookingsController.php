<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Services\BookingService;
use App\Models\Booking\Booking;
use App\Models\Service\Package;
use App\Models\Service\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingsController extends Controller
{
    public function __construct(protected BookingService $bookingService)
    {
    }

    public function index(Request $request)
    {
        abort_unless(Gate::allows('booking_access'), 403);

        $query = Booking::with(['client', 'service', 'package'])
            ->latest();

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->paginate(20);
        $bookings->appends($request->query());
        $services = Service::with('packages')->orderBy('sort_order')->get();

        return view('admin.bookings.index', compact('bookings', 'services'));
    }

    public function show(Booking $booking)
    {
        abort_unless(Gate::allows('booking_show'), 403);

        $booking->load(['client', 'service', 'package.serviceItems', 'photos', 'payments', 'files', 'items']);
        $services = Service::orderBy('sort_order')->get();
        $packagesByService = $booking->service_id
            ? Package::where('service_id', $booking->service_id)->orderBy('name')->pluck('name', 'id')
            : collect();
        $clientSelectedPhotos = collect();
        if ($booking->client) {
            $selectedIds = $booking->client->selectedPhotos()->whereIn('booking_photo_id', $booking->photos->pluck('id'))->pluck('booking_photo_id');
            $clientSelectedPhotos = $booking->photos->whereIn('id', $selectedIds);
        }
        $photosPaginated = $booking->photos()->orderBy('id')->paginate(24)->withQueryString();

        // العناصر المتاحة كإضافات (لها addon_price وليست في الباقة)
        $packageItemIds = $booking->package?->serviceItems->pluck('id')->toArray() ?? [];
        $addonItems = $booking->service_id
            ? \App\Models\Service\ServiceItem::where('service_id', $booking->service_id)
                ->whereNotNull('addon_price')
                ->where('is_active', true)
                ->whereNotIn('id', $packageItemIds)
                ->orderBy('sort_order')
                ->get()
            : collect();
        return view('admin.bookings.show', compact('booking', 'services', 'packagesByService', 'clientSelectedPhotos', 'photosPaginated', 'addonItems'));
    }

    public function calendar()
    {
        abort_unless(Gate::allows('booking_access'), 403);

        $bookings = Booking::with(['service', 'package'])
            ->whereIn('status', [...BookingStatus::activeValues(), BookingStatus::COMPLETED->value])
            ->whereNotNull('event_date')
            ->get();

        return view('admin.bookings.calendar', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        abort_unless(Gate::allows('booking_edit'), 403);

        $data = $request->validate([
            'status' => 'required|in:' . BookingStatus::validationValues(),
        ]);

        $booking->update(['status' => $data['status']]);

        return redirect()
            ->route('admin.bookings.show', $booking->id)
            ->with('message', 'تم تحديث حالة الحجز بنجاح.');
    }

    public function updateDetails(Request $request, Booking $booking)
    {
        abort_unless(Gate::allows('booking_edit'), 403);

        $rules = [
            'notes' => 'nullable|string',
            'status' => 'nullable|in:' . BookingStatus::validationValues(),
            'total_price' => 'nullable|numeric|min:0',
            'final_price' => 'nullable|numeric|min:0',
            'package_id'     => 'nullable|integer|exists:packages,id',
            'addon_item_ids' => 'nullable|array',
            'addon_item_ids.*' => 'integer|exists:service_items,id',
        ];

        $serviceSlug = $booking->service?->slug;
        $hasEventBooking = $booking->eventBooking !== null;
        if ($hasEventBooking) {
            $rules += [
                'event_date'   => 'nullable|date',
                'start_time'   => 'nullable|date_format:H:i,H:i:s',
                'end_time'     => 'nullable|date_format:H:i,H:i:s',
                'venue_custom' => 'nullable|string|max:255',
            ];
        }

        if ($serviceSlug === 'marketing') {
            $rules += [
                'business_name' => 'nullable|string|max:255',
                'budget'        => 'nullable|numeric|min:0',
                'deadline'      => 'nullable|date',
            ];
        }

        $data = $request->validate($rules);

        if ($hasEventBooking && isset($data['event_date'])) {
            if ($this->bookingService->isDateTakenForUpdate($data['event_date'], $booking->id)) {
                return back()->withErrors([
                    'event_date' => 'هذا التاريخ محجوز بالفعل لحجز آخر.',
                ])->withInput();
            }
        }

        $eventFields = ['start_time', 'end_time', 'venue_custom'];
        $bookingData = array_diff_key($data, array_flip($eventFields));
        $booking->update($bookingData);

        // حفظ الإضافات المختارة
        if (!empty($data['addon_item_ids'])) {
            $items = \App\Models\Service\ServiceItem::whereIn('id', $data['addon_item_ids'])->get();
            foreach ($items as $item) {
                $booking->items()->updateOrCreate(
                    ['item_type' => 'addon', 'item_id' => $item->id],
                    [
                        'item_name'   => $item->name,
                        'quantity'    => 1,
                        'unit_price'  => $item->addon_price,
                        'total_price' => $item->addon_price,
                    ]
                );
            }
        }

        if ($hasEventBooking) {
            $eventData = array_filter(
                array_intersect_key($data, array_flip($eventFields)),
                fn($v) => $v !== null
            );
            if (!empty($eventData)) {
                $booking->eventBooking()->updateOrCreate(
                    ['booking_id' => $booking->id],
                    $eventData
                );
            }
        }

        return redirect()
            ->route('admin.bookings.show', $booking->id)
            ->with('message', 'تم تحديث تفاصيل الحجز بنجاح.');
    }

    public function updateFinalVideo(Request $request, Booking $booking)
    {
        abort_unless(Gate::allows('booking_edit'), 403);

        $data = $request->validate([
            'final_video_path' => 'nullable|string|max:500',
        ]);
        $booking->update($data);
        return redirect()
            ->route('admin.bookings.show', $booking->id)
            ->with('message', 'تم تحديث رابط الفيديو النهائي.');
    }

    public function destroy(Booking $booking)
    {
        abort_unless(Gate::allows('booking_delete'), 403);

        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('message', 'تم حذف الحجز بنجاح.');
    }

    public function pdf(Booking $booking)
{
    abort_unless(Gate::allows('booking_show'), 403);

    $meta = $this->bookingService->getBookingMeta($booking);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.bookings.pdf', [
        'booking'      => $booking,
        'packageName'  => $meta['packageName'],
        'packagePrice' => $meta['packagePrice'],
        'locationName' => $meta['locationName'],
    ]);

    return $pdf->download('booking-' . $booking->id . '.pdf');
}
}
