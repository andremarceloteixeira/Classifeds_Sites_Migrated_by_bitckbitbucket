<?php

namespace Anuncios\Controller;
use Zend\Captcha\Image;
use Zend\View\Model\ViewModel;
use Anuncios\Entity\Sex;
use Anuncios\Form\AnuncioForm;
use Anuncios\Model\AnunciosModel;
use Anuncios\Model\ImageUpload;
use Anuncios\Entity\Image as Images;
// Pagination
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
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
        $type = $paginator = $city = $category = $allDataCategory = $listOfSexs = null;
        $paramCities = false;
        $anunciosModel = new AnunciosModel();
        $anunciosModel->setEntityManager($this->getEntityManager());
        $anunciosModel->setQueryBuilder($this->getEntityManager()->createQueryBuilder());
        $title = $searchCategory = false;
        if ($cityId = $this->params()->fromRoute('city')) {
            $city = $anunciosModel->findCityById($cityId);
            if (!is_null($city)) {
                $type = ['type' => 'city', 'current' => $cityId];
                $searchCategory = true;
                $title = 'Resultados para '. '"'.$city->getName().'"';
                $listOfSexs = $anunciosModel->getAllAnuniosByType($city->getId(), 'city');
                if(count($listOfSexs) == 0) {
                    $this->flashMessenger()->addInfoMessage(array(
                        'message' => 'Não existem resultados',
                        'title' => 'Informação:'
                    ));
                    return $this->redirect()->toUrl('/anuncios');
                }
            }
        } elseif ($this->params()->fromRoute('category') || ($this->params()->fromQuery('searchSelect'))) {
            $searchCategory = true;
            $search = $this->params()->fromQuery('searchSelect');
            if (!empty($search)) {
                $id = $this->params()->fromQuery('searchSelect');
            } else {
                $id = $this->params()->fromRoute('category');
            }
            $type = ['type' => 'category', 'current' => $id];
            switch($id) {
                case 2:
                case 3:
                case 4:
                case 5:
                    $category = $anunciosModel->findCategoryById($id);
                    $title =     $title = 'Resultados para '. '"'.$category->getName().'"';
                    $listOfSexs = $anunciosModel->getAllAnuniosByType($category->getId(), 'category');
                    break;
                default:
                    $searchCategory = false;
                    break;
            }
        }
        if (empty($city) && empty($category)) {
            $allDataCategory = $anunciosModel->getAllAnuniosByCategory();
        } else {
            $repository = $this->getEntityManager()->getRepository('Anuncios\Entity\Sex');
            $adapter = new DoctrineAdapter(new ORMPaginator($listOfSexs));
            // Create the paginator itself
            $paginator = new Paginator($adapter);
            $page = $this->params()->fromRoute('page', 1);
            $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(9);
        }
        $sexs = $anunciosModel->extractAllSexs();
        $destaques = $anunciosModel->getSexsAllDestaques();
        $populares   = $anunciosModel->getPopulares();
        $especial   = $anunciosModel->getSpecial();
        $cities = $anunciosModel->getAllCitiesByCategoryType($paramCities);
        $viewVariables = compact('type','anuncios', 'cities', 'allDataCategory', 'sexs', 'destaques', 'especial', 'populares', 'title', 'searchCategory', 'listOfSexs', 'paginator');
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
        $created = $id = $isEdit = $hash = false;
        $title = 'CRIAR UM ANUNCIO GRATIS';
        $fkCidade = $fkCategoria = false;
        $anuncios = new Sex();
        $anunciosModel = new AnunciosModel();
        $anunciosModel->setEntityManager($this->getEntityManager());
        if ($token = $this->params()->fromRoute('id')) {
            $title = 'EDITAR O SEU ANUNCIO';
            $id = $token;
            $isEdit = true;
            $anuncios = $this->getEntityManager()->getRepository('Anuncios\Entity\Sex')->findOneBy(array('id' => $token));
            if (!is_null($anuncios)) {
                $hash = $anuncios->getUrl();
                if(($this->params()->fromRoute('hash') != $hash)) {
                    $this->flashMessenger()->addInfoMessage(array(
                        'message' => 'O anuncio já não existe: Porque ocoreu algum erro, ou porque expirou!',
                        'title' => 'Informação:'
                    ));
                    return $this->redirect()->toUrl('/anuncios');
                }
            } else {
                $this->flashMessenger()->addInfoMessage(array(
                    'message' => 'O anuncio já não existe: Porque ocoreu algum erro, ou porque expirou!',
                    'title' => 'Informação:'
                ));
                return $this->redirect()->toUrl('/anuncios');
            }
            $fkCidade = $anuncios->getCity()->getId();
            $fkCategoria = $anuncios->getCategory()->getId();
            $isEdit = $anuncios->getId();
            $created = $anuncios->getCreated();
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
                            $anuncios = $anunciosModel->setNewAnuncioData($anuncios, $postData, $created);
                            if(!isset($formData['id'])) {
                                $em->persist($anuncios);
                            } else {
                                if(isset($formData['removeImages'])) {
                                    foreach($formData['removeImages'] as $image) {
                                        //mais tarde remover a imagem do servidor!
                                        $image = $this->getEntityManager()->getRepository('Anuncios\Entity\Image')->findOneBy(array('id' => $image));
                                        $em->remove($image);
                                        $em->flush();
                                    }
                                }
                                $em->merge($anuncios);
                            }
                            $em->flush();
                            $anuncios->getId();
                            // para oferecer os Destaque as 11 primeiros para setar a primeira pagina
                            //Solução Linda!
                            if(in_array($anuncios->getId(), [8,9,10,11])) {
                                $anuncios->setType('DESTAQUE_PEQUENO');
                                $em->merge($anuncios);
                                $em->flush();
                            }
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
                               $this->sendEmailCriacaoAnuncio($anuncios->getEmail(), $anuncios->getUrl(), $anuncios->getId());
                                $this->flashMessenger()->addSuccessMessage(array(
                                    'message' => 'O seu anuncio foi publicado. Recebeu um email com a informação sobre o mesmo que lhe permite editar, entre outras informações.',
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
            return $this->redirect()->toUrl('/anuncios');
        }
        $viewVariables = compact('post', 'form', 'isEdit', 'fkCidade', 'fkCategoria', 'title', 'hash', 'id');
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

    private function sendEmailCriacaoAnuncio($email, $hash, $id)
    {
        $editar = $this->getBaseUrl() . $this->url()->fromRoute('anunciar', array('action' => 'editar', 'hash' => $hash, 'id' => $id));
        $remover = $this->getBaseUrl() . $this->url()->fromRoute('anunciar', array('action' => 'remover', 'id' => $hash));
        $registar = $this->getBaseUrl() . $this->url()->fromRoute('registar', array('action' => 'index'));
        //                O link para remover o seu anuncio é: => %s
        //
        try {
            $this->sendEmail(
                $email,
                $this->getTranslatorHelper()->translate('Obrigado por anunciar conosco....'),
                sprintf($this->getTranslatorHelper()->translate('
                O link para editar o seu anuncio é: => %s
                O codigo o seu anúncio é: => %s
                Mais tarde será possivel efectuar um registo no site com o mesmo email de criacao do anuncio, e gerir todos os seus anuncios, tal como novas funcionaliadades. Aguarde-nos :)
                Não se esqueça que pode destacar o seu anuncio. Para isso basta enviar um email com o codigo do seu anuncio e será fornecido um NIB. Após confirmação do pagamento  da opção escolhida o seu anuncio fica em destaque, nas proximas 24H.'), $editar, $hash)

            );
        } catch (\Zend\Mail\Protocol\Exception\RuntimeException $e) {
            $this->flashMessenger()->addErrorMessage(array(
                'message' => 'não foi possivel enviar o mail com as informações.',
                'title' => 'Erro no envio de email: A possivel causa é a sua conexão á internet: '
            ));
        }
    }


}
