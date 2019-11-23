<?php
namespace app\fang\model;

use think\Model;

class EstateHouseType extends Model
{
    protected $connection = 'fang';

    public static function getAll($estate_id, $cnt=20)
    {
        $estate_id = intval($estate_id);
        if (intval($estate_id) > 0) {
            return self::where('estate_id = '.$estate_id)->paginate($cnt);
        } else {
            return null;
        }
    }

}