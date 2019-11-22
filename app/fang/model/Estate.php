<?php
namespace app\fang\model;

use think\Model;
use think\facade\Db;

class Estate extends Model
{
    protected $connection = 'fang';
    protected $type = ['time' => 'timestamp:Y年m月d日'];
    
    public function house()
    {
        return $this->hasMany(EstateHouseMake::class, 'estate_id');
    }

    public static function test()
    {
        // $retData = self::alias('e')->leftJoin('estate_house_make h', 'e.id = h.estate_id')->field('e.name as estate_name, e.time as estate_time, e.*, h.*')->limit(10)->select()->toArray();

        $subSQL = EstateHouseMake::getMaxIdSql();
        dump($subSQL);
        $retData = self::alias('e')->leftJoin('estate_house_make h', 'e.id = h.estate_id')->field('e.name as estate_name, e.time as estate_time, e.*, h.*')->where('h.id in '.$subSQL)->limit(10)->select()->toArray();
        return $retData;
    }
    
    public static function getAll($cnt = 20)
    {
        // 查询全部数据
        $dbData = self::alias('e')->leftJoin('estate_item i', 'e.id = i.estate_id')->paginate($cnt);
        // dump($dbData);
        return $dbData;
        
        // 关联查询
        // $dbHouse = $dbData->house()->select();
        // dump($dbHouse);
        
        // 输出日期字段（自动时间戳转日期字符串）
        $retData = array();
        foreach ($dbData as $value) {
            array_push($retData, $value);
        }
        return $retData;
    }
    
    public static function getAllCount()
    {
        return self::count();
    }

    public static function getEstateHouse($estate_id=0, $cnt=20)
    {
        $retSql = self::alias('e')->join('estate_house_make h', 'e.id = h.estate_id')->field('e.name as estate_name, e.time as estate_time, e.*, h.*');
        if ($estate_id > 0) {
            $retSql = $retSql->where('h.estate_id = ' . $estate_id);
        }
        return $retSql->paginate($cnt);
    }

}