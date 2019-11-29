<?php
namespace app\fang\controller;

use app\BaseController;
use app\fang\model\Estate;
use app\index\model\User;

use think\Request;

use think\facade\View;

class Index extends BaseController
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
            View::assign('username', $user['username']);
            $list = Estate::getAll();
            View::assign('list',$list);
            View::assign('page',$list->render());
            // dump(session('user'));
        }
        // dump(root_path().'app/fang/Fang2.db');
        // dump(Estate::getAll());
        // dump('数据库中包含'.Estate::getAllCount().'个小区数据。');
        return view();
    }

    public function apiLocation()
    {
        $retData = array();
        if ($this->request->isGet()) {
            $retData = Estate::getLocationList();
        } else if ($this->request->isPost()) {
            $district = input('post.district', null);
            $retData = Estate::getLocationList($district);
        }
        return json($retData);
    }

    public function test()
    {
        dump(Estate::test());
    }
}
