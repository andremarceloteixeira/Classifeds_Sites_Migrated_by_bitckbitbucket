<?php

namespace Anuncios\Controller;
use Zend\View\Model\ViewModel;
class ContactarController extends BaseController
{
    /**
     * Criar new anuncio
     * @deprecated
     * @return ViewModel
     */
    public function indexAction()
    {
        $view = new ViewModel();
        $view->setTemplate('anuncios/comentar/index');
        return $view;
    }
}
