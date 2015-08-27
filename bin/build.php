#!/usr/local/bin/php
<?php
define('WEBPATH', dirname(__DIR__) . '/src');
require_once '/data/www/public/framework/libs/lib_config.php';
if (empty($argv[1]))
{
    $dst = 'node';
}
else
{
    $dst = trim($argv[1]);
}

if ($dst == 'node')
{
    $phar = new \Phar(__DIR__ . '/node-agent.phar');
    $phar->buildFromDirectory(WEBPATH, '/\.php$/');
    $phar->addFile(WEBPATH . '/encrypt.key', 'encrypt.key');
    $phar->compressFiles(\Phar::GZ);
    $phar->stopBuffering();
    $phar->setStub($phar->createDefaultStub('node.php'));
}
elseif ($dst == 'center')
{
    $phar = new \Phar(__DIR__ . '/node-center.phar');
    $phar->buildFromDirectory(WEBPATH, '/\.php$/');
    $phar->addFile(WEBPATH . '/encrypt.key', 'encrypt.key');
    $phar->compressFiles(\Phar::GZ);
    $phar->stopBuffering();
    $phar->setStub($phar->createDefaultStub('center.php'));
}
elseif($dst == 'key')
{
    $encrypt_key = md5(uniqid('encrypt'));
    echo $encrypt_key;
}