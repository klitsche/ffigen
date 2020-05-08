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
