<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

class Builtin extends Type
{
    private const C_PHP_MAP = [
        'bool' => 'int',
        'char' => 'int',
        'int' => 'int',
        'short' => 'int',
        'long' => 'int',
        'long long' => 'int',
        'long int' => 'int',
        'long long int' => 'int',
        'int8_t' => 'int',
        'uint8_t' => 'int',
        'int16_t' => 'int',
        'uint16_t' => 'int',
        'int32_t' => 'int',
        'uint32_t' => 'int',
        'int64_t' => 'int',
        'uint64_t' => 'int',
        'signed' => 'int',
        'signed char' => 'int',
        'signed int' => 'int',
        'signed long' => 'int',
        'signed long int' => 'int',
        'signed long long' => 'int',
        'signed long long int' => 'int',
        'unsigned' => 'int',
        'unsigned char' => 'int',
        'unsigned int' => 'int',
        'unsigned short' => 'int',
        'unsigned long' => 'int',
        'unsigned long int' => 'int',
        'unsigned long long' => 'int',
        'unsigned long long int' => 'int',
        'size_t' => 'int',
        'float' => 'float',
        'double' => 'float',
        'long double' => 'float',
        'void' => 'void', // return type only
    ];

    private string $phpType;

    public function __construct(string $cName)
    {
        parent::__construct($cName);
        $this->phpType = self::map($cName);
    }

    public static function isMappable(string $cName): bool
    {
        return isset(self::C_PHP_MAP[$cName]);
    }

    public static function map(string $cName): string
    {
        if (self::isMappable($cName) === false) {
            throw new \InvalidArgumentException(sprintf('Cannot map %s to native php type', $cName));
        }
        return self::C_PHP_MAP[$cName];
    }

    public function getPhpTypes(): string
    {
        if ($this->getCName() === 'void') {
            return $this->phpType;
        }
        return '?' . $this->phpType;
    }

    public function getPhpDocTypes(): string
    {
        if ($this->getCName() === 'void') {
            return $this->phpType;
        }
        return $this->phpType . '|null';
    }
}
