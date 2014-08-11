<?php

namespace Application\Form;


class ContactForm extends GenericForm
{
    public function __construct ()
    {
        parent::__construct();
        $this->setUseInputFilterDefaults(false);
        $this->setName('contacts');
        $this->add(
                array(
                        'name' => 'email',
                        'options' => array(
                            'label' => 'O seu Email *',
                            'label_attributes' => array(
                                'for' => 'email'
                            )
                        ),
                        'type' => 'Zend\Form\Element\Email',
                        'attributes' => array(
                            'id' => 'email',
                            'maxlength' => '50',
                            'class' => 'input-xlarge',
                        )
                ));

        $this->add(
                array(
                        'name' => 'name',
                        'options' => array(
                            'label' => 'O seu nome *',
                            'label_attributes' => array(
                                'for' => 'name'
                            )
                        ),
                        'type' => 'Zend\Form\Element\Text',
                        'attributes' => array(
                            'id' => 'firstName',
                            'class' => 'input-xlarge',
                        )
                ));

        $this->add(
                array(
                        'name' => 'message',
                        'options' => array(
                            'label' => 'Escreva a mensagem *',
                            'label_attributes' => array(
                                'for' => 'comments',
                            )
                        ),
                        'type' => 'Zend\Form\Element\Textarea',
                        'attributes' => array(
                            'id' => 'comments',
                            'class' => 'input-xlarge conctactos-tex-tarea',
                        )
                ));



        $this->add(
            array(
                'name' => 'sendcopy',
                'options' => array(
                    'label' => 'Enviar-me copia',
                    'unchecked_value' => '',
                    'checked_value' => 'yes',
                    'label_attributes' => array(
                        'for' => 'sendcopy'
                    ),

                ),
                'type' => 'Zend\Form\Element\Checkbox',
                'attributes' => array(
                )
            ));


        $this->add(
                array(
                        'name' => 'submit',
                        'type' => 'Zend\Form\Element\Submit',
                        'attributes' => array(
                            'value' => 'Envia Mensagem',
                            'id' => 'submitbutton',
                            'class' => 'btn btn-primary btn-mini enviar-btn'
                        )
                ));
        $this->addCsrf();
    }
}
