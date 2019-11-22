<?php
// 应用公共文件

// URL生成函数（模块地址，入口php文件名）
function url($info='', $index=false)
{
    $url = '';
    if ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) {
        $url = ($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER["SCRIPT_NAME"]);
    }
    else {
        $url = ($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"]);
    }
    if ($index !== false) {
        $url = preg_replace("/\/\w+.php/", '/'.$index, $url);
    }
    return $url.$info;
}

// 字符串替换，专用于View中。
function replace($str, $old, $new = '')
{
    return str_replace($old, $new, $str);
}

// 获取应用名称
function app_name()
{
    return str_replace([base_path(), '/'], '', app_path());
}