<?php


namespace Hsk9044\LwhyCasClient\Contracts;


use Hsk9044\LwhyCasClient\Traits\CurlClient;

class Cas
{

    /**
     * @return Cas
     */
    public static function make() {
        return new static();
    }

    use CurlClient;

    public function authCheck($ticket, $id) {
        return $this->post('auth', [
            'ticket' => $ticket,
            'id' => $id,
            'return_menu' => 'y',
        ]);
    }
}