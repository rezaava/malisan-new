<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $table = 'tours';
    protected $fillable = [
        'active', 'title', 'image', 'title_hint', 'abs_hint',
        'keyword_hint', 'file_hint', 'sponser_name', 'sponser_image',
        'donate', 'title_min', 'title_max', 'abs_min', 'abs_max',
        'key_min', 'key_max', 'keyword_min', 'keyword_max', 'file_max'
    ];

    public function touradmins()
    {
        return $this->hasMany(Touradmin::class, 'tour_id');
    }

    public function tourusers()
    {
        return $this->hasMany(Touruser::class, 'tour_id');
    }
}