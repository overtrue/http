<p> 
    <h1 align="center">Http</h1>
</p>

<p align="center"> :cactus: A simple http client wrapper.</p>

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

Using configuration:

```php

use Overtrue\Http\Client;
use Overtrue\Http\Config;

$config = new Config([
    'base_uri' => 'https://www.easywechat.com/apiV2/',
    'timeout' => 3000,
    //'connect_timeout' => 3000,
]);

$client = Client::create($config); // or new Client($config);

//...
```

Custom response type:

```php
$config = new Config([
    'base_uri' => 'https://www.easywechat.com/apiV2/',
    
    // array(default)/collection/object/raw
    'response_type' => 'collection', 
]);

//...
```

## License

MIT
