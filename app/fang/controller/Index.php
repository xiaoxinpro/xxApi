<?php
namespace app\fang\controller;

use app\BaseController;
use app\fang\model\Estate;
use app\index\model\User;

use think\Request;

use think\facade\View;

class Index extends BaseController
{
    // 构造函数
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // 主页控制器
    public function index()
    {
        if (User::shellUser() === false) {
            return redirect(url('/index/index/login'));
        } else {
            $user = session('user');
            View::assign('username', $user['username']);
            $condition = array();
            if ($this->request->isPost()) {
                $condition = $this->_formDataProcess(input('post.'));
            }
            $list = Estate::getAll($condition);
            View::assign('list',$list);
            View::assign('page',$list->render());     
        }
        return view();
    }

    // 获取数据库中地理位置列表API
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

    // 测试控制器
    public function test()
    {
        dump(Estate::test());
    }

    // 表单数据处理
    private function _formDataProcess($formData=null)
    {
        $ret = array();
        if ($formData && is_array($formData)) {
            // 小区名称筛选
            if (array_key_exists('sxEstateName', $formData) && strlen(trim($formData['sxEstateName'])) > 0) {
                array_push($ret, ['name','like',"%".trim($formData['sxEstateName'])."%"]);
            }
            // 区域筛选
            if (array_key_exists('sxLocationDistrict', $formData) && $formData['sxLocationDistrict'] != '不限') {
                array_push($ret, ['district', '=', $formData['sxLocationDistrict']]);
                // 乡镇筛选
                if (array_key_exists('sxLocationComarea', $formData) && $formData['sxLocationComarea'] != '不限') {
                    array_push($ret, ['comarea', '=', $formData['sxLocationComarea']]);
                }
            }
            // 建筑年限筛选
            if (array_key_exists('sxBuildYearSelect', $formData)) {
                switch ($formData['sxBuildYearSelect']) {
                    case '大于':
                        array_push($ret, ['build_year', '>=', intval($formData['sxBuildYearStart'])]);
                        break;
                    case '小于':
                        array_push($ret, ['build_year', '<=', intval($formData['sxBuildYearStart'])]);
                        break;
                    case '范围':
                        $arr = array(intval($formData['sxBuildYearStart']), intval($formData['sxBuildYearEnd']));
                        array_push($ret, ['build_year', '>=', min($arr)]);
                        array_push($ret, ['build_year', '<=', max($arr)]);
                        # code...
                        break;
                    case '不限':
                    default:
                        # code...
                        break;
                }
            }
            // 平均价格筛选
            if (array_key_exists('sxAvgPriceSelect', $formData)) {
                switch ($formData['sxAvgPriceSelect']) {
                    case '大于':
                        array_push($ret, ['price', '>=', intval($formData['sxAvgPriceStart'])]);
                        break;
                    case '小于':
                        array_push($ret, ['price', '<=', intval($formData['sxAvgPriceStart'])]);
                        break;
                    case '范围':
                        $arr = array(intval($formData['sxAvgPriceStart']), intval($formData['sxAvgPriceEnd']));
                        array_push($ret, ['price', '>=', min($arr)]);
                        array_push($ret, ['price', '<=', max($arr)]);
                        # code...
                        break;
                    case '不限':
                    default:
                        # code...
                        break;
                }
            }
        }
        return $ret;
    }
}
