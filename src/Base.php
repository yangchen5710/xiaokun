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
            'timestamp' => time(),
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