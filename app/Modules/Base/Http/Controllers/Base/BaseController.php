<?php

namespace App\Modules\Base\Http\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Base\Http\Requests\Base\BaseRequest;
use App\Modules\Base\Application\UseCases\Base\BaseUseCase;
use App\Modules\Base\Domain\Holders\UserHolder;

class BaseController extends Controller
{
    protected $baseUseCase;

   
    public function fetchBase()
    {
        $user = UserHolder::getUser();

        dd($user);
    }
}
