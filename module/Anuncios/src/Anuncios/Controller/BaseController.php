<?php
/**
 * Created by PhpStorm.
 * User: mteixeira
 * Date: 8/7/14
 * Time: 9:10 AM
 */

namespace Anuncios\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Zend\Mail\Message;
class BaseController extends AbstractActionController
{
    /**
     * @var ModuleOptions
     */
    protected $options;
    /**
     * @var Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Sets the EntityManager
     *
     * @param EntityManager $em
     * @access protected
     * @return PostController
     */
    protected function setEntityManager(EntityManager $em)
    {
        $this->entityManager = $em;
        return $this;
    }

    /**
     * Returns the EntityManager
     *
     * Fetches the EntityManager from ServiceLocator if it has not been initiated
     * and then returns it
     *
     * @access protected
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->setEntityManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
        }
        return $this->entityManager;
    }

    protected function getQueryBuilder()
    {
        return $this->getEntityManager()->createQueryBuilder();
    }

    protected function getTranslatorHelper()
    {
        if(null === $this->translatorHelper) {
            $this->translatorHelper = $this->getServiceLocator()->get('MvcTranslator');
        }

        return $this->translatorHelper;
    }

    /**
     * Send Email
     *
     * Sends plain text emails
     *
     */
    protected  function sendEmail($to = '', $subject = '', $messageText = '')
    {
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();

        $message->addTo($to)
            ->addFrom($this->getOptions()->getSenderEmailAdress())
            ->setSubject($subject)
            ->setBody($messageText);
        $transport->send($message);
    }

    /**
     * get options
     *
     * @return ModuleOptions
     */
    protected function getOptions()
    {
        if(null === $this->options) {
            $this->options = $this->getServiceLocator()->get('anuncios_module_options');
        }

        return $this->options;
    }

    /**
     * Get Base Url
     *
     * Get Base App Url
     *
     */
    protected function getBaseUrl()
    {
        $uri = $this->getRequest()->getUri();
        return sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
    }
} 