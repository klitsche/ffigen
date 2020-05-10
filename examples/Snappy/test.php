<?php
/**
 * snappy binding example using low in high level
 */

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\Snappy;

/** @var \Composer\Autoload\ClassLoader $composerLoader */
$composerLoader = require_once(__DIR__ . '/../../vendor/autoload.php');
$composerLoader->addPsr4('Klitsche\\FFIGen\\Examples\\Snappy\\', __DIR__);
require_once(__DIR__ . '/constants.php');

$input = 'sometest sometest sometest 123 1234 12345';
printf('Input: %s' . PHP_EOL, $input);

$snappy = new Snappy();
$compressed = $snappy->compress($input);
printf('Compressed: %s' . PHP_EOL, $compressed);

if ($snappy->validate($compressed) === false) {
    throw new \RuntimeException('Compressed string looks faulty ...');
}

$uncompressed = $snappy->uncompress($compressed);
printf('Uncompressed: %s' . PHP_EOL, $uncompressed);

printf('Input === Uncompressed: %s' . PHP_EOL, $uncompressed === $input ? 'same' : 'different - but why?');