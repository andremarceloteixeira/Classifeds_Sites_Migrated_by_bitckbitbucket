<?php

namespace Anuncios\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Publicity
 *
 * @ORM\Table(name="publicity", indexes={@ORM\Index(name="fk_publicity_user1_idx", columns={"user_id", "user_email"})})
 * @ORM\Entity
 */
class Publicity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="publicity_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $publicityId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var \Anuncios\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Anuncios\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id"),
     *   @ORM\JoinColumn(name="user_email", referencedColumnName="email")
     * })
     */
    private $user;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $publicityId
     */
    public function setPublicityId($publicityId)
    {
        $this->publicityId = $publicityId;
    }

    /**
     * @return int
     */
    public function getPublicityId()
    {
        return $this->publicityId;
    }

    /**
     * @param \Anuncios\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Anuncios\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


}
