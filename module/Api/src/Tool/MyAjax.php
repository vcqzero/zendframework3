<?php
namespace Api\Tool;

class MyAjax
{
    const KEY_SUCCESS = 'success';
    const KEY_MSG     = 'msg';
    /**
    * 关闭ajax连接，可执行其他后台程序
    * 
    * @param  
    * @return        
    */
    public static function close()
    {
        // get the size of the output
        $size = ob_get_length();
        // send headers to tell the browser to close the connection
        header("Content-Length: $size");
        header('Connection: close');
        ob_end_flush();
        ob_flush();
        flush();
        
        /******** background process starts here ********/
        ignore_user_abort(true);//在关闭连接后，继续运行php脚本
        /******** background process ********/
        set_time_limit(0); //no time limit，不设置超时时间（根据实际情况使用）
    }
}