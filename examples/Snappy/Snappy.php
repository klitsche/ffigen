<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\Snappy;

use FFI;

/**
 * High level binding example based on generated low level library methods & constants
 */
class Snappy
{
    public function compress(string $uncompressed): string
    {
        $uncompressedLength = strlen($uncompressed);
        $maxCompressedLength = Library::snappy_max_compressed_length($uncompressedLength);

        $compressed = Library::new('char[' . $maxCompressedLength . ']');

        $compressedLength = Library::new('size_t');
        $compressedLength->cdata = $maxCompressedLength;

        $status = Library::snappy_compress(
            $uncompressed,
            $uncompressedLength,
            Library::cast('char *', $compressed),
            FFI::addr($compressedLength)
        );

        $this->checkStatus($status);

        return FFI::string($compressed, $compressedLength->cdata);
    }

    public function uncompress(string $compressed): string
    {
        $compressedLength = strlen($compressed);

        $uncompressedLength = Library::new('size_t');
        $status = Library::snappy_uncompressed_length(
            $compressed,
            $compressedLength,
            FFI::addr($uncompressedLength)
        );
        $this->checkStatus($status);

        $uncompressed = Library::new('char[' . $uncompressedLength->cdata . ']');
        $status = Library::snappy_uncompress($compressed, $compressedLength, $uncompressed, FFI::addr($uncompressedLength));
        $this->checkStatus($status);

        return FFI::string($uncompressed, $uncompressedLength->cdata);
    }

    public function validate(string $compressed): bool
    {
        return Library::snappy_validate_compressed_buffer($compressed, strlen($compressed)) === SNAPPY_OK;
    }

    private function checkStatus(int $status): void
    {
        switch ($status) {
            case SNAPPY_BUFFER_TOO_SMALL:
                throw new \RuntimeException('error: buffer too small', SNAPPY_BUFFER_TOO_SMALL);
                break;
            case SNAPPY_INVALID_INPUT:
                throw new \RuntimeException('error: invalid input', SNAPPY_INVALID_INPUT);
                break;
        }
    }
}