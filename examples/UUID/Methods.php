<?php
/**
 * This file is generated! Do not edit directly.
 */

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\UUID;

trait Methods
{
    abstract public static function getFFI(): \FFI;

    /**
     * @param \FFI\CData|null $uu unsigned char[16]
     */
    public static function uuid_clear(?\FFI\CData $uu): void 
    {
        static::getFFI()->uuid_clear($uu);
    }

    /**
     * @param \FFI\CData|null $uu1 unsigned char[16]
     * @param \FFI\CData|null $uu2 unsigned char[16]
     * @return int|null int
     */
    public static function uuid_compare(?\FFI\CData $uu1, ?\FFI\CData $uu2): ?int 
    {
        return static::getFFI()->uuid_compare($uu1, $uu2);
    }

    /**
     * @param \FFI\CData|null $dst unsigned char[16]
     * @param \FFI\CData|null $src unsigned char[16]
     */
    public static function uuid_copy(?\FFI\CData $dst, ?\FFI\CData $src): void 
    {
        static::getFFI()->uuid_copy($dst, $src);
    }

    /**
     * @param \FFI\CData|null $out unsigned char[16]
     */
    public static function uuid_generate(?\FFI\CData $out): void 
    {
        static::getFFI()->uuid_generate($out);
    }

    /**
     * @param \FFI\CData|null $out unsigned char[16]
     */
    public static function uuid_generate_random(?\FFI\CData $out): void 
    {
        static::getFFI()->uuid_generate_random($out);
    }

    /**
     * @param \FFI\CData|null $out unsigned char[16]
     */
    public static function uuid_generate_time(?\FFI\CData $out): void 
    {
        static::getFFI()->uuid_generate_time($out);
    }

    /**
     * @param \FFI\CData|null $out unsigned char[16]
     * @return int|null int
     */
    public static function uuid_generate_time_safe(?\FFI\CData $out): ?int 
    {
        return static::getFFI()->uuid_generate_time_safe($out);
    }

    /**
     * @param \FFI\CData|null $out unsigned char[16]
     * @param \FFI\CData|null $ns unsigned char[16]
     * @param string|null $name char*
     * @param int|null $len size_t
     */
    public static function uuid_generate_md5(?\FFI\CData $out, ?\FFI\CData $ns, ?string $name, ?int $len): void 
    {
        static::getFFI()->uuid_generate_md5($out, $ns, $name, $len);
    }

    /**
     * @param \FFI\CData|null $out unsigned char[16]
     * @param \FFI\CData|null $ns unsigned char[16]
     * @param string|null $name char*
     * @param int|null $len size_t
     */
    public static function uuid_generate_sha1(?\FFI\CData $out, ?\FFI\CData $ns, ?string $name, ?int $len): void 
    {
        static::getFFI()->uuid_generate_sha1($out, $ns, $name, $len);
    }

    /**
     * @param \FFI\CData|null $uu unsigned char[16]
     * @return int|null int
     */
    public static function uuid_is_null(?\FFI\CData $uu): ?int 
    {
        return static::getFFI()->uuid_is_null($uu);
    }

    /**
     * @param string|null $in char*
     * @param \FFI\CData|null $uu unsigned char[16]
     * @return int|null int
     */
    public static function uuid_parse(?string $in, ?\FFI\CData $uu): ?int 
    {
        return static::getFFI()->uuid_parse($in, $uu);
    }

    /**
     * @param \FFI\CData|null $uu unsigned char[16]
     * @param \FFI\CData|null $out char*
     */
    public static function uuid_unparse(?\FFI\CData $uu, ?\FFI\CData $out): void 
    {
        static::getFFI()->uuid_unparse($uu, $out);
    }

    /**
     * @param \FFI\CData|null $uu unsigned char[16]
     * @param \FFI\CData|null $out char*
     */
    public static function uuid_unparse_lower(?\FFI\CData $uu, ?\FFI\CData $out): void 
    {
        static::getFFI()->uuid_unparse_lower($uu, $out);
    }

    /**
     * @param \FFI\CData|null $uu unsigned char[16]
     * @param \FFI\CData|null $out char*
     */
    public static function uuid_unparse_upper(?\FFI\CData $uu, ?\FFI\CData $out): void 
    {
        static::getFFI()->uuid_unparse_upper($uu, $out);
    }

    /**
     * @param \FFI\CData|null $uu unsigned char[16]
     * @param \FFI\CData|null $ret_tv timeval*
     * @return int|null long
     */
    public static function uuid_time(?\FFI\CData $uu, ?\FFI\CData $ret_tv): ?int 
    {
        return static::getFFI()->uuid_time($uu, $ret_tv);
    }

    /**
     * @param \FFI\CData|null $uu unsigned char[16]
     * @return int|null int
     */
    public static function uuid_type(?\FFI\CData $uu): ?int 
    {
        return static::getFFI()->uuid_type($uu);
    }

    /**
     * @param \FFI\CData|null $uu unsigned char[16]
     * @return int|null int
     */
    public static function uuid_variant(?\FFI\CData $uu): ?int 
    {
        return static::getFFI()->uuid_variant($uu);
    }

    /**
     * @param string|null $alias char*
     * @return \FFI\CData|null *(unsigned char)[16]
     */
    public static function uuid_get_template(?string $alias): ?\FFI\CData 
    {
        return static::getFFI()->uuid_get_template($alias);
    }

}
