<?php

namespace Hsk9044\LwhyCasClient\Traits;





use Hsk9044\LwhyCasClient\Exceptions\CasHttpException;
use Hsk9044\LwhyCasClient\Exceptions\CasKeyInvalidException;

trait CurlClient
{


    /**
     * @param $action string
     * @param array $input
     * @return array
     * @throws CasHttpException
     */
    public function post($action, $input = []) {
        $input['project_code'] = config('lwhy-cas.project_code');
        $input['timestamp'] = time();
        $input['sign'] = $this->getSign($input);

        return $this->curl(config('lwhy-cas.cas_server') . 'cas/' . $action, $input);
    }


    public function getSign($input) {
        ksort($input);
        $str = "";
        foreach ($input as $k => $v) {
            $str.= $k.$v;
        }
        $restr=$str.config('lwhy-cas.secret');
        return strtoupper(md5($restr));

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

        $array = json_decode($res, true);

        if($httpCode != 200) {
            if($httpCode == 401) {  //说明登录失效状态
                throw new CasKeyInvalidException($array['code']);
            }

            throw new CasHttpException($array['code'] ?? $httpCode, $array['message'] ?? "HTTP状态错误", $array['data'] ?? []);
        }

        curl_close($ch);

        return $array;
    }
}