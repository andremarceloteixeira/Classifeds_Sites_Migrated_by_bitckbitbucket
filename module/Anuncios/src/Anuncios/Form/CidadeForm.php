<?php
namespace Anuncios\Form;

use Zend\Form\Form;

class CidadeForm extends Form
{
    public function __construct()
    {
        parent::__construct('cidade');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'name',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array('label' => 'Nome'),
        ));

    }
}