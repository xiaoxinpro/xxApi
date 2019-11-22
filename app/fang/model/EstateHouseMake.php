<?php
namespace app\fang\model;

use think\Model;

class EstateHouseMake extends Model
{
	protected $connection = 'fang';
	protected $type = ['date' => 'timestamp:Y年m月d日'];

	public static function getMaxIdSql()
	{
		return self::field('MAX(id)')->group('estate_id')->buildSql();
	}
}