<?php
namespace app\fang\model;

use think\Model;

class EstateItem extends Model
{
    protected $connection = 'fang';

    public static function getMaxIdSql()
    {
        return self::field('MAX(id)')->group('estate_id')->buildSql();
    }

    public static function getData($estate_id)
    {
        $estate_id = intval($estate_id);
        $retSql = self::order('id', 'desc');
        if ($estate_id > 0) {
            $retSql = $retSql->where('estate_id = '.$estate_id);
        }
        return $retSql->find();
    }
}