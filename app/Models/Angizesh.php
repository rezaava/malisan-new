<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angizesh extends Model
{
    use HasFactory;

    protected $table = 'angizeshes';
    protected $fillable = ['text', 'level'];
}