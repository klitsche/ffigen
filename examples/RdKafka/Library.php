<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\RdKafka;

use FFI;
use FFI\CData;
use FFI\CType;

class Library
{
    use Methods;

    private static FFI $ffi;

    private function __construct()
    {
    }

    /**
     * @param CType|string $type
     */
    public static function new($type, bool $owned = true, bool $persistent = false): CData
    {
        return self::getFFI()->new($type, $owned, $persistent);
    }

    /**
     * @param CType|string $type
     */
    public static function cast($type, CData $ptr): CData
    {
        return self::getFFI()->cast($type, $ptr);
    }

    /**
     * @param CType|string $type
     */
    public static function type($type): CType
    {
        return self::getFFI()->type($type);
    }

    public static function getFFI(): FFI
    {
        if (isset(self::$ffi) === false) {
            self::$ffi = FFI::cdef(FFI_CDEF, FFI_LIB);
        }

        return self::$ffi;
    }
}
