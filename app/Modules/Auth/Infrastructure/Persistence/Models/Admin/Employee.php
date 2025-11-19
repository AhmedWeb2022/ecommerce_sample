<?php

namespace App\Modules\Auth\Infrastructure\Persistence\Models\Admin;

use App\Modules\Auth\Domain\Entity\AuthEmployeeEntity;
use App\Modules\Auth\Infrastructure\Persistence\Models\Admin\EmployeeDevice\EmployeeDevice;
use App\Modules\Auth\Infrastructure\Persistence\Models\Permission\Permission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use App\Modules\Base\Domain\Entity\AuthEntityAbstract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Modules\Base\Domain\Holders\AuthHolderInterface;
use App\Modules\Base\Domain\Services\Email\EmailNotification;
use App\Modules\Auth\Infrastructure\Persistence\Repositories\Admin\EmployeeRepository;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Employee extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = 'employees';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'email_verified_at',
        'password',
        'remember_token',
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

    protected function imageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getAttribute('image') ? asset("storage/{$this->getAttribute('image')}") : null
        );
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }


   
}
