<?php

namespace Anuncios\Model;
use Anuncios\Entity\Publicity;
use Anuncios\Model\ConfigModel  as Config;
use Doctrine\Common\Collections\ArrayCollection;
use Anuncios\Entity\CitiesRange;

class AnunciosModel extends  GenericModel
{

    const APROVADO = 'APROVADO';
    const PENDENTE = 'PENDENTE';
    const RENOVAR = 'RENOVAR';
    const DESTAQUE_GRANDE = 'DESTAQUE_GRANDE';
    const DESTAQUE_PEQUENO = 'DESTAQUE_PEQUENO';
    const NORMAL = 'NORMAL';
    const CONTACTAR_NO = 'NO';
    const CONTACTAR_YES= 'YES';
    const CONTACTAR_CONTACTO = 'CONTACTO';

    /**
     * Set Anuncios Data
     * @param $anuncios
     * @param $postData
     * @return mixed
     */
    public function setNewAnuncioData($anuncios, $postData, $createdAt = false )
    {
        $configModel = new Config();
        /**
         * @var $anuncios \Anuncios\Entity\Sex
         */
        $anuncios->setCategory($this->getEntityManager()->find('Anuncios\Entity\Category', $postData['category_id']));
        $anuncios->setCity( $this->getEntityManager()->find('Anuncios\Entity\City', $postData['city_id']));
        $anuncios->setCreated($configModel->getCurrentTime());
        $anuncios->setSendemail($postData['sendemail']);
        $anuncios->setLocal($configModel->escapeHtmlHacking($postData['local']));
        $anuncios->setDescription($configModel->escapeHtmlHacking($postData['description']));
        $anuncios->setTitle($configModel->escapeHtmlHacking($postData['title']));
        $anuncios->setEmail($postData['email']);
        if (isset($postData['id'])) {
            $anuncios->setId($postData['id']);
            $anuncios->setUpaded($configModel->getCurrentTime());
            $anuncios->setExpiration($anuncios->getExpiration());
            $anuncios->setCreated($createdAt);
        } else {
            $anuncios->setExpiration($configModel->generateExpirationDate());
            $anuncios->setCreated($configModel->getCurrentTime());
            $anuncios->setUpaded($configModel->getCurrentTime());
            $anuncios->setUrl($configModel->createShortLinkMapping());
        }
        return $anuncios;
    }

    public function findCategoryById($id)
    {
        return $this->getEntityManager()->find('Anuncios\Entity\Category', $id);
    }

    public function findCityById($id)
    {
        return $this->getEntityManager()->find('Anuncios\Entity\City', $id);
    }

    public function getAllCitiesByCategoryType($categoryType = false)
    {
        $collection = $newRes = [];
        $this->getQueryBuilder()->select(array('p.name,p.id, COUNT(a.city) as n'))
            ->from('Anuncios\Entity\Sex', 'a')
            ->leftJoin('a.city', 'p');
        $this->getQueryBuilder()->groupBy('p.id');
        $results = $this->getQueryBuilder()->getQuery()->getResult();
        $allCities = $this->getEntityManager()->getRepository('Anuncios\Entity\City')->findAll();
        foreach($results as $res) {
            $newRes[$res['id']] = ['name' => $res['name'] , 'n' =>  $res['n']];
        }
        foreach($allCities as $city) {
            $city = new CitiesRange($city->getName(), $city->getId());
            if (array_key_exists($city->getId(), $newRes)) {
                $city->setNumerOfSex($newRes[$city->getId()]['n']);
            }
            $collection[] = $city;
        }
        array_shift($collection);
        return new ArrayCollection($collection);
    }

    public function getAllCategories() {
        $allCategories = $this->getEntityManager()->getRepository('Anuncios\Entity\Category')->findAll();
        $newCategories = [];
        foreach($allCategories as $category) {
            $newCategories[$category->getId()] = $category->getName();

        }
        return $newCategories;
    }

