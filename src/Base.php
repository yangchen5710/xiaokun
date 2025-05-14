<?php

namespace Ycstar\Xiaokun;

use GuzzleHttp\Client;

class Base
{
    protected string $host;

    protected string $key;

    protected string $appId;

    protected array $guzzleOptions;

    public function __construct(string $host, string $key, string $appId, array $guzzleOptions = [])
    {
        $this->host = $host;
        $this->key = $key;
        $this->appId = $appId;
        $this->guzzleOptions = $guzzleOptions;
    }

    public function request($method, array $options = [])
    {
        $params = array_merge($options, [
            'appId' => $this->appId,
            'timestamp' => round(microtime(true) * 1000), //时间戳，精确到毫秒（13位）
        ]);

        $params['sign'] = $this->getSign($params);

        $response = $this->getHttpClient()->post($this->getUrl($method), [
            'json' => $params,
        ])->getBody()->getContents();
        return json_decode($response, true);
    }

    public function getUrl($method): string
    {
        return $this->host.'/apistand/driver/'.$method;
    }

    public function getSign(array $options): string
    {
        ksort($options);
        $hashStr = '';
        foreach ($options as $key => $val) {
            if (!$val || $val == '' || $key == 'sign') {
                continue;
            }
            $hashStr .= '&'.$key . '=' . $val;
        }

        // 去除第一个字符（如果它是 &）
        if (substr($hashStr, 0, 1) === '&') {
            $hashStr = substr($hashStr, 1);
        }

        return hash('sha256', $hashStr . $this->key);
    }

    /**
     * 获取回调数据
     * @return array
     */
    public function getNotify()
    {
        $data = file_get_contents('php://input');
        return json_decode($data, true);
    }

    /**
     * 获取回调数据回复内容
     * @return array
     */
    public function getNotifySuccessReply()
    {
        return ['error_code'=>0,'error_msg'=>'success'];
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

}