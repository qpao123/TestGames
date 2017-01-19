<?php
use workerman\Worker;
use workerman\Lib\Timer;
require_once './workerman/Autoloader.php';

// 创建一个Worker监听9966端口，使用websocket协议通讯
$ws_worker = new Worker("websocket://0.0.0.0:9966");

// 启动1个进程对外提供服务(worker启动时候，要另开一个worker监听新端口，如果多个进程会导致监听端口重复)
$ws_worker->count = 1;

//Worker启动时候的回调函数
$ws_worker->onWorkerStart = function($ws_worker)
{
	//实际客户端(用户)接受视频的端口
	$send_worker = new Worker('Websocket://0.0.0.0:6699');

	//把实际端口通过属性赋值给ws_worker
	$ws_worker->sendWorker = $send_worker;

	//让实际端口开始监听
	$send_worker->listen();
};

// 当收到canvas通过toDataURL发过来的数据后，返回给实际端口6699连接的客户端
$ws_worker->onMessage = function($connection, $data)
{
	//函数里面不能调用外面的变量
	global $ws_worker;
	
	//$ws_worker->sendWorker 这个就是实际端口打开的连接资源，在onWorkerStart里面赋值的
	//$ws_worker->sendWorker->connections 是指所有连接实际端口的客户端(看视频的用户)
	foreach($ws_worker->sendWorker->connections as $send_connection)
	{
		$send_connection->send($data);
	}
};

//当客户端断开连接
$ws_worker->onClose = function($connection)
{
	echo "connection closed\n";
	return;
};

// 运行worker(里面包含了打开监听listen方法)
Worker::runAll();
