<?php

namespace App\Modules\Auth\Infrastructure\Persistence\Models\Customer;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Modules\Auth\Domain\Entity\AuthUserEntity;
use App\Modules\Auth\Infrastructure\Persistence\Entities\UserEntity;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\BusinessRegistrationDetails\BusinessRegistrationDetails;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\PhoneRequest\PhoneRequest;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\UserAddress\UserAddress;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\UserDevice\UserDevice;
use App\Modules\Auth\Infrastructure\Persistence\Repositories\Customer\UserRepository;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Modules\Base\Domain\Holders\AuthHolderInterface;
use App\Modules\Base\Domain\Services\Email\EmailNotification;
use App\Modules\Notification\Infrastructure\Persistence\Models\Notification\Notification;
use App\Modules\Order\Infrastructure\Persistence\Models\Order\Order;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LDAP\Result;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens/* , SoftDeletes */;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = 'users';


    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }


    public function getImageLinkAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }




    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }



  


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}
