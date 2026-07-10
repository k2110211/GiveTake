<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
