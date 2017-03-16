<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\models\User;

use App\Contracts\OrderContract;
use App;
use PRedis;
use OrderClass;
use App\Services\LoupanService as Loupan;
use App\Jobs\SendReminderEmail;
use Event;
use App\Events\MessageEvent;

class TestController extends Controller
{
	public function __construct(OrderContract $order){
        $this->order = $order;
    }
	
	public function listUser() {
		//PRedis::set('one','zhangsan');
		//PRedis::set(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'),0,6) ,'wangwu');
		//$a = PRedis::get('one');
		//dd($a);
		//exit;

//		$a = Event::fire(new MessageEvent());
//		dd($a);
//		exit;
//
//
//		$user = User::find(3);
//		$this->dispatch(new SendReminderEmail($user));
//		exit;
//
//		$a = Loupan::getList(['hello','pengyou'])->get();
//		$a = Loupan::getOrder(['id'=>2,'username'=>'zhangsan','from'=>'zx'])->get();
//		var_dump($a);exit;
//
//
//		$order = App::make('order');
//		echo $order->fn('6666');
//		exit;
		$user_arr = User::selectUser();
		return view('user.list',['user_arr'=>$user_arr]);
	}
	
	public function add() {
		return view('user.add');
	}
	
	public function addHandle(Request $Request) {
		$aData['user_name'] = trim($Request->input('user_name'));
		$aData['age'] = (int)$Request->input('age');

		$oUserRes = User::createUser($aData);
		if ($oUserRes->id) {
			return redirect('user/list');
		} else {
			return redirect('user/list',['msg'=>'添加失败']);
		}
	}
	
	public function update($id) {
		$user_arr = User::find($id);
		return view('user.update',['user_arr'=>$user_arr]);
	}
	
	public function updateHandle(Request $Request) {
		$iId = (int)$Request->input('id');
		$aData['user_name'] = trim($Request->input('user_name'));
		$aData['age'] = (int)$Request->input('age');

		$bResult = User::updateUser($iId,$aData);
		if ($bResult) {
			return redirect('user/list');
		} else {
			return redirect('user/list',['msg'=>'修改失败']);
		}
	}
	
	public function del($id) {
		$bResult = User::deleteUser($id);
		if ($bResult) {
			return redirect('user/list');
		} else {
			return redirect('user/list',['msg'=>'删除失败']);
		}
	}

	public function login() {
		return view('user.login');
	}

	public function doLogin(Request $Request) {
		$username = $Request->input('username');
		$password = $Request->input('password');

		if($username != 'zx' || $password != '123456'){
			return back()->withErrors('用户名，密码不正确');
		}else{
			session(['username'=>'zx']);
			return redirect('user/list')->withErrors(['hello','pengyou']);
		}
	}
}
