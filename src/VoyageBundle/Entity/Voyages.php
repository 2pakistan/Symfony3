<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Voyages
 *
 * @ORM\Table(name="voyages")
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\VoyagesRepository")
 * @Vich\Uploadable
 */
class Voyages
{


    /**
     * @var integer
     *
     * @ORM\Column(name="idVoyage", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idvoyage;

    /**
     * @var string
     *
     * @ORM\Column(name="titreVoyage", type="string", length=255, nullable=false)
     */
    private $titrevoyage;

    /**
     *
     * @Vich\UploadableField(mapping="trip_image", fileNameProperty="photovoyage")
     * @Assert\Image(
     *     maxSize = "2048k",
     *     mimeTypesMessage = "Veuillez uploader un fichier valide (extensions acceptées : .png .jpg .jpeg .bmp)"
     * )
     * @var File
     */
    private $imagefile;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="photoVoyage", type="string", length=255, nullable=true)
     */
    private $photovoyage;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionVoyage", type="string", length=500, nullable=true)
     */
    private $descriptionvoyage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebutVoyage", type="date", nullable=true)
     */
    private $datedebutvoyage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFinVoyage", type="date", nullable=true)
     */
    private $datefinvoyage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="VoyageBundle\Entity\Utilisateurs", mappedBy="voyages",  cascade={"persist"})
     */
    private $voyageur;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime;
        $this->photovoyage = 'default_trip_cover.jpg';
        $this->voyageur = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitrevoyage()
    {
        return $this->titrevoyage;
    }

    /**
     * @param string $titrevoyage
     */
    public function setTitrevoyage(string $titrevoyage)
    {
        $this->titrevoyage = $titrevoyage;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Utilisateurs
     */
    public function setImagefile(File $image = null)
    {
        $this->imagefile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImagefile()
    {
        return $this->imagefile;
    }


    /**
     * @return string
     */
    public function getPhotovoyage(): string
    {
        return $this->photovoyage;
    }

    /**
     * @param string $photovoyage
     */
    public function setPhotovoyage(string $photovoyage)
    {
        $this->photovoyage = $photovoyage;
    }


    /**
     * Set descriptionvoyage
     *
     * @param string $descriptionvoyage
     *
     * @return Voyages
     */
    public function setDescriptionvoyage($descriptionvoyage)
    {
        $this->descriptionvoyage = $descriptionvoyage;

        return $this;
    }

    /**
     * Get descriptionvoyage
     *
     * @return string
     */
    public function getDescriptionvoyage()
    {
        return $this->descriptionvoyage;
    }

    /**
     * Set datedebutvoyage
     *
     * @param \DateTime $datedebutvoyage
     *
     * @return Voyages
     */
    public function setDatedebutvoyage($datedebutvoyage)
    {
        $this->datedebutvoyage = $datedebutvoyage;

        return $this;
    }

    /**
     * Get datedebutvoyage
     *
     * @return \DateTime
     */
    public function getDatedebutvoyage()
    {
        return $this->datedebutvoyage;
    }

    /**
     * Set datefinvoyage
     *
     * @param \DateTime $datefinvoyage
     *
     * @return Voyages
     */
    public function setDatefinvoyage($datefinvoyage)
    {
        $this->datefinvoyage = $datefinvoyage;

        return $this;
    }

    /**
     * Get datefinvoyage
     *
     * @return \DateTime
     */
    public function getDatefinvoyage()
    {
        return $this->datefinvoyage;
    }

    /**
     * Get idvoyage
     *
     * @return integer
     */
    public function getIdvoyage()
    {
        return $this->idvoyage;
    }

    /**
     * Add voyageur
     *
     * @param \VoyageBundle\Entity\Utilisateurs $voyageur
     *
     * @return Voyages
     */
    public function addVoyageur(\VoyageBundle\Entity\Utilisateurs $voyageur)
    {
        $this->voyageur[] = $voyageur;

        return $this;
    }

    /**
     * Remove voyageur
     *
     * @param \VoyageBundle\Entity\Utilisateurs $voyageur
     */
    public function removeVoyageur(\VoyageBundle\Entity\Utilisateurs $voyageur)
    {
        $this->voyageur->removeElement($voyageur);
    }

    /**
     * Get voyageur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoyageur()
    {
        return $this->voyageur;
    }
}
