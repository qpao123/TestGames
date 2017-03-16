<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
	</head>
	<body>
		<form action="{{ url('user/updateHandle') }}" method="post">
			<table>
				<tr>
					<td>用户名</td>
					<td><input type="text" name="user_name" value="<?php echo $user_arr['user_name'] ?>"/></td>
				</tr>
				<tr>
					<td>年龄</td>
					<td><input type="text" name="age" value="<?php echo $user_arr['age'] ?>"/></td>
				</tr>
				<tr>
					<td><input type="hidden" name="id" value="<?php echo $user_arr['id'] ?>"/></td>
					<td><?php echo csrf_field(); ?><input type="submit" value="修改"/></td>
				</tr>
			</table>
		</form>
	</body>
</html>