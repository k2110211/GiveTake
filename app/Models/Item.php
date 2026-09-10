<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'thumbnail',
        'images',
        'type_id',
        'exchange_wish',
        'min_karma',
        'winner_id',
        'raffle_ends_at',
        'item_status_id',
        'city_id',
        'district_id',
        'rejection_reason',
        'approved_at'
    ];

    protected $attributes = [
        'images' => '[]',
    ];

    protected $casts = [
        'min_karma' => 'integer',
        'approved_at' => 'datetime',
        'raffle_ends_at' => 'datetime',
    ];

    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return null;
                }
                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    if (str_contains($value, '/storage/')) {
                        $relativePath = substr($value, strpos($value, '/storage/') + 9);
                        return asset('storage/' . $relativePath);
                    }
                    return $value;
                }
                return asset('storage/' . $value);
            }
        );
    }

    protected function images(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return [];
                }
                $decoded = is_string($value) ? json_decode($value, true) : $value;
                if (!is_array($decoded)) {
                    return [];
                }
                return array_values(array_map(function ($img) {
                    if (!$img) {
                        return null;
                    }
                    if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                        if (str_contains($img, '/storage/')) {
                            $relativePath = substr($img, strpos($img, '/storage/') + 9);
                            return asset('storage/' . $relativePath);
                        }
                        return $img;
                    }
                    return asset('storage/' . $img);
                }, $decoded));
            },
            set: function ($value) {
                return is_array($value) ? json_encode(array_values(array_filter($value))) : $value;
            }
        );
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ItemStatus::class, 'item_status_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ItemRequest::class);
    }
}
