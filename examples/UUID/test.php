<?php
/**
 * uuid low level binding example
 */

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\UUID;

use Exception;
use FFI;
use FFI\CData;

require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/constants.php');

$dnsNamespace = parse('6ba7b810-9dad-11d1-80b4-00c04fd430c8');

$v1 = Library::new('uuid_t');
Library::uuid_generate_time($v1);
var_dump(unparse($v1));
var_dump(type($v1));

$v3 = Library::new('uuid_t');
Library::uuid_generate_md5($v3, $dnsNamespace, 'foo', 4);
var_dump(unparse($v3));
var_dump(type($v3));

$v4 = Library::new('uuid_t');
Library::uuid_generate_random($v4);
var_dump(unparse($v4));
var_dump(type($v4));

$v5 = Library::new('uuid_t');
Library::uuid_generate_sha1($v5, $dnsNamespace, 'foo', 4);
var_dump(unparse($v5));
var_dump(type($v5));

function unparse(CData $uuid): string
{
    $unparsed = Library::new('char[36]');
    Library::uuid_unparse($uuid, Library::cast(Library::type('void*'), $unparsed));
    return FFI::string($unparsed);
}

function parse(string $uuid): CData
{
    $parsed = Library::new('uuid_t');
    $err = Library::uuid_parse($uuid, $parsed);
    if ($err !== 0) {
        throw new Exception('No valid uuid', $err);
    }
    return $parsed;
}

function type(CData $uuid)
{
    $map = [
        UUID_TYPE_DCE_TIME => 'time', // v1
        UUID_TYPE_DCE_SECURITY => 'security', // v2
        UUID_TYPE_DCE_MD5 => 'md5', // v3
        UUID_TYPE_DCE_RANDOM => 'random', // v4
        UUID_TYPE_DCE_SHA1 => 'sha1', // v5
    ];
    return $map[Library::uuid_type($uuid)] ?? 'unknown';
}
