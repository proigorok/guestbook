<?php
namespace Records\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\FileInput;
use Zend\Validator;
use Zend\Filter;

class Records
{
    public $id;
    public $name;
    public $email;
    public $homepage;
    public $text;
    public $image;
    public $file;
    public $ip;
    public $browser;
    public $date;
    protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->email  = (isset($data['email'])) ? $data['email'] : null;
        $this->homepage  = (isset($data['homepage'])) ? $data['homepage'] : null;
        $this->text  = (isset($data['text'])) ? $data['text'] : null;
        $this->image  = (isset($data['image'])) ? $data['image'] : null;
        $this->file  = (isset($data['file'])) ? $data['file'] : null;
        $this->date  = (isset($data['date'])) ? $data['date'] : null;
        $this->ip  = (isset($data['ip'])) ? $data['ip'] : null;
        $this->browser  = (isset($data['browser'])) ? $data['browser'] : null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }	
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
               'validators' => array(array('name' => 'Alnum'))
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(array('name' => 'EmailAddress'))
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'homepage',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'text',
                'required' => true,
                'filters'  => array(
                    array(
                        'name' => 'StripTags',
                        'options' => array(
                            'allowTags' => array('a', 'code', 'i', 'strike', 'strong'),
                            'allowAttribs' => array('href', 'title')
                            )
                        ),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(array('name' => 'Records\Model\HtmlTags'))
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'ip',
                'required' => false,
                'validators' => array(array('name' => 'Ip'))
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'browser',
                'required' => false,
                'filters' => array(
                  array('name' => 'StripTags'),
                  array('name' => 'HtmlEntities')
                )
            )));
            
            $file = new FileInput('file');
            $file->setRequired(false);
            $file->getValidatorChain()
                ->addValidator(new Validator\File\UploadFile())
                ->addValidator(new Validator\File\Size(array('max' => 102400)))
                ->addValidator(new Validator\File\Extension('txt'));
            
            $file->getFilterChain()
                ->attach(new Filter\File\RenameUpload(array(
                        'target'    => PUBLIC_PATH.'/files/',
                        'use_upload_name' => true,
                        'randomize' => true,
            )));          
            
            $image = new FileInput('image');
            $image->setRequired(false);
            $image->getValidatorChain()
                ->addValidator(new Validator\File\UploadFile())
                ->addValidator(new Validator\File\Extension(array('png','jpg','gif')));
            
            $image->getFilterChain()
                ->attach(new Filter\File\RenameUpload(array(
                        'target'    => PUBLIC_PATH.'/files/',
                        'use_upload_name' => true,
                        'randomize' => true,
            )));
            $inputFilter->add($file)
                        ->add($image);

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    public function checkImage($image)
    {
        $validator = new \Zend\Validator\File\ImageSize(array(
                        'maxWidth' => 320, 'maxHeight' => 240));
        if (!$validator->isValid($image))                   
            Helper::resizeImage($image['tmp_name'],$validator->getMaxwidth(),$validator->getMaxHeight(),$validator->width,$validator->height,$image['tmp_name']);
    }    
}