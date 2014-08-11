<?php

namespace Application\Form;

use Anuncios\Model\AnunciosModel;

class SearchForm extends GenericForm
{

    public function __construct ($locator)
    {
        parent::__construct();
        $anunciosModel = new AnunciosModel();
        $anunciosModel->setEntityManager($locator->get('Doctrine\ORM\EntityManager'));
        $anunciosModel->setQueryBuilder($locator->get('Doctrine\ORM\EntityManager')->createQueryBuilder());
        $this->setUseInputFilterDefaults(false);
        $objectManager = $locator->get('Doctrine\ORM\EntityManager');
        $this->setName('search-form');
        $this->setAttribute('id', 'search-form');
        $this->setAttribute('class', 'search-form');
        $this->setAttribute('method', 'get');
        /*$this->add(
            array(
                'name' => 'searchSelect',
                'options' => array(),
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'options' => $anunciosModel->getAllCategories()
                )
            ));*/
        $this->add(array(
            'name' => 'searchSelect',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $objectManager,
                'target_class' => 'Anuncios\Entity\Category',
                'property' => 'name'
            ),
            'attributes' => array(
                'class' =>'categories span3'
            )
        ));


        $this->add(
                array(
                        'name' => 'submit',
                        'type' => 'Zend\Form\Element\Submit',
                        'attributes' => array(
                                'id' => 'search-btn',
                                'class' => 'btn btn-warning btn-large searchButton',
                        ),
       ));
    }
}


