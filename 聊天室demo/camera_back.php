<?php
use workerman\Worker;
use workerman\Lib\Timer;
require_once './workerman/Autoloader.php';

// 创建一个Worker监听9966端口，使用websocket协议通讯
$ws_worker = new Worker("websocket://0.0.0.0:9966");

// 启动4个进程对外提供服务(因为不会再开一个监听端口，所以可以开多个进程，不存在端口监听重复问题)
$ws_worker->count = 4;

// 当收到canvas通过toDataURL发过来的数据后，返回给客户端
$ws_worker->onMessage = function($connection, $data)
{
	//函数里面不能调用外面的变量
	global $ws_worker;
	
	//$ws_worker->sendWorker 这个就是实际端口打开的连接资源，在onWorkerStart里面赋值的
	//$ws_worker->sendWorker->connections 是指所有连接实际端口的客户端(看视频的用户)
	// foreach($ws_worker->sendWorker->connections as $send_connection)
	// {
	// 	$send_connection->send($data);
	// }

	//我们的主播数据是通过9966端口传过来的，用户接收数据，我也用的9966端口。
	//但是，用户那端是没有传任何信息过来的，所以此$connection是主播端的连接句柄。
	//我们要发数据给用户端，但是没法区分哪一个$connection连接是主播端，所以全部传输
	//这样会重复浪费多传输了一个主播端的数据，而且我猜测用两个不同端口传，会减小压力，不建议用此方法
	foreach($ws_worker->connections as $con)
	{
		$con->send($data);
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
