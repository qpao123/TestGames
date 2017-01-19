<?php
use workerman\Worker;
use workerman\Lib\Timer;
require_once './workerman/Autoloader.php';

// 创建一个Worker监听2347端口，不使用任何应用层协议
$tcp_worker = new Worker("tcp://127.0.0.1:2347");

// 启动4个进程对外提供服务
$tcp_worker->count = 4;

// 定时向客户端发送广播
$tcp_worker->onWorkerStart = function($worker)
{
	Timer::add(10,function()use($worker)
	{
		foreach($worker->connections as $connection)
		{
			$connection->send('nihao,ha ha ha !!!');
		}
	});
};

// 当客户端发来数据时
$tcp_worker->onMessage = function($connection, $data)
{
    // 向客户端发送hello $data
    $connection->send('Hello:'.$data);
};

// 运行worker
Worker::runAll();