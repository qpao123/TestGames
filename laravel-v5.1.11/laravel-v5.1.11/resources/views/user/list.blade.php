<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
	</head>
	<body>
		<a href="{{url('user/login')}}">登陆</a>
		<table>
			<?php foreach($user_arr as $v): ?>
			<tr>
				<td><?php echo $v['user_name'] ?></td>
				<td><?php echo $v['age'] ?></td>
				<td>
					<a href="{{url('user/update',['id'=>$v['id']])}}">修改</a>
					<a href="{{url('user/del',['id'=>$v['id']])}}">删除</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		<div>
			<a href="{{ url('user/add') }}">添加</a>
		</div>
		<div>
			<?php foreach($errors->all() as $error): ?>
				<?php echo $error ?>
			<?php endforeach;?>
		</div>
	</body>
</html>