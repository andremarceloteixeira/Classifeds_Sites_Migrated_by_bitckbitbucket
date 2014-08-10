<?php
/**
 * Created by PhpStorm.
 * User: mteixeira
 * Date: 8/6/14
 * Time: 11:04 PM
 */

namespace Anuncios\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Doctrine\ORM\EntityManager as ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Anuncios\Entity\Image as Images;

class ImageFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $fileUploadDir = 'public/assets/images/anuncios/';

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('product-image');

        $this->setHydrator(new DoctrineHydrator($objectManager,
            'Application\Entity\Image'))
            ->setObject(new Images());

        $this->add(array(
                'name' => 'images',
                'type' => 'Zend\Form\Element\File',
                'options' => array(
                    'label' => 'Imagem',
                    'label_attributes' => array(
                        'class' => 'form-label'
                    ),
                    'multiple' => true,
                    'id' => 'filename'
                )
            )
        );

    }
    public function getInputFilterSpecification()
    {
       return array();
    }
}