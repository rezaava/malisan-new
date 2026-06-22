<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $table = 'shops';
    protected $fillable = [
        'name', 'description', 'discount_percent', 'address', 'phone',
        'latitude', 'longitude', 'image', 'user_id', 'active',
        'checkout_sheba', 'checkout_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}