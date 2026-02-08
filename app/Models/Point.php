<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Point extends Model
{
    use HasFactory, Notifiable, HasUuids;

    protected $tableName = "points";
    protected $type = "string";
    protected $increamenting = false;

    protected $fillable = [
        'title',
        'type'
    ];

    public function user() {
        $this->belongsTo(User::class);
    }
}
