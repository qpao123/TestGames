<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
	</head>
	<body>
		<form action="{{url('user/dologin')}}" method="post">
			<table>
				<tr>
					<td>用户名</td>
					<td><input type="text" name="username" value=""/></td>
				</tr>
				<tr>
					<td>密码</td>
					<td><input type="password" name="password" value=""/></td>
				</tr>
				<tr>
					{{ csrf_field() }}
					<td colspan="2"><input type="submit" value="登陆"/></td>
				</tr>
			</table>
		</form>
		<div>
			<?php foreach($errors->all() as $error): ?>
				<?php echo $error ?>
			<?php endforeach;?>
		</div>
	</body>
</html>