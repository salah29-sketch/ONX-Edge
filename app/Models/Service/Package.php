<?php
namespace App\Models\Service;

use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'subtitle',
        'description',
        'price',
        'old_price',
        'price_note',
        'duration',
        'features',
        'is_featured',
        'is_buildable',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'old_price'    => 'decimal:2',
        'features'     => 'array',
        'is_featured'  => 'boolean',
        'is_buildable' => 'boolean',
        'is_active'    => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────────

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(PackageOption::class)->orderBy('sort_order');
    }

    public function activeOptions(): HasMany
    {
        return $this->hasMany(PackageOption::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function serviceItems(): BelongsToMany
    {
        return $this->belongsToMany(ServiceItem::class, 'package_service_items')
                    ->withPivot('quantity_label', 'sort_order')
                    ->orderBy('package_service_items.sort_order')
                    ->withTimestamps();
    }

    // ── Scopes ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function priceDisplay(): string
    {
        if ($this->price > 0) {
            return number_format((float) $this->price);
        }
        return $this->price_note ?? 'حسب الطلب';
    }

    public function currencyLabel(): string
    {
        return $this->service?->isSubscription() ? 'DA / شهر' : 'DA';
    }

    public function fullPriceDisplay(): string
    {
        if ($this->price > 0) {
            return $this->priceDisplay() . ' ' . $this->currencyLabel();
        }
        return $this->price_note ?? 'حسب الطلب';
    }

    public function hasDiscount(): bool
    {
        return $this->old_price && $this->price < $this->old_price;
    }

    public function getFeaturesAttribute($value): array
    {
        if ($this->relationLoaded('serviceItems') && $this->serviceItems->isNotEmpty()) {
            return $this->serviceItems->map(function ($item) {
                return $item->pivot->quantity_label
                    ? $item->name . " (" . $item->pivot->quantity_label . ")"
                    : $item->name;
            })->toArray();
        }
        if (is_array($value)) return $value;
        return json_decode($value, true) ?? [];
    }
}
