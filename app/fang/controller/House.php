<?php
namespace app\fang\controller;

use app\BaseController;
use app\fang\model\Estate;
use app\fang\model\EstateHouseType;
use app\index\model\User;

use think\Request;

use think\facade\View;

class House extends BaseController
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        if (User::shellUser() === false) {
            return redirect(url('/index/index/login'))  ;
        } else {
            $user = session('user');
            $estate_id = input('get.estate_id', null);
            if ($estate_id === null) {
                $estate_id = intval(session('fang_house_estate_id'));
            } else if (intval($estate_id) === 0) {
                $estate_id = 0;
            } else {
                $estate_id = intval($estate_id);
                session('fang_house_estate_id', $estate_id);
            }
            View::assign('username', $user['username']);
            View::assign('estate_id',$estate_id);
            $list = Estate::getEstateHouse(input('get.estate_id', $estate_id));
            View::assign('list',$list);
            View::assign('page',$list->render());
            if ($estate_id > 0) {
                // dump(EstateHouseType::getAll($estate_id));
                View::assign('listHouseType', EstateHouseType::getAll($estate_id));
                View::assign('estate', Estate::getEstateData($estate_id));
                View::assign('row', array(
                    'name' => '小区名称',
                    'type' => '小区类型',
                    'build_year' => '建筑年份',
                    'build_type' => '建筑类型',
                    'build_count' => '楼栋数量',
                    'house_count' => '房屋数量',
                    'sell' => '在售数量',
                    'rent' => '在租数量',
                    'price' => '平均单价',
                    'ratio' => '价格涨幅',
                    'developers' => '物业公司',
                    'url' => '更多信息',
                ));
            }
        }
        return view();
    }
}
