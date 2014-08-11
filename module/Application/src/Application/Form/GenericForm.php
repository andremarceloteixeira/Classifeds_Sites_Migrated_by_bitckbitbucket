<?php

namespace Application\Form;

use Zend\Form\Form;


abstract class GenericForm extends Form
{


    protected function addCsrf()
    {
        $this->add(
                array(
                        'name' => 'csrf',
                        'type' => 'Zend\Form\Element\Csrf',
                        'options' => array(
                                'csrf_options' => array(
                                        'timeout' => 3600 //one hour
                                )
                        )
                ));
    }


    public function cleanText($str)
    {
        return ($str == '')?'':strip_tags(trim($str));
    }
}
