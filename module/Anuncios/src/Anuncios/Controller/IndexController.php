<?php

namespace Anuncios\Controller;
use Zend\Captcha\Image;
use Zend\View\Model\ViewModel;
use Anuncios\Entity\Sex;
use Anuncios\Form\AnuncioForm;
use Anuncios\Model\AnunciosModel;
use Anuncios\Model\ImageUpload;
use Anuncios\Entity\Image as Images;
class IndexController extends BaseController
{

    const ANUNIOS_ENTITY = 'Anuncios\Entity\Anuncios';
    const IMAGE_ENTITY = 'Anuncios\Entity\Images';


    /***
     * All anuncios action
     * @return ViewModel
     */
    public function anunciosAction()
    {
        $paramCities = false;
        $anunciosModel = new AnunciosModel();
        /*repository = $this->getEntityManager()->getRepository('Anuncios\Entity\Sex');
        $anuncios      = $repository->findAll(); */
        /*if ($token = $this->params()->fromRoute('city')) {

        } */



        $anunciosModel->setEntityManager($this->getEntityManager());
        $anunciosModel->setQueryBuilder($this->getEntityManager()->createQueryBuilder());
        $allDataCategory = $anunciosModel->getAllAnuniosByCategory();
        $sexs = $anunciosModel->extractAllSexs();
        $destaques = $anunciosModel->getSexsAllDestaques();
        $populares   = $anunciosModel->getPopulares();
        $especial   = $anunciosModel->getSpecial();
        $cities = $anunciosModel->getAllCitiesByCategoryType($paramCities);
        $viewVariables = compact('anuncios', 'cities', 'allDataCategory', 'sexs', 'destaques', 'especial', 'populares');
        $view = new ViewModel($viewVariables);
        $view->setTemplate('anuncios/index/anuncios');
        return $view;
    }

    /***
     * All anuncios action
     * @return ViewModel
     */
    public function emDestaqueAction()
    {
        $view = new ViewModel();
        $view->setTemplate('anuncios/index/em-destaque');
        return $view;
    }
    /***
     * All anuncios action
     * @return ViewModel
     */
    public function contactarDestaqueAction()
    {
        $view = new ViewModel();
        $view->setTemplate('anuncios/index/contactar-destaque');
        return $view;
    }


