<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coworker extends Model
{
    use HasFactory;

    protected $table = 'coworkers';
    protected $fillable = ['konkor_id', 'user_id'];

    public function konkor()
    {
        return $this->belongsTo(Konkor::class, 'konkor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}