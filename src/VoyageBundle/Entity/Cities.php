<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Destination
 *
 * @ORM\Table(name="cities")
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\CitiesRepository")
 */
class Cities
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
     * @var \VoyageBundle\Entity\States
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\States", inversedBy="cities" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     * })
     */
    private $state;

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
     * @return int
     */
    public function getStateid()
    {
        return $this->stateid;
    }

    /**
     * @param int $stateid
     */
    public function setStateid($stateid)
    {
        $this->stateid = $stateid;
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


}
