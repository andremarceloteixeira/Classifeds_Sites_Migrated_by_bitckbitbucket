<?php

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;
class PageHelper extends AbstractHelper
{
    public $serviceLocator;
    const MARCAS_LINE = 2;
    public function __construct ($locator)
    {
        $this->serviceLocator = $locator;
    }

    public function __invoke ()
    {
        return $this;
    }

    //_resize;
    public function getUrl($type = null, $path = null, $r = true)
    {
        switch ($type)
        {
            case 'image' :
                $url =  ROOT_PATH . '/public/data/uploads/'. $path ;
                if ($r) {
                    $url = ROOT_PATH . '/public/data/uploads/'. $path ;
                }
                $url = $this->imageExists($url)  && !empty($path) ?  $this->getHost() . '/data/uploads/resize/'. $path  : $this->getHost() . '/data/uploads/placeholder.png';
                break;
            default :
                $url = $this->getHost() . '/' .$path;
                break;
        }
        return $this->getProtocol(). '://' . $url;
    }

    public function getBannerUrl($url)
    {
        if (empty($url)) {
            return $this->getProtocol(). '://' . $this->getHost() .'/themes/images/clients/1.png';
        }
        return $this->getProtocol(). '://' . $this->getHost() . $url;
    }

    public function getBigImage($path = null)
    {
        $url = $this->getHost() . '/data/uploads/big/'. $path ;
        $url = $this->imageExists($url) ? $url : $this->getHost() . '/data/uploads/big/placeholder.png';
        return $this->getProtocol(). '://' . $url;
    }

    public function getDestaqueImage($path = null)
    {
        $url = $this->getHost() . '/data/uploads/destaque/'. $path ;
        $url = $this->imageExists($url) ? $url : $this->getHost() . '/data/uploads/destaque/placeholder.png';
        return $this->getProtocol(). '://' . $url;
    }

    public function formatDate($date)
    {
        return $date->format('d/m/Y');
    }

    private function imageExists($url)
    {
        if(!empty($url)) {
            if (file_exists($url)) {
                if ($this->URLIsValid($url)) {
                    return 1;
                }
            }
        }
        return false;
    }

    public function URLIsValid($url)
    {
        $fp = @fopen($url, "r");
        if ($fp !== false) {
            fclose($fp);
            return true;
        }

        return false;
    }

    public function getRequest()
    {
        return $this->serviceLocator->get('request');
    }


    public function getHost()
    {
        return $this->getRequest()->getUri()->getHost();
    }


    public function getDescription($url, $chars = 30)
    {
        if(strlen($url) <= $chars) {
            return substr($url, 0, $chars);
        }
        return substr($url, 0, $chars) . '...';
    }

    public function getProtocol()
    {
        return $this->getRequest()->getUri()->getScheme();
    }

}
