<?php
/**
 * snappy low level binding example
 */

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\Snappy;

use FFI;

/** @var \Composer\Autoload\ClassLoader $composerLoader */
$composerLoader = require_once(__DIR__ . '/../../vendor/autoload.php');
$composerLoader->addPsr4('Klitsche\\FFIGen\\Examples\\Snappy\\', __DIR__);
require_once(__DIR__ . '/constants.php');

$input = 'sometest sometest sometest 123 1234 12345';

// compress
$maxCompressedLength = Library::snappy_max_compressed_length(strlen($input));

$compressed = Library::new('char[' . $maxCompressedLength . ']');

$compressedLength = Library::new('size_t');
$compressedLength->cdata = $maxCompressedLength;

$status = Library::snappy_compress(
    $input,
    strlen($input),
    FFI::cast('char *', $compressed),
    FFI::addr($compressedLength)
);
checkStatus($status);

$compressedString = FFI::string($compressed, $compressedLength->cdata);
var_dump($compressedString);

// uncompress again
$uncompressedLength = Library::new('size_t');
$status = Library::snappy_uncompressed_length(
    $compressedString,
    strlen($compressedString),
    FFI::addr($uncompressedLength)
);
checkStatus($status);

$uncompressed = Library::new('char[' . $uncompressedLength->cdata . ']');
Library::snappy_uncompress($compressedString, strlen($compressedString), $uncompressed, FFI::addr($uncompressedLength));

$output = FFI::string($uncompressed, $uncompressedLength->cdata);
var_dump($output);

var_dump($input === $output);

function checkStatus(int $status): void
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
