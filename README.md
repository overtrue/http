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

## License

MIT
