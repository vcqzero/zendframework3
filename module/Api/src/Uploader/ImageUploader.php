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
    * 执行验证和过滤
    * 
    * @param  array $formConfig 验证时所用规则
    * @param  array $values 待验证或过滤数据
    * @param  bool  $deleteEmpty 是否删除表单中 value='' 的数据
    * @return        
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