<?php
namespace Records\Form;

use Zend\Form\Form;

class SortingForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('records');
        $this->setAttribute('method', 'get');

        $this->add(array(
            'name' => 'field',
            'type'  => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Sort by: ',
                'value_options' => array(
                    'date' => 'Date', 
                    'email' => 'E-mail', 
                    'name' => 'Username',
            )

         )));
        $this->add(array(
            'name' => 'order',
            'type'  => 'Zend\Form\Element\Select',
            'options' => array(
                'value_options' => array(
                    'asc' => 'ascending', 
                    'desc' => 'descending', 
            )
        )));
    }
}