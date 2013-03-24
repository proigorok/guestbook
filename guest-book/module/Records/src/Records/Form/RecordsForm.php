<?php
namespace Records\Form;

use Zend\Form\Form;

class RecordsForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('records');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
            ),
            'options' => array(
                'label' => 'Your e-mail',
            ),
        ));
        $this->add(array(
            'name' => 'homepage',
            'attributes' => array(
                'type'  => 'url',
            ),
            'options' => array(
                'label' => 'Your Homepage',
            ),
        ));
        $this->add(array(
            'name' => 'text',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Message',
            ),
        ));
        $this->add(array(
            'name' => 'image',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'Your Image',
            ),
        ));
        $this->add(array(
            'name' => 'file',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'Your File',
            ),
        ));
        $this->add(array(
          'type' => 'captcha',
          'name' => 'captcha',
          'options' => array(
            'captcha' => new Captcha\Image( array(
            'font' => '/data/fonts/3rd Man.ttf',
            ))              
          ),
      ));
        $this->add(array(
            'name' => 'ip',
            'attributes' => array(
                'type' => 'hidden',
                'value' => $_SERVER['REMOTE_ADDR'],
            ),
        ));
        $this->add(array(
            'name' => 'browser',
            'attributes' => array(
                'type' => 'hidden',
                'value' => $_SERVER['HTTP_USER_AGENT'],
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Add',
                'id' => 'submitbutton',
            ),
        ));
        }
}
