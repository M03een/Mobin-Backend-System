<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model
{
    use HasUuids;
    protected $table = "password_otps";
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        "email",
        "otp",
        "expires_at"
    ];
}
