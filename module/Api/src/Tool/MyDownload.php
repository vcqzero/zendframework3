<?php
namespace Api\Tool;

class MyDownload
{
    /**
    * 下载文件
    * 
    * @param  string $file_name 文件名称，注意必须是文件全路径 
    * @param  string $basename  文件显示的名称
    * @return void       
    */
    public static function download($file_name, $basename = null) {
        //首先判断文件是否存在
        if(!file_exists($file_name)) {
            echo "文件不存在";
            return;
        }
        //以只读的方式打开文件
        $fp=fopen($file_name,"r");
        $file_size=filesize($file_name); //文件大小
        
        //获取文件的基本名称，供下载时显示
        if(empty($basename)) {
            $basename = basename($file_name);
        }else {
            //获取扩展名
            $ext = strrchr($file_name,'.');
            $basename = $basename . $ext;
        }
        
        //以下为下载文件所需的http文件头
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Content-Length:". $file_size);
        header("Content-Disposition: attachment;filename=".$basename);
        //向客户端回送数据
        $buffer=1024; //每次读1024字节
        //为了下载的安全，我们做一个文件字节读取计数器
        $file_count=0;
        while(!feof($fp) && ($file_size - $file_count>0)){ //判断文件是否结束
            $file_data = fread($fp,$buffer);
            //统计读了多少字节
            $file_count += $buffer;
            //把部分数据回送给游览器
            echo $file_data;
        }
        fclose($fp);
    }
}