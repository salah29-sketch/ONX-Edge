<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceItem extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'addon_price',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'addon_price' => 'decimal:2',
        'is_active'   => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_service_items')
                    ->withPivot('quantity_label', 'sort_order')
                    ->withTimestamps();
    }

    public function isAddon(): bool
    {
        return $this->addon_price !== null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
