<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';
    protected $fillable = [
        'user_id', 'cat_id', 'course_id', 'group', 'text', 'type',
        'desc_add', 'active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'survey_id');
    }

    public function optionUsers()
    {
        return $this->hasMany(OptionUser::class, 'survey_id');
    }
}