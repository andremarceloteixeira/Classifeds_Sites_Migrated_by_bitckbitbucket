<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Anuncios\Controller\BaseController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ContactForm;
use Application\Model\Contacts;
use Anuncios\Model\AnunciosModel as Anuncios;

class IndexController extends BaseController
{

    /**
     * Index action
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $anunciosModel = new Anuncios();
        $anunciosModel->setEntityManager($this->getEntityManager());
        $anunciosModel->setQueryBuilder($this->getEntityManager()->createQueryBuilder());
        $destaques  =$anunciosModel->getPopulares(8);
        $top = $anunciosModel->getSpecial(3);//Para já
        $marcas = $anunciosModel->getBanners();
        $nDestaques[0] = array_slice($destaques, 0, 4);
        $nDestaques[1] = array_slice($destaques, 4, 8);
        $lines = 2;
        $viewVariables = compact('nDestaques', 'top', 'lines', 'marcas');
        $view = new ViewModel($viewVariables);
        return $view;
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
        $form = new ContactForm();
        $viewVariables = compact('form');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $contactosModel = new Contacts();
            $form->setInputFilter($contactosModel->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $contactosModel->exchangeArray($form->getData());
                //$this->getAlbumTable()->saveAlbum($album);
                try {
                    $string = 'O possivel cliente '. $contactosModel->getName(). ' enviou a seguinte messagem';
                    $this->sendEmail(
                        $contactosModel->getMyEmail(),
                        $this->getTranslatorHelper()->translate($string),
                        sprintf($this->getTranslatorHelper()->translate('
                Mensagem: => %s'),$contactosModel->getMessage()));

                    if ($contactosModel->getSenEmailCopy()) {
                        $this->sendEmail(
                            $contactosModel->getEmail(),
                            $this->getTranslatorHelper()->translate($string),
                            sprintf($this->getTranslatorHelper()->translate('
                Mensagem: => %s'),$contactosModel->getMessage()));
                    }
                } catch (\Zend\Mail\Protocol\Exception\RuntimeException $e) {
                    $this->flashMessenger()->addErrorMessage(array(
                        'message' => 'não foi possivel enviar o mail com as informações.',
                        'title' => 'Erro no envio de email: A possivel causa é a sua conexão á internet: '
                    ));
                }

                $this->flashMessenger()->addSuccessMessage(array(
                    'message' => 'A sua mensagem foi enviada com sucesso',
                    'title' => 'Sucesso',
                    'titleTag' => 'h4',
                    'isBlock' => true,
                ));
                return $this->redirect()->toRoute('anuncios');
            } else {
                $this->flashMessenger()->addErrorMessage(array(
                    'message' => 'Por favor preencha os dados do formulário correctamente',
                    'title' => 'Erro',
                    'titleTag' => 'h4',
                    'isBlock' => true,
                ));
            }
        }
        $view = new ViewModel($viewVariables);
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
