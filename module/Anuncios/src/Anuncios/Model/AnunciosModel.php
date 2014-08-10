<?php
/**
 * Created by PhpStorm.
 * User: mteixeira
 * Date: 8/7/14
 * Time: 9:57 AM
 */

namespace Anuncios\Model;
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
    public function setNewAnuncioData($anuncios, $postData)
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
        } else {
            $anuncios->setExpiration($configModel->generateExpirationDate());
            $anuncios->setCreated($configModel->getCurrentTime());
            $anuncios->setUpaded($configModel->getCurrentTime());
            $anuncios->setUrl($configModel->createShortLinkMapping());
        }
        return $anuncios;
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

    /**
     * use keys : mulheresprocurandohomens ,mulheresprocurandomulheres, homensprocurandomulheres , homensprocurandohomens
     * @param bool $idCategory
     * @return array
     */
    public function getAllAnuniosByCategory($idCategory = false, $limit = 1000000)
    {
        $allCats = [];
        if (!$idCategory) {
            $allCategories = $this->getEntityManager()->getRepository('Anuncios\Entity\Category')->findAll();
            array_shift($allCategories);
            foreach($allCategories as $category) {
                $id = $category->getId();
                $allCats[strtolower(preg_replace('/\s+/', '', $category->getName()))] = [
                    'id' => $id,
                    'name' => $category->getName(),
                    'sexs' => $this->getEntityManager()->createQuery("SELECT c FROM Anuncios\Entity\Sex c WHERE c.category = '$id' ORDER BY c.created DESC")
                        ->setMaxResults($limit)->getResult()
                ];
            }
           return $allCats;
        }
        //filter articles by category ID


    }

    /**
     * use keys : mulheresprocurandohomens ,mulheresprocurandomulheres, homensprocurandomulheres , homensprocurandohomens
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
        return $this->getEntityManager()->createQuery("SELECT c FROM Anuncios\Entity\Sex c WHERE c.state  = '$state' AND c.type  = '$type' OR  c.type  = '$typegrande'  ORDER BY c.created DESC")
           ->getResult();
    }

    public function getPopulares()
    {
        $type = self::DESTAQUE_PEQUENO;
        $state = self::APROVADO;
        return $this->getEntityManager()->createQuery("SELECT c FROM Anuncios\Entity\Sex c WHERE c.state  = '$state' AND c.type  = '$type'  ORDER BY c.created DESC")
            ->setMaxResults(2)->getResult();
    }

    public function getSpecial()
    {
        $typegrande = self::DESTAQUE_GRANDE;
        $state = self::APROVADO;
        return $this->getEntityManager()->createQuery("SELECT c FROM Anuncios\Entity\Sex c WHERE c.state  = '$state' AND c.type  = '$typegrande'  ORDER BY c.created DESC")
            ->setMaxResults(1)->getResult();
    }

    public function getAllAnuniosByCity($idCity)
    {

    }

    public function getAllAnuniosByCityCategory($idCity, $idCategory)
    {

    }
} 