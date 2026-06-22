<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Touruser extends Model
{
    use HasFactory;

    protected $table = 'tourusers';
    protected $fillable = [
        'user_id', 'tour_id', 'title', 'abstract', 'keywords', 'file',
        'davar', 'desc_title', 'desc_key', 'desc_abs', 'desc_file',
        'score_file', 'score_abs', 'score_key', 'score_title'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
}