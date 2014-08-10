<?php
namespace Anuncios\Form;

use Doctrine\ORM\EntityManager as ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class AnuncioForm extends Form
{
    public function __construct(ObjectManager $objectManager,$isEdit = false)
    {
        parent::__construct('post');

        $this->setAttribute('method', 'post')->setHydrator(new DoctrineHydrator($objectManager,
            'Application\Entity\Sex'));
        $this->setAttribute('enctype','multipart/form-data');
        $this->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
        ));
        if ($isEdit) {
            $this->add(array(
                'name' => 'id',
                'type'  => 'Zend\Form\Element\Hidden',
                'attributes' => array(
                    'value' => $isEdit,
                )
            ));
        }

        $this->add(array(
            'name' => 'category_id',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Categoria *',
                'object_manager' => $objectManager,
                'target_class' => 'Anuncios\Entity\Category',
                'property' => 'name'
            ),
            'attributes' => array(
                'required' => true,
                'class' =>'span3 categorias'
            )
        ));
        $this->add(array(
            'name' => 'city_id',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Cidade *',
                'object_manager' => $objectManager,
                'target_class' => 'Anuncios\Entity\City',
                'property' => 'name'
            ),
            'attributes' => array(
                'required' => true,
                'class' =>'span3 cidades',

            )
        ));
        $this->add(array(
            'name' => 'title',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array('label' => 'Titúlo * '),
            'attributes' => array(
                'class' => 'input-xxlarge',
                'property' => 'title'
            )
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array('label' => 'O seu email * '),
            'attributes' => array(
                'class' => 'input-xxlarge',
                'property' => 'email'
            )
        ));
        $this->add(array(
            'name' => 'description',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array('label' => 'Descrição *',  'property' => 'descricao'),
            'attributes' => array(
                'class' =>'input-xxlarge',
                'property' => 'description'
            )
        ));
        $this->add(array(
            'name' => 'local',
            'type'  => 'Zend\Form\Element\Text',
            'options' => array('label' => 'Local * '),
            'attributes' => array(
                'class' => 'input-xxlarge',
                'property' => 'local'
            )
        ));
        $imageFieldset = new ImageFieldset($objectManager);
        $imageFieldset->setName("images");
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'images[]',
            'options' => array(
                'allow_add' => true,
                'allow_remove' => false,
                'count' => 4,
                'target_element' => $imageFieldset
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'sendemail',
            'options' => array(
                'label' => 'Permitir o email para contactos?',
                'value_options' => array(
                    'YES' => 'Exibir meu endereço de email no site',
                    'CONTACTO' => 'Não mostrar meu email, mas permitir que outros entrem em contato através do formulário',
                    'NO' => 'Não exibir meu endereço de email',
                ),
            ),
            'attributes' => array(
                'value' => 'CONTACTO', //set checked to '1'
                'property' => 'sendemail'
            )
        ));
        $this->add(array(
            'name' => 'termosecondicoes',
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'attributes' => array(
                'value' => '0',
            ),
            'options' => array(
                'label' => 'Termos e condições',
                'value_options' => array(
                    '0' => 'Termos e condiçes(ver link no rodapé)',
                ),
            ),
        ));

        $this->setValidationGroup(array(
            'category_id', 'city_id', 'title','email','description','local',
            'images[]', 'sendemail', 'termosecondicoes'
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => !$isEdit ? 'CRIAR ANUNUCIO' : 'EDITAR ANUNCIO',
                'id' => 'submitbutton',
                    'class' => 'btn btn-large'

            ),
        ));
    }
}