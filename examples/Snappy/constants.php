<?php
/**
 * This file is generated! Do not edit directly.
 */

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\Snappy;

/**
 * enum snappy_status
 */
const SNAPPY_OK = 0;
/**
 * enum snappy_status
 */
const SNAPPY_INVALID_INPUT = 1;
/**
 * enum snappy_status
 */
const SNAPPY_BUFFER_TOO_SMALL = 2;
/**
 */
const FFI_CDEF = 'typedef long int ptrdiff_t;
typedef int wchar_t;
typedef enum {
  SNAPPY_OK = 0,
  SNAPPY_INVALID_INPUT = 1,
  SNAPPY_BUFFER_TOO_SMALL = 2,
} snappy_status;
snappy_status snappy_compress(const char *input, size_t input_length, char *compressed, size_t *compressed_length);
snappy_status snappy_uncompress(const char *compressed, size_t compressed_length, char *uncompressed, size_t *uncompressed_length);
size_t snappy_max_compressed_length(size_t source_length);
snappy_status snappy_uncompressed_length(const char *compressed, size_t compressed_length, size_t *result);
snappy_status snappy_validate_compressed_buffer(const char *compressed, size_t compressed_length);
';
/**
 */
const FFI_LIB = 'libsnappy.so.1';
