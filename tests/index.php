<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    $host = 'https://www.xiaokunkeji.com';
    $key = 'ASFDJ_XK';
    $appId = '31u9t2f6c7fxkbd231h06157crf50fg';

    $client = new \Ycstar\Xiaokun\Xiaokun($host, $key, $appId, ['debug' => true]);


    $params = [
        'lng' => '121.51464',
        'lat' => '31.104135',
        'productCode' => 'ASFDJ-001',
        'mobile' => '13800000000',
    ];
    $res = $client->openCity($params);

    //$res = $client->nearDriver();

    var_dump($res);