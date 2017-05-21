<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\InscriptionRepository")
 */
class Inscription
{


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $dateInscription;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    private $isValidated;

    /**
     * @var \VoyageBundle\Entity\Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\Utilisateurs", inversedBy="inscription" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     * @ORM\Id
     */
    private $user;

    /**
     * @var \VoyageBundle\Entity\Rencontres
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\Rencontres", inversedBy="inscription" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="code_rencontre", referencedColumnName="code")
     * })
     *
     * @ORM\Id
     */
    private $rencontre;



    /**
     * Inscription constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * @param \DateTime $dateInscription
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;
    }

    /**
     * @return bool
     */
    public function isIsValidated()
    {
        return $this->isValidated;
    }

    /**
     * @param bool $isValidated
     */
    public function setIsValidated($isValidated)
    {
        $this->isValidated = $isValidated;
    }

    /**
     * @return Utilisateurs
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Utilisateurs $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Rencontres
     */
    public function getRencontre()
    {
        return $this->rencontre;
    }

    /**
     * @param Rencontres $rencontre
     */
    public function setRencontre($rencontre)
    {
        $this->rencontre = $rencontre;
    }


}
