<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestStatus extends Model
{
    use HasFactory;

    protected $table = 'request_statuses';

    protected $fillable = ['name', 'color'];

    public function requests(): HasMany
    {
        return $this->hasMany(ItemRequest::class, 'request_status_id');
    }
}
