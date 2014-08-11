<?php

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;
class MenuHelper extends AbstractHelper
{
    /**
     * service locator var....
     * @var
     */
    public $serviceLocator;

    protected $footerMenu = array(
        '/ajuda' => array (
            'name' => 'AJUDA/FAQ',
            'separator' => true
        ),
        '/destaque' => array (
            'name' => 'ANUNCIO EM DESTAQUE',
            'separator' => true
        ),
        '/banner' => array (
                'name' =>'PUBLICIDADE BANNERS',
                'separator' => true
         ),
        /*'/apps' => array (
            'name' =>'APLICACAO PARA ANDROID',
            'separator' => true
        ),*/
        '/contactos' => array (
            'name' => 'CONTACTAR'
        ),
    );

    public function __construct ($locator)
    {
       $this->serviceLocator = $locator;
    }
    
    public function __invoke ()
    {     
        return $this;
    }

    public function getFooterMenu ()
    {
        return $this->footerMenu;
    }

    public function getSearchOptions()
    {
        return $this;
    }

}