<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * rencontres
 *
 * @ORM\Table(name="rencontres")
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\RencontresRepository")
 */
class Rencontres
{
    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="string")
     * @ORM\Id
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $dateFin;

    /**
     * @var \VoyageBundle\Entity\Cities
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\Cities", inversedBy="rencontres" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    private $lieu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="VoyageBundle\Entity\Inscription", mappedBy="rencontre", cascade={"persist"})
     */
    private $inscription;


    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;


    /**
     * Rencontres constructor.
     */
    public function __construct()
    {
        $this->inscription = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTime $dateFin
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return Cities
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param Cities $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * Add inscription
     *
     * @param \VoyageBundle\Entity\Inscription $inscription
     *
     * @return Rencontres
     */
    public function addInscription(\VoyageBundle\Entity\Inscription $inscription)
    {
        $this->inscription[] = $inscription;
        return $this;
    }

    /**
     * Remove inscription
     *
     * @param \VoyageBundle\Entity\Inscription $inscription
     */
    public function removeInscription(\VoyageBundle\Entity\Inscription $inscription)
    {
        $this->inscription->removeElement($inscription);
    }

    /**
     * Get inscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInscription()
    {
        return $this->inscription;
    }



}
