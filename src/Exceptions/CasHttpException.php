<?php
namespace Hsk9044\LwhyCasClient\Exceptions;

class CasHttpException extends CasBaseException
{
    public function __construct($errCode, $errMsg='Cas服务器请求错误', $data=[])
    {
        parent::__construct($errCode, $errMsg, $data);
    }
}