    /**
     * Edit action
     * Add / Edit anuncio
     */
    public function editAction()
    {
        $isEdit = $hash = false;
        $title = 'CRIAR UM ANUNCIO GRATIS';
        $fkCidade = $fkCategoria = false;
        $anuncios = new Sex();
        $anunciosModel = new AnunciosModel();
        $anunciosModel->setEntityManager($this->getEntityManager());
        if ($token = $this->params()->fromRoute('id')) {
            $title = 'EDITAR O SEU ANUNCIO';
            $hash = $token;
            $isEdit = true;
            $anuncios = $this->getEntityManager()->getRepository('Anuncios\Entity\Sex')->findOneBy(array('url' => $token));
            if (is_null($anuncios)) {
                $this->flashMessenger()->addInfoMessage(array(
                    'message' => 'O anuncio já não existe: Porque ocoreu algum erro, ou porque expirou!',
                    'title' => 'Informação:'
                ));
                return $this->redirect()->toUrl('/anuncios');
            }
            $fkCidade = $anuncios->getCity()->getId();
            $fkCategoria = $anuncios->getCategory()->getId();
            $isEdit = $anuncios->getId();
        }
        $form = new AnuncioForm($this->getEntityManager(), $isEdit);
        $form->bind($anuncios);
        $request = $this->getRequest();
        try {
            if ($request->isPost()) {
                $form->setInputFilter($anuncios->getInputFilter());
                $postArr = $request->getPost()->toArray();
                $fileArr = $request->getFiles()->toArray();
                $formData = array_merge_recursive(
                    $postArr, //POST
                    $fileArr
                );
                $form->setData($formData);
                if ($form->isValid()) {
                    $postData = ($request->getPost()->toArray());
                    // You can do your own move, or use Zend\Validator\File\Rename
                    $imageUpload = new ImageUpload($formData['images']);
                    if($validate = $imageUpload->validateImage()) {
                        if(is_array($validate)) {
                            $this->flashMessenger()->addErrorMessage(array(
                                'message' => $validate['error'],
                                'title' => 'Imagens Invalidas',
                                'titleTag' => 'h4',
                                'isBlock' => true,
                            ));
                        } else {
                            $imageUpload->uploadImages();
                            $em = $this->getEntityManager();
                            /**
                             * @var AnunciosModel
                             */
                            $anuncios = $anunciosModel->setNewAnuncioData($anuncios, $postData);
                            if(!isset($formData['id'])) {
                                $em->persist($anuncios);
                            } else {
                                $anunciosObj  = $this->getEntityManager()->getRepository('Anuncios\Entity\Sex')->findOneBy(array('id' => $formData['id']));
                                /**
                                 * @var \Anuncios\Entity\Sex
                                 */
                                $anuncios->setExpiration($anunciosObj->getExpiration());
                                $anuncios->setCreated($anunciosObj->getCreated());
                                $anuncios->setUrl($anunciosObj->getUrl());
                                $em->merge($anuncios);
                            }
                            $em->flush();
                            $imagesForSave = $imageUpload->getImageNamesForSave();
                            if (!empty($imagesForSave)) {
                                foreach($imagesForSave as $url) {
                                    $url .= '.png';
                                    /**
                                     * @var Images
                                     */
                                    $entity = new Images();
                                    $entity->setUrl($url);
                                    if (!isset($formData['id'])) {
                                        $em->persist($entity); //$em is an instance of EntityManager
                                        $entity->setSex($anuncios);
                                    } else {
                                        $entity->setSex($anuncios);
                                        $em->merge($entity); //$em is an instance of EntityManager
                                    }
                                    $em->flush();
                                }
                            }
                            //send email... to person!
                            if(!isset($formData['id'])) {
                               $this->sendEmailCriacaoAnuncio($anuncios->getEmail(), $anuncios->getUrl());
                                $this->flashMessenger()->addSuccessMessage(array(
                                    'message' => 'O seu anuncio foi publicado. Recebeu um email com a informação sobre o mesmo que lhe permite editar, e eliminar',
                                    'title' => 'Parabéns!',
                                    'titleTag' => 'h4',
                                    'isBlock' => true,
                                ));
                            } else {
                                $this->flashMessenger()->addInfoMessage(array(
                                    'message' => 'O seu anuncio foi editado com sucesso',
                                    'title' => 'Edicão de anuncio!',
                                    'titleTag' => 'h4',
                                    'isBlock' => true));
                            }
                            return $this->redirect()->toRoute('anuncios');
                        }
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage(array(
                        'message' => 'Por favor preencha os dados do formulario correctamente',
                        'title' => 'Dados mal preenchidos',
                        'titleTag' => 'h4',
                        'isBlock' => true,
                    ));
                }
            }
        } catch (\Doctrine\ORM\ORMException $e) {
            $this->flashMessenger()->addErrorMessage(array(
                'message' => 'não foi possivel gravar o seu anuncio',
                'title' => 'Erro:'
            ));
            return $this->redirect()->toUrl('anuncios');
        }
        $viewVariables = compact('post', 'form', 'isEdit', 'fkCidade', 'fkCategoria', 'title', 'hash');
        $view = new ViewModel($viewVariables);
        $view->setTemplate('anuncios/index/edit');
        return $view;
    }
    /**
     * Add action
     */
    public function addAction()
    {
        return $this->editAction();
    }
    /**
     * Add action
     */
    public function editarAction()
    {
        return $this->editAction();
    }

    private function sendEmailCriacaoAnuncio($email, $hash)
    {
        $editar = $this->getBaseUrl() . $this->url()->fromRoute('anunciar', array('action' => 'editar', 'id' => $hash));
        $remover = $this->getBaseUrl() . $this->url()->fromRoute('anunciar', array('action' => 'remover', 'id' => $hash));
        $registar = $this->getBaseUrl() . $this->url()->fromRoute('registar', array('action' => 'index'));
        try {
            $this->sendEmail(
                $email,
                $this->getTranslatorHelper()->translate('Obrigado por anunciar conosco....'),
                sprintf($this->getTranslatorHelper()->translate('
                O link para editar o seu anuncio é: => %s
                O link para remover o seu anuncio é: => %s
                Pode ainda efectuar um registo no site com o mesmo email de criacao do anuncio.
                Registe-se aqui => %s '), $editar, $remover, $registar)

            );
        } catch (\Zend\Mail\Protocol\Exception\RuntimeException $e) {
            $this->flashMessenger()->addErrorMessage(array(
                'message' => 'não foi possivel enviar o mail com as informações.',
                'title' => 'Erro no envio de email: A possivel causa é a sua conexão á internet: '
            ));
        }
    }


}
