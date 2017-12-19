<p> 
    <h1 align="center">Http</h1>
</p>

<p align="center">A simple http client wrapper.</p>

## Installing

```php
$ composer require overtrue/http -vvv
```

## Usage

```php
$client = Client::create(); 

$response = $client->get('https://httpbin.org/ip');
//{
//    "ip": "1.2.3.4"
//}
```

## License

MIT
