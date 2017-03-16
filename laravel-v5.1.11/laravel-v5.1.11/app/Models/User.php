<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mockery\CountValidator\Exception;

class User extends Model
{
    public $table = 'user';
	public $primaryKey = 'id';
	public $fillable = ['user_name','age','email'];
	public $timestamps = false;

	/**
	 * 新增用户
	 */
	public static function createUser(array $aData)
	{
		$aResult = self::where('user_name',$aData['user_name'])->get()->toArray();
		if ($aResult) {
			exit('用户名存在');
		}
		return self::create($aData);
	}

	/**
	 * 删除用户
	 */
	public static function deleteUser($iId)
	{
//		$oUser = self::find($iId);
//		return $oUser->delete();
		return self::destroy($iId);
	}

	/**
	 * 修改用户
	 */
	public static function updateUser($iId,$aData)
	{
		if (!$oUser = self::find($iId)) {
			exit('用户不存在');
		}
		return $oUser->update($aData);
	}

	/**
	 * 查询用户
	 */

	public static function selectUser(array $aWhere = [], array $aColumns = ['*'], array $aOrder = [])
	{
		$oUser = new static;
		foreach ($aWhere as $sKey => $mValue) {
			$oUser = $oUser->where($sKey, $mValue);
		}
		foreach ($aOrder as $sKey => $mValue) {
			$oUser = $oUser->orderBy($sKey, $mValue);
		}
		return $oUser->select($aColumns)->get()->toArray();
	}
	
}
