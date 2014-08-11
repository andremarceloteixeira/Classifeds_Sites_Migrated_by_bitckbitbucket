<?php
namespace Application\Model;

// Add these import statements
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Contacts implements InputFilterAwareInterface
{
    public $email;

    /**
     * @param string $myEmail
     */
    public function setMyEmail($myEmail)
    {
        $this->myEmail = $myEmail;
    }

    /**
     * @return string
     */
    public function getMyEmail()
    {
        return $this->myEmail;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param boolean $senEmailCopy
     */
    public function setSenEmailCopy($senEmailCopy)
    {
        $this->senEmailCopy = $senEmailCopy;
    }

    /**
     * @return boolean
     */
    public function getSenEmailCopy()
    {
        return $this->senEmailCopy;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    public $message;
    public $myEmail = 'contacto.sexoja@gmail.com';
    public $senEmailCopy = false;
    public $name;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->email     = (isset($data['email']))     ? $data['email']     : null;
        $this->message = (isset($data['message'])) ? $data['message'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->senEmailCopy = (isset($_POST['sendcopy'])) ? $_POST['sendcopy'] : false;
        if($this->senEmailCopy == 'yes') {
            $this->senEmailCopy = true;
        }
        $this->title  = (isset($data['title']))  ? $data['title']  : null;
    }

    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();


            $inputFilter->add(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'message',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 3000,
                        ),
                    ),
                ),
            ));

            $inputFilter->add([
                'name' => 'email',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 5,
                            'max'      => 255,
                            'messages' => array(
                                \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Formato de email invalido'
                            )
                        ],
                    ],
                ],
            ]);
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}