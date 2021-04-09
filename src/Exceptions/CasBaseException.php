<?php


namespace Hsk9044\LwhyCasClient\Exceptions;


class CasBaseException extends \Exception
{
    protected $errCode;
    protected $errMsg;
    protected $data;

    public function __construct($errCode, $errMsg='', $data=[])
    {
        parent::__construct($errCode.'|'.$errMsg);
        $this->errCode = $errCode;
        $this->errMsg = $errMsg;
        $this->data = $data;
    }

    public function getErrCode()
    {
        return $this->errCode;
    }

    public function getErrMsg()
    {
        return $this->errMsg;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setErrMsg($msg)
    {
        $this->errMsg = $msg;
    }
}