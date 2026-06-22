<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';
    protected $fillable = ['survey_id', 'option'];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}