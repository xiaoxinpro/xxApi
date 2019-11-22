<?php
namespace app\index\controller;

use app\BaseController;
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
        // dump($this->request);
        // dump(app_name());
        if (User::shellUser() === false) {
            return redirect(url('/index/index/login'));
        } else {
            return view();
        }
        // dump($this->request);
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V6 - Index<br/><span style="font-size:30px">13载初心不改 - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }
    
    public function login()
    {
        // dump($this->request);
        View::assign('message', false);
        if ($this->request->isPost()) {
            $username = input('post.username', 'null');
            $password = input('post.password', 'null');
            $user = User::login(trim($username), md5(trim($password)));
            if ($user['uid'] > 0) {
                return redirect(url());
            } else {
                View::assign('message', $user['msg']);
            }
        }
        View::assign('title', 'Login');
        return view();
    }

    public function logout()
    {
        User::logout();
        return redirect(url('/index/index/login'));
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
