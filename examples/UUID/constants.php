<?php
/**
 * This file is generated! Do not edit directly.
 */

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\UUID;

/**
 * #define
 */
const UUID_VARIANT_NCS = 0;
/**
 * #define
 */
const UUID_VARIANT_DCE = 1;
/**
 * #define
 */
const UUID_VARIANT_MICROSOFT = 2;
/**
 * #define
 */
const UUID_VARIANT_OTHER = 3;
/**
 * #define
 */
const UUID_VARIANT_SHIFT = 5;
/**
 * #define
 */
const UUID_VARIANT_MASK = 7;
/**
 * #define
 */
const UUID_TYPE_DCE_TIME = 1;
/**
 * #define
 */
const UUID_TYPE_DCE_SECURITY = 2;
/**
 * #define
 */
const UUID_TYPE_DCE_MD5 = 3;
/**
 * #define
 */
const UUID_TYPE_DCE_RANDOM = 4;
/**
 * #define
 */
const UUID_TYPE_DCE_SHA1 = 5;
/**
 * #define
 */
const UUID_TYPE_SHIFT = 4;
/**
 * #define
 */
const UUID_TYPE_MASK = 15;
/**
 * #define
 */
const UUID_STR_LEN = 37;
/**
 */
const FFI_CDEF = 'typedef long time_t;
typedef unsigned char uuid_t[16];
extern void uuid_clear(uuid_t uu);
extern int uuid_compare(const uuid_t uu1, const uuid_t uu2);
extern void uuid_copy(uuid_t dst, const uuid_t src);
extern void uuid_generate(uuid_t out);
extern void uuid_generate_random(uuid_t out);
extern void uuid_generate_time(uuid_t out);
extern int uuid_generate_time_safe(uuid_t out);
extern void uuid_generate_md5(uuid_t out, const uuid_t ns, const char *name, size_t len);
extern void uuid_generate_sha1(uuid_t out, const uuid_t ns, const char *name, size_t len);
extern int uuid_is_null(const uuid_t uu);
extern int uuid_parse(const char *in, uuid_t uu);
extern void uuid_unparse(const uuid_t uu, char *out);
extern void uuid_unparse_lower(const uuid_t uu, char *out);
extern void uuid_unparse_upper(const uuid_t uu, char *out);
extern time_t uuid_time(const uuid_t uu, struct timeval *ret_tv);
extern int uuid_type(const uuid_t uu);
extern int uuid_variant(const uuid_t uu);
const extern uuid_t *uuid_get_template(const char *alias);
';
/**
 */
const FFI_LIB = 'libuuid.so.1';
