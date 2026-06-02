<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientBookingController extends Controller
{
    // ── قائمة حجوزات العميل ──────────────────────────────────────
    public function index(Request $request)
    {
        $client = $request->user();

        $bookings = $client->bookings()
            ->with(['service', 'package'])
            ->latest()
            ->get()
            ->map(fn ($b) => [
                'id'          => $b->id,
                'reference'   => $b->reference ?? 'ONX-' . str_pad($b->id, 5, '0', STR_PAD_LEFT),
                'status'      => $b->status,
                'status_label'=> $this->statusLabel($b->status),
                'service'     => $b->service?->name ?? '—',
                'package'     => $b->package?->name ?? null,
                'date'        => $b->event_date?->format('Y-m-d') ?? null,
                'total'       => (float) $b->total_price,
                'paid'        => (float) ($b->payments()->sum('amount') ?? 0),
                'created_at'  => $b->created_at->format('Y-m-d'),
            ]);

        return response()->json([
            'success'  => true,
            'bookings' => $bookings,
        ]);
    }

    // ── تفاصيل حجز واحد ──────────────────────────────────────────
    public function show(Request $request, $id)
    {
        $client  = $request->user();
        $booking = $client->bookings()->with(['service', 'package', 'payments', 'files'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'booking' => [
                'id'           => $booking->id,
                'reference'    => $booking->reference ?? 'ONX-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT),
                'status'       => $booking->status,
                'status_label' => $this->statusLabel($booking->status),
                'service'      => $booking->service?->name ?? '—',
                'package'      => $booking->package?->name ?? null,
                'date'         => $booking->event_date?->format('Y-m-d') ?? null,
                'total'        => (float) $booking->total_price,
                'paid'         => (float) ($booking->payments->sum('amount')),
                'remaining'    => (float) ($booking->total_price - $booking->payments->sum('amount')),
                'payments'     => $booking->payments->map(fn($p) => [
                    'amount' => (float) $p->amount,
                    'date'   => $p->paid_at?->format('Y-m-d') ?? $p->created_at->format('Y-m-d'),
                    'method' => $p->method ?? null,
                ]),
                'files'        => $booking->files
                    ->where('is_visible', true)
                    ->map(fn($f) => [
                        'name' => $f->name,
                        'url'  => asset('storage/' . $f->path),
                    ]),
                'created_at'   => $booking->created_at->format('Y-m-d'),
            ],
        ]);
    }

    // ── تحويل الحالة إلى نص عربي ─────────────────────────────────
    private function statusLabel(string $status): string
    {
        return match($status) {
            'pending'   => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default     => $status,
        };
    }
}
