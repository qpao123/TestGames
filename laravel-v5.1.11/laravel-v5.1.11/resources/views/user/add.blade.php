<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
	</head>
	<body>
		<form action="{{ url('user/addHandle') }}" method="post">
			<table>
				<tr>
					<td>用户名</td>
					<td><input type="text" name="user_name" value=""/></td>
				</tr>
				<tr>
					<td>年龄</td>
					<td><input type="text" name="age" value=""/></td>
				</tr>
				<tr>
					<td><?php echo csrf_field(); ?></td>
					<td><input type="submit" value="添加"/></td>
				</tr>
			</table>
		</form>
	</body>
</html>