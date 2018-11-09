<?php
namespace Api\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Zend\Validator\File\Size;
use Zend\Validator\File\UploadFile;
use Zend\Validator\File\IsImage;
/**
* @desc 数据表验证
*/
class FileFilter
{
    const PATH_FORM_FILTER_CONFIG = 'module/Api/src/Model/FileFilter/';
    const PATH_IMAGES = 'public/upload/images/';
    
    private static $instance;
    private $name;
    private $InputFilter;

    /**
     * get the instance of the FormFilter
     *
     * @param  Adapter $DbAdapter
     * @param  Logger  $Logger
     * @return self
     */
    public static function getInstance()
    {
        return empty(self::$instance) ? new self() : self::$instance;
    }
    
    private final function __construct()
    {
    }
    
    private final function __clone(){}
    
    /**
    * 执行验证和过滤
    * 
    * @param  array $formConfig 验证时所用规则
    * @param  array $values 待验证或过滤数据
    * @param  bool  $deleteEmpty 是否删除表单中 value='' 的数据
    * @return        
    */
    private function isValid($fileData)
    {
        if (count($fileData) > 1) 
        {
            throw new \Exception('一次仅可验证一次文件');
            return false;
        }
        
        $name = key($fileData);
        $this->name = $name;
        
        $file   = new FileInput();
        $file   ->setName($name);
        $file   ->getValidatorChain()               // Validators are run first w/ FileInput
                ->attach(new UploadFile())
                ->attach(new Size(1024 * 1024 * 2))
                ->attach(new IsImage());
        
        $file   ->getFilterChain()                  // Filters are run second w/ FileInput
                ->attach(new \Zend\Filter\File\RenameUpload([
                    'target'    => self::PATH_IMAGES . 'image',
                    'randomize' => true,
                    'use_upload_extension'=>true,
        ]));
                
        $InputFilter= new InputFilter();
        $InputFilter->add($file);
        $InputFilter->setData($fileData);
        $this->InputFilter = $InputFilter;
        return $InputFilter->isValid();
    }
    
    public function upload($fileData)
    {
        $isValid = $this->isValid($fileData);
        if(empty($isValid)) {
            return false;
        }
        $InputFilter = $this->InputFilter;
        $name = $this->name;
        $values = $InputFilter->getValues();
        $file   = $values[$name];
        $file['tmp_name'] = str_replace('\\', '/', $file['tmp_name']);
        return $file;
    }
}