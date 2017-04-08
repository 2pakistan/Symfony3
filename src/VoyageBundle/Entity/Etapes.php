<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etapes
 *
 * @ORM\Table(name="etapes", indexes={@ORM\Index(name="FK_etapes_idVoyage", columns={"voyage_id"})} )
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\EtapesRepository")
 */
class Etapes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idEtape", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idetape;

    /**
     * @var \VoyageBundle\Entity\Countries
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\Countries" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

    /**
     * @var \VoyageBundle\Entity\Voyages
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\Voyages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="voyage_id", referencedColumnName="idVoyage" )
     * })
     */
    private $trip;

    /**
     * @var \VoyageBundle\Entity\Cities
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\Cities" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $cities;

    /**
     * @var \VoyageBundle\Entity\States
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\States" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $state;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="VoyageBundle\Entity\Medias", mappedBy="idetape", cascade={"persist"})
     */
    private $medias;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionEtape", type="string", length=5000, nullable=true)
     */
    private $descriptionetape;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", length=5000, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", length=5000, nullable=false)
     */
    private $longitude;

    /**
     * Etapes constructor.
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getIdetape()
    {
        return $this->idetape;
    }

    /**
     * @param int $idetape
     */
    public function setIdetape($idetape)
    {
        $this->idetape = $idetape;
    }

    /**
     * @return string
     */
    public function getDescriptionetape()
    {
        return $this->descriptionetape;
    }

    /**
     * @param string $descriptionetape
     */
    public function setDescriptionetape($descriptionetape)
    {
        $this->descriptionetape = $descriptionetape;
    }

    /**
     * @return Countries
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Countries $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return Voyages
     */
    public function getTrip()
    {
        return $this->trip;
    }

    /**
     * @param Voyages $trip
     */
    public function setTrip($trip)
    {
        $this->trip = $trip;
    }

    /**
     * @return Cities
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * @param Cities $cities
     */
    public function setCities($cities)
    {
        $this->cities = $cities;
    }

    /**
     * @return States
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param States $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     *
     * @param \VoyageBundle\Entity\Medias $medias
     *
     * @return Etapes
     **/
    public function addMedia($medias)
    {
        $this->medias[] = $medias ;
        return $this;
    }

    /**
     * @param mixed $medias
     */
    public function removeMedia($medias)
    {
        $this->medias->removeElement($medias);
    }

    /**
     * @return floats
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

}
