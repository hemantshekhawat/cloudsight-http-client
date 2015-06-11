[![Latest Stable Version][packagist-icon]][packagist]
[![MIT License][license-icon]][MIT]

# cloudsight-http-client

PHP HTTP client library for the [CloudSight][] API, with mock API functionality.


## Installation

Install using [Composer][], via [Packagist][]


    composer require nfreear/cloudsight-http-client:dev-master 


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


© 2015 Nick Freear. License: [MIT][].

[![author: @nfreear on Twitter][author-icon]][twitter]


[github]: https://github.com/nfreear/cloudsight-http-client
[source-icon]: https://img.shields.io/badge/source-nfreear%2Fcloudsight--http--client-blue.svg
[twitter]: https://twitter.com/nfreear
[author-icon]: https://img.shields.io/badge/author-%40nfreear-blue.svg
[Packagist]: https://packagist.org/packages/nfreear/cloudsight-http-client
[packagist-icon]: https://img.shields.io/packagist/v/nfreear/cloudsight-http-client.svg
[license-icon]: https://img.shields.io/packagist/l/nfreear/cloudsight-http-client.svg
[MIT]: http://nfreear.mit-license.org/
[CloudSight]: https://cloudsightapi.com/
[CloudSight documentation]: http://cloudsight.readme.io/v1.0/docs
[Composer]: https://getcomposer.org/

