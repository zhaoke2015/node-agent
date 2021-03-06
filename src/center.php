<?php
require_once __DIR__ . '/_init.php';

//是一个96字节的文件
$encrypt_key = file_get_contents(__DIR__ . '/encrypt.key');
$svr = new NodeAgent\Center($encrypt_key);
//设置上传文件的存储目录
$svr->setRootPath(['/data']);
$svr->setScriptPath('/data/script');
//设置允许上传的文件最大尺寸
$svr->setMaxSize(100 * 1024 * 1024);
$svr->run("0.0.0.0", NodeAgent\Center::PORT_TCP);
