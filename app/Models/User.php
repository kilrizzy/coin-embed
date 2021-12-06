<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Newsletter;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','newsletter',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function updateNewsletterService()
    {
        if(config('newsletter.enabled')) {
            if ($this->newsletter) {
                if (!Newsletter::isSubscribed($this->email)) {
                    $nameParts = explode(' ', $this->name);
                    $firstName = $nameParts[0] ?? null;
                    unset($nameParts[0]);
                    $lastName = implode(' ',$nameParts);
                    Newsletter::subscribeOrUpdate($this->email, ['FNAME' => $firstName, 'LNAME' => $lastName]);
                }
            } else {
                if (Newsletter::isSubscribed($this->email)) {
                    Newsletter::unsubscribe($this->email);
                }
            }
        }
    }

}
