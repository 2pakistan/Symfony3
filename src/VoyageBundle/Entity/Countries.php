<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Destination
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\CountriesRepository")
 */
class Countries
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
     * @ORM\Column(name="name", type="string", length=125, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shortname", type="string", length=5, nullable=true)
     */
    private $shortname;

    /**
     * @var integer
     *
     * @ORM\Column(name="phonecode", type="integer", length=5, nullable=true)
     */
    private $phonecode;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="VoyageBundle\Entity\States", mappedBy="country")
     */
    private $states;


    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="VoyageBundle\Entity\Utilisateurs", mappedBy="countriesVisited",  cascade={"persist"})
     */
    private $visitors;

    /**
     * States constructor.
     * @param \Doctrine\Common\Collections\Collection $states
     */
    public function __construct(\Doctrine\Common\Collections\Collection $states)
    {
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visitors = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return string
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * @param string $shortname
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
    }

    /**
     * @return int
     */
    public function getPhonecode()
    {
        return $this->phonecode;
    }

    /**
     * @param int $phonecode
     */
    public function setPhonecode($phonecode)
    {
        $this->phonecode = $phonecode;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @param \VoyageBundle\Entity\States $state
     */
    public function addState(States $state)
    {
        $this->states[] = $state;
    }

    /**
     * Remove state
     *
     * @param \VoyageBundle\Entity\States $state
     */
    public function removeState(States $state)
    {
        $this->states->removeElement($state);
    }

    /**
     * Add visitor
     *
     * @param \VoyageBundle\Entity\Utilisateurs $visitor
     *
     * @return Countries
     */
    public function addVisitor(\VoyageBundle\Entity\Utilisateurs $visitor)
    {
        $this->visitors[] = $visitor;

        return $this;
    }

    /**
     * Remove visitor
     *
     * @param \VoyageBundle\Entity\Utilisateurs $visitor
     */
    public function removeVoyages(\VoyageBundle\Entity\Utilisateurs $visitor)
    {
        $this->visitors->removeElement($visitor);
    }

    /**
     * Get visitors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

}
