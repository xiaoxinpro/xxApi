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

    public static function getAll($estate_id=0, $cnt=20)
    {
        $retSql = self::order('date', 'desc');
        $estate_id = intval($estate_id);
        if ($estate_id > 0) {
            $retSql = $retSql->where('estate_id = '.$estate_id);
        }
        return $retSql->paginate($cnt);
    }
}