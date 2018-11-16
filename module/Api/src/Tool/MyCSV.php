<?php
namespace Api\Tool;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class MyCSV
{
    /**
     * 读取CSV文件
     * @param string $csv_file csv文件路径
     * @param int $lines       读取行数
     * @param int $offset      起始行数
     * @return array|bool
     */
    public static function read_csv_lines($csv_file = '', $lines = 0, $offset = 0)
    {
        if (!$fp = fopen($csv_file, 'r')) {
            return false;
        }
        $i = $j = 0;
        while (false !== ($line = fgets($fp))) {
            if ($i++ < $offset) {
                continue;
            }
            break;
        }
        $data = array();
        while (($j++ < $lines) && !feof($fp)) {
            $data[] = fgetcsv($fp);
        }
        fclose($fp);
        return $data;
    }
    
    /**
     * 导出CSV文件
     * 
     * @param string $file_name  文件名称，必须加上.csv
     * @param array  $file_desc  文件说明  二维数组，可设置为[], 所有数组中键值无效 [['文件名称', '设置文件名称']]
     * @param array $head_title 表头文件 
     * @param array $data  
     * @return void
     */
    public static function export_csv($file_name, $file_desc, $head_title, $data)
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$file_name);
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a');
        
        //设置文件描述
        if (!empty($file_desc)) {
            foreach ($file_desc as $key=>$desc) {
                foreach ($desc as $key => $value) {
                    $desc[$key] = iconv('utf-8', 'gbk', $value);
                }
                fputcsv($fp, $desc);
            }
            fputcsv($fp, []);
        }
        
        //设置表头
        if (!empty($head_title)) {
            foreach ($head_title as $key => $value) {
                $head_title[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $head_title);
        }
        
        //设置文件数据
        $num = 0;
        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        //逐行取出数据，不浪费内存
        $count = count($data);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $num++;
                //刷新一下输出buffer，防止由于数据过多造成问题
                if ($limit == $num) {
                    ob_flush();
                    flush();
                    $num = 0;
                }
                $row = $data[$i];
                foreach ($row as $key => $value) {
                    $row[$key] = iconv('utf-8', 'gbk', $value);
                }
                fputcsv($fp, $row);
            }
        }
        fclose($fp);
    }
}
