<?php

namespace Anuncios\Controller;
use Zend\View\Model\ViewModel;
class ArticleController extends BaseController
{
    /**
     * Criar new anuncio
     * @deprecated
     * @return ViewModel
     */
    public function indexAction()
    {
        $view = new ViewModel();
        $view->setTemplate('anuncios/article/index');
        return $view;
    }
}
