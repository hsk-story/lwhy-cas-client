<?php


namespace Hsk9044\LwhyCasClient\Contracts;


use Hsk9044\LwhyCasClient\Exceptions\CasBaseException;
use Hsk9044\LwhyCasClient\Exceptions\CasHttpException;
use Hsk9044\LwhyCasClient\Exceptions\CasKeyInvalidException;
use Hsk9044\LwhyCasClient\Traits\CurlClient;
use Illuminate\Support\Str;

class Cas
{

    /**
     * @return Cas
     */
    public static function make() {
        return new static();
    }

    use CurlClient;


    /**
     * 通过ticket验证用户
     * @param $ticket
     * @param $userId
     * @return mixed
     */
    public function authCheck($ticket, $userId) {
//        $casUser = new CasUser("qXZmHRzvtuKRULdTruiMzLLlwI2TZpVs");
//        $casUser->load("qXZmHRzvtuKRULdTruiMzLLlwI2TZpVs");
//        die;



        $result = $this->post('auth', [
            'ticket' => $ticket,
            'id' => $userId,
            'return_menu' => 'y',
            'return_permission' => 'y'
        ]);
        $token = Str::random(32);
        $casUser = new CasUser($token);
        $casUser->put('user_id', $userId);
        $casUser->put('ticket', $ticket);
        $casUser->put('permissions', $result['permissions']);
        $casUser->put('roles', $result['roles']);
        $casUser->put('auth_interval', $result['info']['auth_interval'] ?? 300);

        $casUser->saveToCache();

        return [
            'token' => $token,
            'nav' => $result['nav'],
        ];
    }



    public function getCasUser($token) {
        $casUser = new CasUser($token);
        $casUser->load();

        if($casUser->get('update_time') + $casUser->get('auth_interval') < time()) {
            //需要去CAS服务器重新获取一下登录状态
            try{
                $this->post('refresh', [
                    'ticket' => $casUser->get('ticket'),
                    'id' => $casUser->get('user_id'),
                ]);
            }catch (CasBaseException $e) {
                if($e instanceof CasKeyInvalidException) {
                    //说明ticket已失效, 删除该token
                    $casUser->deleteCache();
                }
                //TODO 当返回cas服务器原子锁定时候重新获取一下本地的缓存更新时间是否最新
                throw $e;
            }

            //更新一下最新缓存时间
            $casUser->saveToCache();
        }

        return $casUser;
    }



    public function logout(CasUser $casUser) {
        $this->post('logout', [
            'ticket' => $casUser->get('ticket'),
            'id' => $casUser->get('user_id'),
        ]);

        $casUser->deleteCache();
    }
}