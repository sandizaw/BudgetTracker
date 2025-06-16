<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        
    ];
    protected $table = 'categories';

    public function trackers()
    {
        return $this->hasMany(Tracker::class);
    }
        public function user()
{
    return $this->belongsTo(User::class);
}
}