<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Destination
 *
 * @ORM\Table(name="destination")
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\DestinationRepository")
 */
class Destination
{
    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=50, nullable=true)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="nomDestination", type="string", length=255, nullable=true)
     */
    private $nomdestination;

    /**
     * @var integer
     *
     * @ORM\Column(name="idDestination", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddestination;

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Destination
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set nomdestination
     *
     * @param string $nomdestination
     *
     * @return Destination
     */
    public function setNomdestination($nomdestination)
    {
        $this->nomdestination = $nomdestination;

        return $this;
    }

    /**
     * Get nomdestination
     *
     * @return string
     */
    public function getNomdestination()
    {
        return $this->nomdestination;
    }

    /**
     * Get iddestination
     *
     * @return integer
     */
    public function getIddestination()
    {
        return $this->iddestination;
    }
}
