<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    /**
     * Index action
     * @return array|ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * Ajuda action
     * @return ViewModel
     */
    public function ajudaAction()
    {
        $view = new ViewModel();
        $view->setTemplate('application/index/ajuda');
        return $view;
    }

    /**
     * banner Action
     * @return ViewModel
     */
    public function bannerAction()
    {
        $view = new ViewModel();
        $view->setTemplate('application/index/banner');
        return $view;
    }
    /**
     * contactos Action
     * @return ViewModel
     */
    public function contactosAction()
    {
        $view = new ViewModel();
        $view->setTemplate('application/index/contactos');
        return $view;
    }

    /**
     * Destaque Action
     * @return ViewModel
     */
    public function destaqueAction()
    {
        $view = new ViewModel();
        $view->setTemplate('application/index/destaque');
        return $view;
    }

    /**
     * Termos Action
     * @return ViewModel
     */
    public function termosAction()
    {
        $view = new ViewModel();
        $view->setTemplate('application/index/termos');
        return $view;
    }
}
