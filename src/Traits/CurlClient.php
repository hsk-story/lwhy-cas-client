<?php

namespace Hsk9044\LwhyCasClient\Traits;


use App\Exceptions\BaseException;

trait CurlClient
{


    public function post($action, $input = []) {
        $input['project_code'] = config('lwhy-cas.project_code');
        $input['timestamp'] = time();

        ksort($input);
        $str = "";
        foreach ($input as $k => $v) {
            $str.= $k.$v;
        }
        $restr=$str.config('lwhy-cas.secret');
        $sign = strtoupper(md5($restr));
        $input['sign'] = $sign;


        return $this->curl(config('lwhy-cas.cas_server') . 'cas/' . $action, $input);
    }


    protected function curl($url, $postData = [], $method = 'POST') {


        $ch = curl_init();

        if($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, 'POST'); // 发送一个常规的Post请求
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);     // Post提交的数据包
        } else {
            $url = $url . '?' . http_build_query($postData);
        }

        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($httpCode != 200) throw new BaseException("CurlError", $res);

        curl_close($ch);

        return json_decode($res, true);
    }
}