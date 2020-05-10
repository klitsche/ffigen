<?php
/**
 * This file is generated! Do not edit directly.
 */

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\Snappy;

trait Methods
{
    abstract public static function getFFI(): \FFI;

    /**
     * @param string|null $input char*
     * @param int|null $input_length size_t
     * @param \FFI\CData|null $compressed char*
     * @param \FFI\CData|null $compressed_length size_t*
     * @return int snappy_status
     */
    public static function snappy_compress(?string $input, ?int $input_length, ?\FFI\CData $compressed, ?\FFI\CData $compressed_length): int
    {
        return static::getFFI()->snappy_compress($input, $input_length, $compressed, $compressed_length);
    }

    /**
     * @param string|null $compressed char*
     * @param int|null $compressed_length size_t
     * @param \FFI\CData|null $uncompressed char*
     * @param \FFI\CData|null $uncompressed_length size_t*
     * @return int snappy_status
     */
    public static function snappy_uncompress(?string $compressed, ?int $compressed_length, ?\FFI\CData $uncompressed, ?\FFI\CData $uncompressed_length): int
    {
        return static::getFFI()->snappy_uncompress($compressed, $compressed_length, $uncompressed, $uncompressed_length);
    }

    /**
     * @param int|null $source_length size_t
     * @return int|null size_t
     */
    public static function snappy_max_compressed_length(?int $source_length): ?int
    {
        return static::getFFI()->snappy_max_compressed_length($source_length);
    }

    /**
     * @param string|null $compressed char*
     * @param int|null $compressed_length size_t
     * @param \FFI\CData|null $result size_t*
     * @return int snappy_status
     */
    public static function snappy_uncompressed_length(?string $compressed, ?int $compressed_length, ?\FFI\CData $result): int
    {
        return static::getFFI()->snappy_uncompressed_length($compressed, $compressed_length, $result);
    }

    /**
     * @param string|null $compressed char*
     * @param int|null $compressed_length size_t
     * @return int snappy_status
     */
    public static function snappy_validate_compressed_buffer(?string $compressed, ?int $compressed_length): int
    {
        return static::getFFI()->snappy_validate_compressed_buffer($compressed, $compressed_length);
    }
}
