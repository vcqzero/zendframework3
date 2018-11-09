<?php
namespace Api\Tool;

class MyCurl
{

    public static function post($data, $url)
    {
        // 初始化
        $curl = curl_init();
        // 设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置头文件的信息,false=不输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        // 设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        // 设置post参数
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // 设置不验证证书（这个参数一般不需要设置）
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($curl); // 执行命令
        $res = empty(curl_error($curl)) ? json_decode($json, true) : [];
        curl_close($curl); // 关闭URL请求
        return $res;
    }

    /**
     * get the res from method of get
     *
     * @param string $url            
     * @param array $data            
     * @return array or []
     */
    public static function get($url, $data = null, $httpheaders=null)
    {
        if (! empty($data)) {
            $url = $this->splitUrl($url, $data);
        }
        // 初始化
        $curl = curl_init();
        // 设置抓取的url
        if (!empty($httpheaders))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheaders);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        // 设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 设置不验证证书（这个参数一般不需要设置）
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // 执行命令
        $json = curl_exec($curl);
        // 关闭URL请求
        $res = empty(curl_error($curl)) ? json_decode($json, true) : [];
        curl_close($curl);
        return $res;
    }

    /**
     *
     * @param string $oriurl
     *            array $data
     * @return string $url
     */
    public function splitUrl($oriUrl, array $data)
    {
        $array = [];
        foreach ($data as $key => $val) {
            $array[$key] = $key . '=' . $val;
        }
        $string = implode('&', $array);
        $url = $oriUrl . '?' . $string;
        return $url;
    }
}

