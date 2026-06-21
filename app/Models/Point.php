<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Point extends Model
{
    use HasFactory, Notifiable, HasUuids;

    protected $table = "points";
    protected $type = "string";
    protected $incrementing = false;

    protected $fillable = [
        'amount',
        'type',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
