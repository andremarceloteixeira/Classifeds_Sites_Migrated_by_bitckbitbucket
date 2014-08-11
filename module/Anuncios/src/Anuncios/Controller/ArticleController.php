<?php

namespace Anuncios\Controller;
use Zend\View\Model\ViewModel;
use Anuncios\Model\AnunciosModel;
class ArticleController extends BaseController
{
    /**
     * Criar new anuncio
     * @deprecated
     * @return ViewModel
     */
    public function indexAction()
    {
        $anunciosModel = new AnunciosModel();
        $anunciosModel->setEntityManager($this->getEntityManager());
        $anunciosModel->setQueryBuilder($this->getEntityManager()->createQueryBuilder());
        $sex = $this->getEntityManager()->getRepository('Anuncios\Entity\Sex')->findOneBy(array('url' => $this->getEvent()->getRouteMatch()->getParam('article')));
        $destaques  = $anunciosModel->getPopulares(8);
        $nDestaques[0] = array_slice($destaques, 0, 4);
        $nDestaques[1] = array_slice($destaques, 4, 8);
        $lines = 2;
        $viewVariables = compact('sex', 'nDestaques', 'lines');
        $view = new ViewModel($viewVariables);
        $view->setTemplate('anuncios/article/index');
        return $view;
    }
}
