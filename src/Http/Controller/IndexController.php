<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/4/7
 * Time: 11:11
 */

namespace Hsk9044\LwhyCasClient\Http\Controller;


use Hsk9044\LwhyCasClient\Contracts\CasFactor;
use Hsk9044\LwhyCasClient\Contracts\CasUser;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller {

    public function index() {
//        $user = Cas::make()->getCasUser("W9UBbSr9P2MYxY7dSFKDjL2gNLnwQsAE");
//        Cas::make()->logout($user);
//        dd($user);
/*        $user = (new CasUser(['id'=>1, 'name'=>'哈哈哈']));
//        $user->can('abc123');

        dump(Auth::guard('cas')->user());
//        Auth::login($user);
        Auth::guard('cas')->login($user);*/

        $r = CasFactor::make()->authCheck(request()->input('ticket'), request()->input('id'));
        dd($r);
    }

}