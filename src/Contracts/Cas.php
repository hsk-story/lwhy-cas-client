<?php


namespace Hsk9044\LwhyCasClient\Contracts;


use Hsk9044\LwhyCasClient\Cache\UserCache;
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
        $result = $this->post('auth', [
            'ticket' => $ticket,
            'id' => $userId,
            'return_menu' => 'y',
            'return_permission' => 'y'
        ]);

        $token = Str::random(32);
        $casUser = new CasUser();
        $casUser->put('user_id', $userId);
        $casUser->put('ticket', $ticket);
        $casUser->put('permissions', $result['return_permission']);
        $casUser->put('roles', $result['roles']);
        $casUser->put('auth_interval');
        $casUser->put('token', $token);

        $casUser->save($token, $result['info']['auth_interval'] ?? 300);

        return $result;
    }
}