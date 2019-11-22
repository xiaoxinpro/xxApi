<?php
namespace app\index\model;

use think\Model;

class User extends Model
{
    protected $connection = 'mysql';
    protected $autoWriteTimestamp = true;

    // 验证登录状态
    public static function shellUser() 
    {
        $user = session('user');
        if ($user && array_key_exists('time', $user) && array_key_exists('shell', $user) && array_key_exists('uid', $user)) {
            if (time() - $user['time'] > 7200) {
                self::logout();
                return false;
            }
            else if ($user['shell'] === self::getShell($user['uid'], $user['time'])) {
                return true;
            }
            self::logout();
            return false;
        } else {
            self::logout();
            return false;
        }
    }

    // 登录用户名
    public static function login($username, $passworld_md5)
    {
        $ret = array();
        $time = time();
        $user = self::where(array('username' => trim($username), 'password' => trim($passworld_md5)))->findOrEmpty();
        if (!$user->isEmpty()) {
            $ret['uid'] = $user->uid;
            $ret['username'] = $username;
            $ret['icon'] = $user->icon;
            $ret['time'] = $time;
            $ret['shell'] = self::getShell($ret['uid'], $time, $passworld_md5);
            $ret['msg'] = '登录成功。';
            session('user',$ret);
        } else {
            $ret['uid'] = 0;
            $ret['time'] = $time;
            $ret['msg'] = '用户名或密码错误。';
            self::logout();
        }
        return $ret;
    }

    // 退出登录
    public static function logout()
    {
        session('user', null);
        self::clearCache();
    }

    // 清除数据库缓存
    public static function clearCache()
    {
        cache('user_password', null);
    }

    // 获取验证登录码
    private static function getShell($uid, $time, $passworld_md5 = false)
    {
        if ($passworld_md5 === false) {
            $passworld_md5 = self::where('uid', $uid)->cache('user_password')->value('password');
        }
        $shell = $uid.'|'.md5($uid.'|'.$passworld_md5.'|'.$time).'|'.$time;
        return $shell;
    }
}
