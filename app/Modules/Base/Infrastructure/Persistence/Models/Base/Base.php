<?php

namespace App\Modules\Base\Infrastructure\Persistence\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Base extends Model
{
    use HasFactory;
    protected $fillable = [
        'base',
    ];
}
