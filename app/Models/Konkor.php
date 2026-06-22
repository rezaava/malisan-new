<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Konkor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'konkors';
    protected $fillable = ['active', 'reshte', 'gerayesh', 'dars'];
    protected $dates = ['deleted_at'];

    public function coworkers()
    {
        return $this->hasMany(Coworker::class, 'konkor_id');
    }

    public function konkorqs()
    {
        return $this->hasMany(Konkorq::class, 'konkor_id');
    }

    public function leitners()
    {
        return $this->hasMany(Leitner::class, 'konkor_id');
    }
}