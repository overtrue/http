<p> 
    <h1 align="center">Http</h1>
</p>

<p align="center"> :cactus: A simple http client wrapper.</p>

<p align="center">
<a href="https://travis-ci.org/overtrue/http"><img src="https://travis-ci.org/overtrue/http.svg?branch=master" alt="Build Status"></a>
<a href="https://packagist.org/packages/overtrue/http"><img src="https://poser.pugx.org/overtrue/http/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/overtrue/http"><img src="https://poser.pugx.org/overtrue/http/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://scrutinizer-ci.com/g/overtrue/http/build-status/master"><img src="https://scrutinizer-ci.com/g/overtrue/http/badges/build.png?b=master" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/overtrue/http/?branch=master"><img src="https://scrutinizer-ci.com/g/overtrue/http/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"></a>
<a href="https://scrutinizer-ci.com/g/overtrue/http/?branch=master"><img src="https://scrutinizer-ci.com/g/overtrue/http/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="https://packagist.org/packages/overtrue/http"><img src="https://poser.pugx.org/overtrue/http/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/overtrue/http"><img src="https://poser.pugx.org/overtrue/http/license" alt="License"></a>
</p>

## Installing

```shell
$ composer require overtrue/http -vvv
```

## Usage

```php
<?php

use Overtrue\Http\Client;

$client = Client::create(); 

$response = $client->get('https://httpbin.org/ip');
//{
//    "ip": "1.2.3.4"
//}
```

### Configuration:

```php

use Overtrue\Http\Client;

$config = [
    'base_uri' => 'https://www.easyhttp.com/apiV2/',
    'timeout' => 3000,
    //'connect_timeout' => 3000,
];

$client = Client::create($config); // or new Client($config);

//...
```

### Custom response type

```php
$config = new Config([
    'base_uri' => 'https://www.easyhttp.com/apiV2/',
    
    // array(default)/collection/object/raw
    'response_type' => 'collection', 
]);

//...
```

### Logging request and response

Install monolog:

```bash
$ composer require monolog/monolog
```
Add logger middleware:

```php
use Overtrue\Http\Client;

$client = Client::create();

$logger = new \Monolog\Logger('my-logger');

$logger->pushHandler(
    new \Monolog\Handler\RotatingFileHandler('/tmp/my-log.log')
);

$client->pushMiddleware(\GuzzleHttp\Middleware::log(
                            $logger,
                            new \GuzzleHttp\MessageFormatter(\GuzzleHttp\MessageFormatter::DEBUG)
                        ));

$response = $client->get('https://httpbin.org/ip');
```

## PHP 扩展包开发

> 想知道如何从零开始构建 PHP 扩展包？
>
> 请关注我的实战课程，我会在此课程中分享一些扩展开发经验 —— [《PHP 扩展包实战教程 - 从入门到发布》](https://learnku.com/courses/creating-package)

## License

MIT
