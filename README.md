# ffigen - a FFI binding generator for PHP

`ffigen` is a simple cli helper to quickly generate and update PHP FFI bindings for C libraries. 

It generates two PHP files out of provided C header file(s):

* `constants.php` - holding constant values
* `Methods.php` - holding function bindings as static methods plus phpdoc in a trait

It is heavily inspired by [FFIMe](https://github.com/ircmaxell/FFIMe) and depends on [PHPCParser](https://github.com/ircmaxell/php-c-parser) by ircmaxell. 

__WIP__: Expect breaking changes along all 0.* pre-releases.

## Quick Start

Install in your project:

    composer require --dev klitsche/ffigen
    
Install a c library (eg. uuid).

Add a config file to your project root:

    .ffigen.yml
    
Tweak this config file (example):

```yaml
headerFiles:
  - uuid/uuid.h
libraryFile: libuuid.so.1
parserClass: Klitsche\FFIGen\Examples\UUID\FFIGen\Parser
outputPath: ./
excludeConstants:
  - /^(?!(FFI|UUID)_).*/
excludeMethods:
namespace: Klitsche\FFIGen\Examples\UUID
```

Optional: add your own Parser class to customize pre oder post processing logic (example):

```php
<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\UUID\FFIGen;

use Klitsche\FFIGen\Config;

class Parser extends \Klitsche\FFIGen\Adapter\PHPCParser\Parser
{
    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->context->defineInt('_SYS_TYPES_H', 1);
        $this->context->defineInt('_SYS_TIME_H', 1);
        $this->context->defineInt('_TIME_H', 1);
    }

    protected function parseHeaderFile(string $file): array
    {
        $file = $this->searchHeaderFilePath($file);

        $prependHeaderFile = '
            typedef long time_t;
        ';
        $tmpfile = tempnam(sys_get_temp_dir(), 'ffigen');
        file_put_contents($tmpfile, $prependHeaderFile . file_get_contents($file));

        $declarations = parent::parseHeaderFile($tmpfile);

        unlink($tmpfile);

        return $declarations;
    }

    private function searchHeaderFilePath(string $file): string
    {
        if (file_exists($file)) {
            return $file;
        }
        foreach ($this->context->headerSearchPaths as $headerSearchPath) {
            if (file_exists($headerSearchPath . '/' . $file)) {
                return $headerSearchPath . '/' . $file;
            }
        }

        throw new \RuntimeException(sprintf('File not found: %s', $file));
    }
}
```

Do not forget to register the Parser namespace in your composer.json for autoloading (dev is okay):

```json
    "autoload-dev": {
        "psr-4": {
            "Klitsche\\FFIGen\\Examples\\": "examples"
        }
    },
```

Dump autoloading with 

    composer dump-autoload

Run ffigen to generate binding files

    vendor/bin/ffigen
    
This generates the two files in the output path:

* `constants.php` - add this to your autoloading
* `Methods.php` - add this to your own Class context

Do not forget to add `constants.php` to your compose.json for autoloading:

```json
    "autoload": {
        "files": [
          "tweak-path-to/constants.php",
        ]
    },
```

## Play with examples

Build docker image with preinstalled c libraries (uuid, snappy & librdkafka):

     docker-compose build php74
     
Run snappy example

    docker-compose run --rm php74 bin/ffigen generate -c examples/Snappy/.ffigen.yml
    docker-compose run --rm php74 php examples/Snappy/test.php
        
Run uuid example

    docker-compose run --rm php74 php bin/ffigen generate -c examples/UUID/.ffigen.yml
    docker-compose run --rm php74 php examples/UUID/test.php
        
Run rdkafka example (librdkafka 1.4.0 & mock cluster)

    docker-compose run --rm php74 bin/ffigen generate -c examples/RdKafka/.ffigen.yml
    docker-compose run --rm php74 php examples/RdKafka/test.php
        
## Todos

* [ ] Add more tests 
* [ ] Add travis 
* [ ] Add documentation
* [ ] Add support for Windows, macOS
* [ ] Add more examples (and learn from them)
* [ ] Think about multi version support
* [ ] Think about custom interface / class generation for types
* [ ] Think about clang adapter (pp-trace yml & clang ast-dump as json)