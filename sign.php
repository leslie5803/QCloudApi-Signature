<?php

class Sign {

    /**
     * 加密算法
     * @param  array  $params  接口参数
     * @return string $url
     */
	public static function makdSign($params){

		$requestParams = [
            'Action' => $action,  //替换为接口名
            'SecretId' => $SecretId, //替换为SecretId
            'Timestamp' => time(), //替换为SecretId
            'Region' => 'gz',
            'Nonce' => rand(1,1000000),
            'SignatureMethod' => $SignatureMethod //替换为加密方式,这里为256
        ];
        $requestParams = array_merge($requestParams, $params);
        //第一步
        ksort($requestParams);
        //第二步
        $str = '';
        foreach ($requestParams as $key => $requestParam) {
            $str .= $key . '=' . $requestParam . '&';
        }
        $str = rtrim($str, '&');
        //第三步
        $requestMethod = 'GET';
        $host = 'vod.api.qcloud.com';
        $requestRoute = '/v2/index.php';
        $originStr = $requestMethod . $host . $requestRoute . '?' . $str;
        $signStr = base64_encode(hash_hmac('sha256', $originStr, $SecretKey, true));
        $urlencodeSignStr = urlencode($signStr);

        //第四步
        $encodeRequestParams = '';
        foreach ($requestParams as $key => $requestParam) {
            $encodeRequestParams .= $key . '=' . urlencode($requestParam) . '&';
        }
        //得到最终请求的url
        $url = 'https://' . $host . $requestRoute . '?' . $encodeRequestParams . 'Signature=' . $urlencodeSignStr;

        return $url;
    }
}