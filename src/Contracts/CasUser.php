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

    protected $attributes = [];


    public function __construct() {
/*        $this->ticket = $ticket;
        $this->userId = $userId;
        $this->permissions = $permissions;
        $this->roles = $roles;*/
    }


    public function put($key, $value = '') {
        if(is_array($key))
            $this->attributes = array_merge($this->attributes, $key);
        else
            $this->attributes[$key] = $value;
    }



    //TODO 契约检测权限方法
    public function checkCasPermission($permission, $guardName = null) {
        dd($permission);
    }

    //保存到缓存
    public function save($key, $authInterval) {
        //每个key的缓存保持24小时, 如果key丢失, 则直接跳到登录页
        //每隔authInterval秒的时间需要重新去cas服务器校验一下ticket, 如果ticket失效则挑到登录页
        //如果校验成功则更新上次校验秒的缓存

    }


    private function camelize($uncamelized_words,$separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }
}