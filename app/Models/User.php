<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, HasApiTokens;

    protected $tableName = "users";

    protected $type = 'string';
    protected $increamenting = false;

    protected $fillable = [
        'username',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'last_point_at'
    ];

    protected $appends = ['current_streak'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            "last_streak_date" => "date"
        ];
    }

    public function points() {
        $this->hasMany(Point::class);
    }

    public function updateStreak()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        if ($this->last_streak_date?->equalTo($today)) {
            return;
        }

        if ($this->last_streak_date?->equalTo($yesterday)) {
            $this->streak_count += 1;
        }else {
            $this->streak_count = 1;
        }

        $this->last_streak_date = $today;
        $this->save();
    }

    protected function currentStreak(): Attribute
    {
        return Attribute::make(
            get: function () {
                $today = Carbon::today();
                $yesterday = Carbon::yesterday();

                if ($this->last_streak_date?->equalTo($today) || $this->last_streak_date?->equalTo($yesterday)) {
                    return $this->streak_count;
                }
                return 0;
            }
        );
    }
}
