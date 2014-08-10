<?php
/**
 * Created by PhpStorm.
 * User: mteixeira
 * Date: 8/8/14
 * Time: 11:57 AM
 */

namespace Anuncios\Entity;


class CitiesRange extends GenericEntity
{
    private $name;
    private $id;
    private $numerOfSex;


    public function __construct($name = '', $id ,$numerOfSex = 0)
    {
       $this->setId($id);
       $this->setName($name);
       $this->setNumerOfSex($numerOfSex);
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $numerOfSex
     */
    public function setNumerOfSex($numerOfSex)
    {
        $this->numerOfSex = $numerOfSex;
    }

    /**
     * @return mixed
     */
    public function getNumerOfSex()
    {
        return $this->numerOfSex;
    }
} 