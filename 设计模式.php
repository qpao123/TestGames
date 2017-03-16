<?php

	/*
	1.工厂模式
	  用工厂方法创建类，而不直接用new来创建。
	  好处就是，如果项目中多处直接用new来创建类，
	  类名改变了之后，就需要修改多处地方。
	  而用工厂方式，就修改工厂方法一个地方即可。
	*/

	 class Factory 
	 {
	 	static function createObj($class)
	 	{
	 		return new $class;
	 	}
	 }

	 //Factory::createObj(user); 


	/*
	2.单列模式
	  多次实列化一个类只创建一个对象，节约了空间。
	*/

	 class Sign
	 {
	 	static protected $obj = null;

	 	private function __construct()
	 	{

	 	}

	 	static function createSign()
	 	{
	 		if (self::$obj == null) {
	 			self::$obj = new self();
	 		}

	 		return self::$obj;
	 	}

	 	public function __destruct()
	 	{
	 		echo '###################<br/>';
	 	}

	 } 

	// $a = Sign::createSign();
	// $b = Sign::createSign();

	/*
	3.注册树模式
	  将一个对象注册到注册树中，这样可以直接在全局中使用。
	  而不用，每次都创建调用。
	*/

 	class register
  	{
  		protected $arr_obj = [];

	  	function _set($key,$obj)
	  	{
	  		$this->arr_obj[$key] = $obj;
	  	}

	  	function _unset($key)
	  	{
	  		unset($this->arr_obj[$key]);
	  	}

	  	function _get($key)
	  	{
	  		return $this->arr_obj[$key];
	  	}
  	}

  	class test
  	{
  		public $name = 'vivi';
  	}

  	$register = new register();
  	$register->_set('one',new test());
  	$a = $register->_get('one');
  	//echo $a->name;

  	/*
	4.适配器模式
	  创建一个接口，每个子类都继承这个接口。
	  这样使用任何一个子类，都不需要修改业务代码。
	  比如，mysql mysqli pdo
	*/

	interface database
	{
		const author = 'zhengxiang';

		function connect($host,$name,$passwd,$database_name);
		
		function query($sql);
		
		function close();
	}  

	/*
     *接口中权限，必须是public，公有的
     *接口中属性必须是常量
     *接口中方法都是抽象方法
     *接口和抽象类，都不能直接创建对象
	 */
	class mysql implements database
	{	
		protected $link;

		public function connect($host,$name,$passwd,$database_name)
		{
			$this->link = mysql_connect($host,$name,$passwd);
			mysql_select_db($database_name,$this->link);
		}

		public function query($sql)
		{
			$res = mysql_query($sql);
			return mysql_fetch_assoc($res);
		}

		public function close()
		{
			mysql_close($this->link);
		}
	}

	//不能用mysqli，系统有这个类名
	class mysqli_zx implements database
	{
		protected $link;

		public function connect($host,$name,$passwd,$database_name)
		{
			$this->link = mysqli_connect($host,$name,$passwd,$database_name);
		}

		public function query($sql)
		{
			$res = mysqli_query($this->link,$sql);
			return mysql_fetch_assoc($res);
		}

		public function close()
		{
			mysqli_close($this->link);
		}
	}

	// $db = new mysqli_zx();
	// $db->connect('localhost','root','','zx');
	// $sql = "SELECT * FROM user";
	// $res = $db->query($sql);
	// var_dump($res);


    /*
	5.策略模式
	  将一组特定的行为和算法封装成类，以适应某些特定的上下文
	  如：男性用户，和女性用户，进入通一个页面，展示不同的效果
	*/

	//显示广告、分类
	interface show
	{
		function showAd();

		function showCategory();
	}  

	class boyUser implements show
	{
		function showAd()
		{
			echo '显示 美女<br/>';
		}

		function showCategory()
		{
			echo '显示 汽车分类<br/>';
		}
	}

	class girlUser implements show
	{
		function showAd()
		{
			echo '显示 帅哥<br/>';
		}

		function showCategory()
		{
			echo '显示 香水分类<br/>';
		}
	}

	//首页显示类
	class page
	{
		protected $user;

		function index()
		{
			$this->user->showAd();
			$this->user->showCategory();
		}

		function setUser(show $user)
		{
			$this->user = $user;
		}
	}

	$page = new page();

	//不同的用户，传递不同对象
	//$_GET['user'] = 'boy';
	if ($_GET['user'] == 'boy') 
	{
		$page->setUser(new boyUser());
	}
	else
	{
		$page->setUser(new girlUser());
	}

	$page->index();

	/*
	6.数据对象映射模式
	  直接通过方法来保存对象，隐藏sql操作
	*/

	 class user 
	 {
	 	public $field;
	 	private $db;

	 	public function __construct($id)
	 	{
 			$this->db = new mysql();
			$this->db->connect('localhost','root','','zx');
			$sql = "SELECT * FROM user WHERE id = '$id'";
			$this->field = $this->db->query($sql);
	 	}

	 	public function save($id)
	 	{
	 		$str = '';
	 		foreach ($this->field as $k=>$v) {
	 			$str .= $k."='$v',";
	 		}
	 		$str = rtrim($str,',');

	 		$sql = "UPDATE user SET $str WHERE id='$id'";
	 		$this->db->query($sql);
	 	}
	 }

	 $u = new user(1);
	 $u->field['age'] = '88';
	 $u->save(1);

	/*
	7.观察者模式
	  当一个对象状态发生改变时，依赖它的对象全部收到通知，并自动更新
	*/

	//触发事件的基类
	class eventBasic
	{
		//所有的关注此事件的人
		protected $arr = [];

		//添加观察者
		public function addObserver($obj)
		{
			$this->arr[] = $obj;
		}

		//发送通知给观察者
		public function notice()
		{
			foreach ($this->arr as $v) {
				$v->update();
			}
		}
	}

	//触发事件的类
	class trigger extends eventBasic 
	{
		public function event()
		{
			echo 'event 触发<br/>';
			$this->notice();
		}
	}

	//观察者接口
	interface observer
	{
		function update();
	} 

	//第一个观察者
	class onePeople implements observer
	{
		public function update()
		{
			echo '我是第一个观察者<br/>';
		}
	}

	//第二个观察者
	class twoPeople implements observer
	{
		public function update()
		{
			echo '我是第二个观察者<br/>';
		}
	}

	$t = new trigger();
	$t->addObserver(new onePeople());
	$t->addObserver(new twoPeople());
	$t->event();





























