    /**
     * use keys : mulheresprocurandohomens ,mulheresprocurandomulheres, homensprocurandomulheres , homensprocurandohomens
     * @param bool $idCategory
     * @return array
     */
    public function getAllAnuniosByCategory($type = false , $limit = 1000000)
    {
        $allCats = [];
        if (!$type) {
            $allCategories = $this->getEntityManager()->getRepository('Anuncios\Entity\Category')->findAll();
            array_shift($allCategories);
            foreach($allCategories as $category) {
                $id = $category->getId();
                $allCats[strtolower(preg_replace('/\s+/', '', $category->getName()))] = [
                    'id' => $id,
                    'name' => $category->getName(),
                    'sexs' => $this->getEntityManager()->createQuery("SELECT c FROM Anuncios\Entity\Sex c WHERE c.category = '$id' AND c.expiration > CURRENT_DATE() ORDER BY c.created DESC")
                        ->setMaxResults($limit)->getResult()
                ];
            }
           return $allCats;
        }
       return false;

    }

    /**
     *  use keys : mulheresprocurandohomens
     * ,mulheresprocurandomulheres,
     * homensprocurandomulheres , homensprocurandohomens
     * @param bool $idCategory
     * @return array
     */
    public function extractAllSexs()
    {
        return $this->getEntityManager()->getRepository('Anuncios\Entity\Sex')->findBy(
            array(),
            array('created' => 'DESC')
        );
    }


    public function getSexsAllDestaques()
    {
        $typegrande = self::DESTAQUE_GRANDE;
        $type = self::DESTAQUE_PEQUENO;
        $state = self::APROVADO;
        return $this->getEntityManager()->createQuery("SELECT c FROM Anuncios\Entity\Sex c WHERE c.state  = '$state' AND c.expiration > CURRENT_DATE() AND c.type  = '$type' OR  c.type  = '$typegrande'  ORDER BY c.created DESC")
           ->getResult();
    }


    /**
     * Mock
     * @return array
     */
    public function getBanners()
    {
        $banners = [];
        for($i=0; $i<2 ; $i++)  {
            for($j=0; $j<6 ; $j++)  {
                $banners[$i][$j] = new Publicity();
            }
        }
        return $banners;
    }


    public function getPopulares($limit = 2)
    {
        $type = self::DESTAQUE_PEQUENO;
        $state = self::APROVADO;
        return $this->getEntityManager()->createQuery("SELECT c FROM Anuncios\Entity\Sex c WHERE c.state  = '$state' AND c.type  = '$type' AND c.expiration > CURRENT_DATE()  ORDER BY c.created DESC")
            ->setMaxResults($limit)->getResult();
    }

    public function getSpecial($limit = 1)
    {
        $typegrande = self::DESTAQUE_GRANDE;
        $state = self::APROVADO;
        return $this->getEntityManager()->createQuery("SELECT c FROM Anuncios\Entity\Sex c WHERE c.state  = '$state' AND c.type  = '$typegrande' AND c.expiration > CURRENT_DATE() ORDER BY c.created DESC")
            ->setMaxResults($limit)->getResult();
    }

    public function getAllAnuniosByType($id, $type)
    {
        switch($type) {
            case 'category':
                $dpl = "SELECT c FROM Anuncios\Entity\Sex c WHERE c.state  = 'APROVADO' AND c.category = '$id' AND c.expiration > CURRENT_DATE()  ORDER BY c.created DESC";
                /*return $this->getQueryBuilder()->select('a')
                    ->from('Anuncios\Entity\Sex', 'a')
                    ->where("a.state =", static::APROVADO)
                    ->andWhere("a.city = " . $id)
                    ->andWhere("a.expiration > CURRENT_DATE()")
                    ->orderBy("a.created", 'DESC');*/
                break;
            case 'city':
                $dpl = "SELECT c FROM Anuncios\Entity\Sex c WHERE c.state  = 'APROVADO' AND c.city = '$id'  AND c.expiration > CURRENT_DATE() ORDER BY c.created DESC";
                /*return $this->getQueryBuilder()->select('a')
                    ->from('Anuncios\Entity\Sex', 'a')
                    ->where("a.state =", static::APROVADO)
                    ->andWhere("a.expiration > CURRENT_DATE()")
                    ->andWhere("a.category = " . $id)
                    ->orderBy("a.created", 'DESC'); */
                break;
        }
       return $this->getEntityManager()->createQuery($dpl);
    }
} 