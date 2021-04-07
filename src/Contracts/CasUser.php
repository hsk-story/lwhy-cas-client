<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/4/7
 * Time: 10:34
 */

namespace Hsk9044\LwhyCasClient\Contracts;

//use Illuminate\Auth\Authenticatable;
use Hsk9044\LwhyCasClient\Traits\CasAuthenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;

class CasUser implements AuthorizableContract{
    use Authorizable;
//    use CasAuthenticatable;

    protected $attr;

    public function __construct(array $attributes) {
        $this->attr = $attributes;
    }

    public function checkCasPermission($permission, $guardName = null) {
        dd($permission);
    }
}