<?php
use workerman\Worker;
use workerman\Lib\Timer;
require_once './workerman/Autoloader.php';

// 创建一个Worker监听2388端口，使用websocket协议通讯
$ws_worker = new Worker("websocket://0.0.0.0:2388");

// 启动4个进程对外提供服务
$ws_worker->count = 4;

// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function($connection, $data)
{
    // 向客户端发送hello $data
    //$connection->send('hello ' . $data);

	//根据客户端传来的不同信息，返回
	global $ws_worker;
	$data = json_decode($data,true);

	switch($data['type'])
	{
		case 'login':
			//登录时候把昵称放入session
			$_SESSION['client_name'] = $data['name'];
			//把session放入客户端连接对象
			$connection->session = $_SESSION;

			//获取在线所有用户(这个$id,使用的是$connection->id,在多进程时候,可能会重复，最好在外面声明一个全局常量，里面自增)
			foreach($ws_worker->connections as $id=>$con)
			{
				$user_list[$id]=$con->session['client_name'];
			}
			//给当前登录客户端发送所有用户列表
			$msg = ['type'=>$data['type'],'name'=>$data['name'],'user_list'=>$user_list];
			$connection->send(json_encode($msg));

			//给除了当前登录客户端发送登录的用户信息
			foreach($ws_worker->connections as $id=>$con)
			{
				if($con->session['client_name'] == $connection->session['client_name'])
				{	
					$client_id = $id;
				}
			}
			$new_user_list[$client_id] = $data['name'];
			$msg = ['type'=>$data['type'],'name'=>$data['name'],'new_user_list'=>$new_user_list];


			foreach($ws_worker->connections as $id=>$con)
			{
				if($con->session['client_name'] != $connection->session['client_name'])
				{
					$con->send(json_encode($msg));	
				}
			}
			return;
			break;
		case 'say':
			//如果是对单个用户私聊
			if(!empty($data['to_client_id']))
			{
				$msg = ['type'=>$data['type'],'name'=>$data['name'],'content'=>$data['content'],'to_client_name'=>$data['to_client_name']];
				
				//发私聊的用户
				$ws_worker->connections[$data['to_client_id']]->send(json_encode($msg));
				//发给当前客户端(自己)
				$connection->send(json_encode($msg));
				return;
			}
			
			//发给所有人
			$msg = ['type'=>$data['type'],'name'=>$data['name'],'content'=>$data['content']];
			foreach($ws_worker->connections as $id=>$con)
			{
				$con->send(json_encode($msg));
			}
			return;
			break;	
	}
};

//当客户端断开连接
$ws_worker->onClose = function($connection)
{
	global $ws_worker;
	$msg = ['type'=>'logout','name'=>$connection->session['client_name']];

	//此时的所有客户端连接，已经没有了断开的客户端
	foreach($ws_worker->connections as $id=>$con)
	{
		$con->send(json_encode($msg));
	}
	return;
};

// 运行worker
Worker::runAll();
