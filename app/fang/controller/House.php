<?php
namespace app\fang\controller;

use app\BaseController;
use app\fang\model\Estate;
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
            return redirect(url('/index/index/login'));
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
            $list = Estate::getEstateHouse(input('get.estate_id', $estate_id));
            View::assign('list',$list);
            View::assign('page',$list->render());
        }
        return view();
    }
}
