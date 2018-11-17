<?php
namespace Api\Tool;

class MyAjax
{
    const SUBMIT_SUCCESS = 'success';
    const SUBMIT_MSG     = 'msg';
    
    /**
    * 返回ajax，并可在返回之后执行后台程序
    * 
    * @param bool $success  
    * @param string $message  
    * @return        
    */
    public function close($success, $msg = null)
    {
        $responce = [
            self::AJAX_RESULT_SUCCESS => $success === true,
            self::AJAX_RESULT_MESSAGE => $msg,
        ];
        echo json_encode($responce);
        
        // get the size of the output
        $size = ob_get_length();
        // send headers to tell the browser to close the connection
        header("Content-Length: $size");
        header('Connection: close');
        ob_flush();
        flush();
        ob_end_flush();
        /******** background process starts here ********/
        ignore_user_abort(true);//在关闭连接后，继续运行php脚本
        /******** background process ********/
        set_time_limit(0); //no time limit，不设置超时时间（根据实际情况使用）
    }
}