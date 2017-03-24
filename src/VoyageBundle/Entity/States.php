<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Destination
 *
 * @ORM\Table(name="states")
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\StatesRepository")
 */
class States
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="VoyageBundle\Entity\Cities", mappedBy="state", cascade={"persist"})
     */
    private $cities;

    /**
     * @var \VoyageBundle\Entity\Countries
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\Countries", inversedBy="states" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

    /**
     * States constructor.
     * @param \Doctrine\Common\Collections\Collection $cities
     */
    public function __construct(\Doctrine\Common\Collections\Collection $cities)
    {
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * @param \VoyageBundle\Entity\Cities $city
     */
    public function addCity(Cities $city)
    {
        $this->cities[] = $city;
    }

    /**
     * Remove cities
     *
     * @param \VoyageBundle\Entity\Cities $city
     */
    public function removeCity(Cities $city)
    {
        $this->cities->removeElement($city);
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



}
