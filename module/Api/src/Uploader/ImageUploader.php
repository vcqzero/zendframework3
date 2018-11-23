<?php
namespace Api\Uploader;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;
use Zend\Validator\File\IsImage;
/**
* @desc 数据表验证
*/
class ImageUploader
{
    private $name;
    private $error;
    private $values;
    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
    * 获取图片上传器
    * 
    * @param int $size 限制图片大小 
    * @param string $target 图片保存位置
    * @return        
    */
    public final function __construct()
    {
    }
    
    /**
     * 验证文件是否是图片格式，文件大小是否合法
     * 如果验证成功则执行move操作，从临时文件移动到正式文件
     * 如果验证失败，返回false不进行move
     * 
     * @param array   $fileData
     * @param string  $target
     * @param bool    $overwrite
     * @param string $randomize
     * @param int $size 验证文件大小，默认为2M
     * @throws \Exception
     * @return boolean
     */
    public function upload($fileData, $target, $overwrite, $randomize=false, $size=null)
    {
        if (count($fileData) > 1) 
        {
            throw new \Exception('一次仅可验证一次文件');
            return false;
        }
        
        if (empty($target)) {
            return false;
        }
        
        //获取文件名称
        $name = key($fileData);
        //get input 
        $fileInput   = new FileInput($name);
        $fileInput   ->getValidatorChain()               // Validators are run first w/ FileInput
                     ->attach(new UploadFile())
                     ->attach(new Size($size))
                     ->attach(new IsImage());
        
        $fileInput   ->getFilterChain()                  // Filters are run second w/ FileInput
                     ->attach(new \Zend\Filter\File\RenameUpload([
                         'target'    => $target,
                         'randomize' => $randomize == true,
                         'overwrite'=>$overwrite,
                         'use_upload_extension'=>true,
                         'use_upload_name'=>false,
                     ]));
        
        //do valid
        $InputFilter= new InputFilter();
        $InputFilter->add($fileInput);
        $InputFilter->setData($fileData);
        $valid       = $InputFilter->isValid();
        $this->error = $InputFilter->getMessages();
        if (empty($valid)) {
            $this->values = [];
            return false;
        }else {
            $this->values = $InputFilter ->getValues();
        }
        return true;
    }
    
    /**
    * 获取url，必须保证可以生产url
    * 
    * @return string $url       
    */
    public function getUrl()
    {
        $values = $this->values;
        if (empty($values)) {
            return ;
        }
        $values = current($values);
        $filename = $values['tmp_name'];
        $url      = str_replace('public', '', $filename);
        $url      = str_replace('\\', '/', $url);
        return $url;
    }
}