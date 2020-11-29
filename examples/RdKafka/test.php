<?php
/**
 * snappy low level binding example
 */

declare(strict_types=1);

namespace Klitsche\FFIGen\Examples\RdKafka;

use FFI;
use FFI\CData;

/** @var \Composer\Autoload\ClassLoader $composerLoader */
$composerLoader = require_once(__DIR__ . '/../../vendor/autoload.php');
$composerLoader->addPsr4('Klitsche\\FFIGen\\Examples\\RdKafka\\', __DIR__);
require_once(__DIR__ . '/constants.php');

$version = Library::rd_kafka_version_str();
var_dump($version);

// mock cluster produce & consumer example

$conf = Library::rd_kafka_conf_new();
Library::rd_kafka_conf_set($conf, 'test.mock.num.brokers', '3', null, null);
Library::rd_kafka_conf_set($conf, 'log_level', (string) LOG_DEBUG, null, null);
Library::rd_kafka_conf_set($conf, 'debug', 'all', null, null);
// use queue for log events, this prevents segfaults
Library::rd_kafka_conf_set($conf, 'log.queue', 'true', null, null);
Library::rd_kafka_conf_set_log_cb(
    $conf,
    \Closure::fromCallable(
        function (CData $rdkafka, int $level, string $fac, string $buf): void {
            echo sprintf('  log: %s %s', $fac, $buf) . PHP_EOL;
        }
    )
);

$producer = Library::rd_kafka_new(RD_KAFKA_PRODUCER, $conf, null, null);
// use queue for log events, this prevents segfaults
Library::rd_kafka_set_log_queue($producer, null);
$topic = Library::rd_kafka_topic_new($producer, 'example', null);


for ($i = 0; $i < 1000; $i++) {
    $key = $i % 10;
    $payload = "payload-${i}-key-${key}";
    echo sprintf('produce msg: %s', $payload) . PHP_EOL;
    Library::rd_kafka_produce(
        $topic,
        RD_KAFKA_PARTITION_UA,
        0,
        $payload,
        strlen($payload),
        (string) $key,
        strlen((string) $key),
        null
    );
    $events = Library::rd_kafka_poll($producer, 1);
    echo sprintf('polling triggered %d events', $events) . PHP_EOL;
}
// flush
Library::rd_kafka_flush($producer, 1000);

// read metadata to extract broker list & topic partition ids for low level consuming
$metadata = Library::new('struct rd_kafka_metadata*');
Library::rd_kafka_metadata($producer, 1, null, FFI::addr($metadata), 10000);
//print_r($metadata);

$brokerList = [];
for ($i = 0; $i < $metadata->broker_cnt; $i++) {
    $brokerList[] = FFI::string($metadata->brokers[$i]->host) . ':' . $metadata->brokers[$i]->port;
}
//print_r($brokerList);
$partitionIds = [];
for ($i = 0; $i < $metadata->topics[0]->partition_cnt; $i++) {
    $partitionIds[] = $metadata->topics[0]->partitions[$i]->id;
}
//print_r($partitionIds);

// consume messages from each partition
$consumerConf = Library::rd_kafka_conf_new();
Library::rd_kafka_conf_set($consumerConf, 'metadata.broker.list', implode(',', $brokerList), null, null);
Library::rd_kafka_conf_set($consumerConf, 'log_level', (string) LOG_DEBUG, null, null);
Library::rd_kafka_conf_set($consumerConf, 'debug', 'all', null, null);
// use queue for log events, this prevents segfaults
Library::rd_kafka_conf_set($consumerConf, 'log.queue', 'true', null, null);
Library::rd_kafka_conf_set($consumerConf, 'enable.partition.eof', 'true', null, null);
Library::rd_kafka_conf_set($consumerConf, 'auto.offset.reset', 'earliest', null, null);
Library::rd_kafka_conf_set_log_cb(
    $consumerConf,
    \Closure::fromCallable(
        function (CData $rdkafka, int $level, string $fac, string $buf): void {
            echo sprintf('  log: %s %s', $fac, $buf) . PHP_EOL;
        }
    )
);

$consumer = Library::rd_kafka_new(RD_KAFKA_CONSUMER, $consumerConf, null, null);
// use queue for log events, this prevents segfaults
Library::rd_kafka_set_log_queue($consumer, null);
$consumerTopic = Library::rd_kafka_topic_new($consumer, 'example', null);
$queue = Library::rd_kafka_queue_new($consumer);

foreach ($partitionIds as $partitionId) {
    Library::rd_kafka_consume_start_queue($consumerTopic, $partitionId, 0, $queue);
}

$eofPartitions = [];
$consuming = true;
while ($consuming) {
    $messagePtr = Library::rd_kafka_consume_queue($queue, 100);
//    print_r($messagePtr);
    if ($messagePtr === null) {
        echo 'NONE' . PHP_EOL;
        continue;
    }
    $message = $messagePtr[0];
    // check msgs & eofs
    if ($message->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
        $eofPartitions[$message->partition] = true;
        echo 'consume msg: eof partition #' . $message->partition . PHP_EOL;
        if (count($eofPartitions) === count($partitionIds)) {
            $consuming = false;
        }
    } else {
        echo sprintf(
                'consume msg: %s, key: %s, partition: %d',
                FFI::string($message->payload, $message->len),
                FFI::string($message->key, $message->key_len),
                $message->partition
            ) . PHP_EOL;
    }
    // destroy before polling
    Library::rd_kafka_message_destroy($messagePtr);
    // trigger log callbacks
    $events = Library::rd_kafka_poll($consumer, 1);
    echo sprintf('polling triggered %d events', $events) . PHP_EOL;
}

foreach ($partitionIds as $partitionId) {
    Library::rd_kafka_consume_stop($consumerTopic, $partitionId);
}

// close consumer
Library::rd_kafka_destroy($consumer);

// close producer
Library::rd_kafka_destroy($producer);
