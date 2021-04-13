<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/4/7
 * Time: 10:34
 */

namespace Hsk9044\LwhyCasClient\Contracts;

use Hsk9044\LwhyCasClient\Exceptions\CasBaseException;
use Hsk9044\LwhyCasClient\Exceptions\CasHttpException;
use Hsk9044\LwhyCasClient\Exceptions\CasKeyInvalidException;
use Hsk9044\LwhyCasClient\Traits\CasAuthenticatable;
use Hsk9044\LwhyCasClient\Traits\CurlClient;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\Cache;

class CasUser implements AuthorizableContract, AuthenticatableContract{
    use Authorizable;
    use CasAuthenticatable;

    protected $attributes = [];
    protected $token;


    public function __construct($token) {
        $this->token = $token;
    }


    public function put($key, $value = '') {
        if(is_array($key))
            $this->attributes = array_merge($this->attributes, $key);
        else {
            $this->attributes[$key] = $value;
        }
    }


    public function get($key) {
        return $this->attributes[$key];
    }


    // 契约检测权限方法
    public function checkCasPermission($permission, $guardName = null) {
        return array_search($permission, $this->attributes['permissions'] ?? []) !== false;
    }


    //保存到缓存
    public function saveToCache() {
        return $this->save([
            'update_time' => time(),
        ]);
    }



    private function save($options = []) {
        //每个key的缓存保持12小时, 如果key丢失, 则直接跳到登录页
        //每隔authInterval秒的时间需要重新去cas服务器校验一下ticket, 如果ticket失效则挑到登录页
        //如果校验成功则更新上次校验秒的缓存
        $key = "cas_cache_{$this->token}";
        $value = [
            'auth_interval' => $this->attributes['auth_interval'],
            'id' => $this->attributes['id'],
            'name' => $this->attributes['name'],
            'permissions' => $this->attributes['permissions'],
            'roles' => $this->attributes['roles'],
            'ticket' => $this->attributes['ticket'],
        ];

        Cache::store(config('lwhy-cas.cache'))->put($key, array_merge($value, $options), now()->addHours(12));
    }


    public function hasRole($role) {
        return array_search($role, $this->attributes['roles'] ?? []) !== false;
    }


    //从缓存中获取数据
    public function load() {
        $key = "cas_cache_{$this->token}";
        if(!Cache::store(config('lwhy-cas.cache'))->has($key)) return false;

        $attributes = Cache::store(config('lwhy-cas.cache'))->get($key);
        $this->put($attributes);

        return true;
    }




    public function deleteCache() {
        $key = "cas_cache_{$this->token}";
        Cache::store(config('lwhy-cas.cache'))->delete($key);
    }



    private function camelize($uncamelized_words,$separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }
}