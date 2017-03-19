<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etapes
 *
 * @ORM\Table(name="etapes", indexes={@ORM\Index(name="FK_etapes_idVoyage", columns={"idVoyage"}), @ORM\Index(name="FK_etapes_idDestination", columns={"idDestination"})})
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\EtapesRepository")
 */
class Etapes
{
    /**
     * @var string
     *
     * @ORM\Column(name="descriptionEtape", type="string", length=5000, nullable=true)
     */
    private $descriptionetape;

    /**
     * @var integer
     *
     * @ORM\Column(name="idEtape", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idetape;

    /**
     * @var \VoyageBundle\Entity\Destination
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="VoyageBundle\Entity\Destination")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idDestination", referencedColumnName="idDestination")
     * })
     */
    private $iddestination;

    /**
     * @var \VoyageBundle\Entity\Voyages
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="VoyageBundle\Entity\Voyages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idVoyage", referencedColumnName="idVoyage")
     * })
     */
    private $idvoyage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="VoyageBundle\Entity\Medias", mappedBy="idetape", cascade={"persist"})
     */
    private $medias;

    /**
     * Etapes constructor.
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Add medias
     *
     * @param \VoyageBundle\Entity\Medias $medias
     * @return Medias
     */
    public function addMedias(\VoyageBundle\Entity\Medias $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param \VoyageBundle\Entity\Medias $media
     */
    public function removeMedias(\VoyageBundle\Entity\Medias $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Set descriptionetape
     *
     * @param string $descriptionetape
     *
     * @return Etapes
     */
    public function setDescriptionetape($descriptionetape)
    {
        $this->descriptionetape = $descriptionetape;

        return $this;
    }

    /**
     * Get descriptionetape
     *
     * @return string
     */
    public function getDescriptionetape()
    {
        return $this->descriptionetape;
    }

    /**
     * Set idetape
     *
     * @param integer $idetape
     *
     * @return Etapes
     */
    public function setIdetape($idetape)
    {
        $this->idetape = $idetape;

        return $this;
    }

    /**
     * Get idetape
     *
     * @return integer
     */
    public function getIdetape()
    {
        return $this->idetape;
    }

    /**
     * Set iddestination
     *
     * @param \VoyageBundle\Entity\Destination $iddestination
     *
     * @return Etapes
     */
    public function setIddestination(\VoyageBundle\Entity\Destination $iddestination)
    {
        $this->iddestination = $iddestination;

        return $this;
    }

    /**
     * Get iddestination
     *
     * @return \VoyageBundle\Entity\Destination
     */
    public function getIddestination()
    {
        return $this->iddestination;
    }

    /**
     * Set idvoyage
     *
     * @param \VoyageBundle\Entity\Voyages $idvoyage
     *
     * @return Etapes
     */
    public function setIdvoyage(\VoyageBundle\Entity\Voyages $idvoyage)
    {
        $this->idvoyage = $idvoyage;

        return $this;
    }

    /**
     * Get idvoyage
     *
     * @return \VoyageBundle\Entity\Voyages
     */
    public function getIdvoyage()
    {
        return $this->idvoyage;
    }
}
