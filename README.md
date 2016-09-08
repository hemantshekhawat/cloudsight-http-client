[![Latest Stable Version][packagist-icon]][packagist]
[![MIT License][license-icon]][MIT]
[![Build status: Travis-CI][travis-icon]][travis-ci]

# cloudsight-http-client

PHP HTTP client library for the [CloudSight][] API, with mock API functionality.


## Installation

Install using [Composer][], via [Packagist][]


    composer require hemantshekhawat/cloudsight-http-client:dev-master 


## Usage

Register with [CloudSight][] to get an API key.

```php
<?php
use Nfreear\Cloudsight\Cloudsight_Http_Client;

$client = new CloudSight_Http_Client($api_key);

$request = $client->postImageRequests($image_url);

while (1) {

    sleep(1);

    $result = $client->getImageResponses($request->token);

    // Check if analysis is complete.
    if ($client->isComplete()) {
        break;
    }
}

echo "Complete. ALT text: " . $result->name;
?>
```

Try the command line example:

    composer example

And, a web server based example:

    composer web


---
_NOTE: this library is NOT endorsed by CloudSight._


© 2016 Hemant Shekhawat. License: [MIT][].

[![author: @hsshekhawat on Twitter][author-icon]][twitter]

