<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Services\BookingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BookingController extends Controller
{
  protected $bookingService;

public function __construct(BookingService $bookingService)
{
    $this->bookingService = $bookingService;
}

    /**
     * Verify booking token for stateless access
     */
    private function verifyBookingToken(Booking $booking, ?string $token): bool
    {
        if (!$token) {
            return false;
        }
        $expected = hash('sha256', $booking->id . '|' . $booking->created_at);
        return hash_equals($expected, $token);
    }

    /**
     * صفحة تأكيد الحجز
     */
    public function confirmation(Request $request, Booking $booking)
    {
        if (!session("booking_confirmed_{$booking->id}") && !$this->verifyBookingToken($booking, $request->query('token'))) {
            abort(403);
        }

        $meta  = $this->bookingService->getBookingMeta($booking);
        $bid   = $booking->id;
        $creds = session('booking_creds_' . $bid);

        $clientLogin = $creds['login'] ?? ($booking->client ? ($booking->client->email ?: $booking->client->phone) : null);
        $clientPassword = $creds['password'] ?? null;

        $packagePrice = $meta['packagePrice'] ?? null;
$totalPrice   = $booking->total_price ? (float) $booking->total_price : null;

$extraPrice = null;
if ($totalPrice && $packagePrice && $totalPrice > (float) $packagePrice) {
    $extraPrice = $totalPrice - (float) $packagePrice;
}

// ── جديد: الخصم الترويجي ──
$discountAmount = $booking->discount_amount ? (float) $booking->discount_amount : null;
$promoCode = $booking->promoCode ? $booking->promoCode->code : null;
return view('front.booking.confirmation', [
    'booking'         => $booking,
    'packageName'     => $meta['packageName'],
    'packagePrice'    => $packagePrice,
    'totalPrice'      => $totalPrice,
    'extraPrice'      => $extraPrice,
    'locationName'    => $meta['locationName'],
    'clientLogin'     => $clientLogin,
    'clientPassword'  => $clientPassword,
    'discountAmount'  => $discountAmount,  // ← جديد
    'promoCode'       => $promoCode,       // ← جديد
]);
    }

    /**
     * تحميل PDF الحجز
     */
    public function pdf(Request $request, Booking $booking)
    {
        if (!session("booking_confirmed_{$booking->id}") && !$this->verifyBookingToken($booking, $request->query('token'))) {
            abort(403);
        }

        $meta  = $this->bookingService->getBookingMeta($booking);
        $bid   = $booking->id;
        $creds = session('booking_creds_' . $bid);

        $client      = $booking->client;
        $clientLogin = $creds['login']
            ?? ($client ? ($client->email ?: $client->phone) : null);
        $clientPassword = $creds['password'] ?? null;

        Pdf::setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'defaultFont' => 'dejavu sans']);
        $pdf = Pdf::loadView('front.booking.pdf', [
            'booking'         => $booking,
            'packageName'     => $meta['packageName'],
            'packagePrice'    => $meta['packagePrice'],
            'locationName'    => $meta['locationName'],
            'clientLogin'     => $clientLogin,
            'clientPassword'  => $clientPassword,
        ]);

        session()->forget('booking_creds_' . $bid);

        return $pdf->download('booking-' . $booking->id . '.pdf');
    }
}
