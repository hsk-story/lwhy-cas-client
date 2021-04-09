<?php
namespace Hsk9044\LwhyCasClient\Exceptions;

class CasKeyInvalidException extends CasBaseException
{
    public function __construct($errCode, string $errMsg = '单点Key失效', array $data = []) {
        parent::__construct($errCode, $errMsg, $data);
    }
}
