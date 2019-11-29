<?php
namespace app\fang\model;

use think\Model;
use think\facade\Db;

class Estate extends Model
{
    protected $connection = 'fang';
    protected $type = ['time' => 'timestamp:Y年m月d日'];

    // 测试函数
    public static function test()
    {
        // $retData = self::alias('e')->leftJoin('estate_house_make h', 'e.id = h.estate_id')->field('e.name as estate_name, e.time as estate_time, e.*, h.*')->limit(10)->select()->toArray();

        // $subSQL = EstateHouseMake::getMaxIdSql();
        // dump($subSQL);
        // $retData = self::alias('e')->leftJoin('estate_house_make h', 'e.id = h.estate_id')->field('e.name as estate_name, e.time as estate_time, e.*, h.*')->where('h.id in '.$subSQL)->limit(10)->select()->toArray();

        $retData = self::field('district')->distinct(true)->select()->toArray();
        $retData = self::field('comarea')->distinct(true)->where('district', '普陀')->select()->toArray();
        return $retData;
    }
    
    // 获取全部小区数据（分页）
    public static function getAll($cnt = 20)
    {
        // 查询全部数据
        $dbData = self::alias('e')->leftJoin('estate_item i', 'e.id = i.estate_id')->paginate($cnt);
        // dump($dbData);
        return $dbData;
    }
    
    // 获取小区总数
    public static function getAllCount()
    {
        return self::count();
    }

    // 获取小区及其交易数据（指定ID，分页）
    public static function getEstateHouse($estate_id=0, $cnt=20)
    {
        $estate_id = intval($estate_id);
        $retSql = self::alias('e')->join('estate_house_make h', 'e.id = h.estate_id')->field('e.name as estate_name, e.time as estate_time, e.*, h.*');
        if ($estate_id > 0) {
            $retSql = $retSql->where('h.estate_id = ' . $estate_id);
        }
        return $retSql->paginate($cnt);
    }

    // 获取指定ID的小区数据（指定ID）
    public static function getEstateData($estate_id)
    {
        $estate_id = intval($estate_id);
        if ($estate_id > 0) {
            return self::alias('e')->join('estate_item i', 'e.id = i.estate_id')->join('estate_rate r', 'e.id = r.estate_id')->where('e.id', $estate_id)->find();
        }
        return null;
    }

    // 获取位置列表（指定区）
    public static function getLocationList($district=null)
    {
        $retData = array();
        if (is_null($district)) {
            $retData = self::field('district')->distinct(true)->select()->toArray();
        } else {
            $retData = self::field('comarea')->distinct(true)->where('district', $district)->select()->toArray();
        }
        return $retData;
    }

}