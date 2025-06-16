<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracker extends Model
{
    use HasFactory;
    protected $table = 'trackers';
    protected $fillable = [
        'type',
        'amount',
        'category',
        'date',
        'note',
        'user_id',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
  



