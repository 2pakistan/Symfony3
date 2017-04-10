<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



/**
 * Utilisateurs
 *
 * @ORM\Table(name="utilisateurs")
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\UtilisateursRepository")
 * @Vich\Uploadable
 */
class Utilisateurs extends BaseUser
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Veuillez entrer votre nom", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     minMessage="Le nom est trop court.",
     *     maxMessage="Le nom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Veuillez entrer votre prénom", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     minMessage="Le prenom est trop court.",
     *     maxMessage="Le prenom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nationalite", type="string", length=50, nullable=true)
     */
    private $nationalite;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=25, nullable=true )
     */
    private $telephone;


    /**
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="photoprofil")
     * @Assert\Image(
     *     maxSize = "2048k",
     *     mimeTypesMessage = "Veuillez uploader un fichier valide (extensions acceptées : .png .jpg .jpeg .bmp)"
     * )
     * @var File
     */
    private $imagefile;


    /**
     * @var string
     *
     * @ORM\Column(name="photoProfil", type="string", length=255, nullable=true)
     */
    private $photoprofil;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;


    /**
     *
     * @Vich\UploadableField(mapping="user_cover", fileNameProperty="photocouverture")
     * @Assert\Image(
     *     maxSize = "2048k",
     *     mimeTypesMessage = "Veuillez uploader un fichier valide (extensions acceptées : .png .jpg .jpeg .bmp)"
     * )
     * @var File
     */
    private $imagefilecover;


    /**
     * @var string
     *
     * @ORM\Column(name="photoCouverture", type="string", length=255, nullable=true)
     */
    private $photocouverture;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionProfil", type="string", length=500, nullable=true )
     */
    private $descriptionprofil;

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="string", length=500, nullable=true )
     */
    private $review;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="VoyageBundle\Entity\Utilisateurs", inversedBy="follower", cascade={"persist"})
     * @ORM\JoinTable(name="etreami",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_utilisateurs", referencedColumnName="id")
     *   }
     * )
     */
    private $followed;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="VoyageBundle\Entity\Utilisateurs", mappedBy="followed",  cascade={"persist"})
     */
    private $follower;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="VoyageBundle\Entity\Voyages", inversedBy="voyageur",  cascade={"persist"})
     * @ORM\JoinTable(name="participer",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idVoyage", referencedColumnName="idVoyage")
     *   }
     * )
     */
    private $voyages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="VoyageBundle\Entity\Countries", inversedBy="visitors",  cascade={"persist"})
     */
    private $countriesVisited;

    public function __construct()
    {
        parent::__construct();
        $this->photoprofil = "unknown.jpg";
        $this->photocouverture = "vermont.jpg";
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
        $this->follower = new \Doctrine\Common\Collections\ArrayCollection();
        $this->followed = new \Doctrine\Common\Collections\ArrayCollection();
        $this->voyages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->countriesVisited = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateurs
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateurs
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nationalite
     *
     * @param string $nationalite
     *
     * @return Utilisateurs
     */
    public function setNationalite($nationalite)
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * Get nationalite
     *
     * @return string
     */
    public function getNationalite()
    {
        return $this->nationalite;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Utilisateurs
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
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
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Utilisateurs
     */
    public function setImagefilecover(File $image = null)
    {
        $this->imagefilecover = $image;

        if ($image) {

            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImagefilecover()
    {
        return $this->imagefilecover;
    }



    /**
     * Set photoprofil
     *
     * @param string $photoprofil
     *
     * @return Utilisateurs
     */
    public function setPhotoprofil($photoprofil)
    {
        $this->photoprofil = $photoprofil;

        return $this;
    }

    /**
     * Get photoprofil
     *
     * @return string
     */
    public function getPhotoprofil()
    {
        return $this->photoprofil;
    }

    /**
     * Set photocouverture
     *
     * @param string $photocouverture
     *
     * @return Utilisateurs
     */
    public function setPhotocouverture($photocouverture)
    {
        $this->photocouverture = $photocouverture;

        return $this;
    }

    /**
     * Get photocouverture
     *
     * @return string
     */
    public function getPhotocouverture()
    {
        return $this->photocouverture;
    }

    /**
     * Set descriptionprofil
     *
     * @param string $descriptionprofil
     *
     * @return Utilisateurs
     */
    public function setDescriptionprofil($descriptionprofil)
    {
        $this->descriptionprofil = $descriptionprofil;

        return $this;
    }

    /**
     * Get descriptionprofil
     *
     * @return string
     */
    public function getDescriptionprofil()
    {
        return $this->descriptionprofil;
    }


    /**
 * Add follower
 *
 * @param \VoyageBundle\Entity\Utilisateurs $follower
 *
 * @return Utilisateurs
 */
    public function addFollower(\VoyageBundle\Entity\Utilisateurs $follower)
    {
        $this->follower[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \VoyageBundle\Entity\Utilisateurs $follower
     */
    public function removeFollower(\VoyageBundle\Entity\Utilisateurs $follower)
    {
        $this->follower->removeElement($follower);
    }

    /**
     * Get follower
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollower()
    {
        return $this->follower;
    }

    /**
     * Add followed
     *
     * @param \VoyageBundle\Entity\Utilisateurs $followed
     *
     * @return Utilisateurs
     */
    public function addFollowed(\VoyageBundle\Entity\Utilisateurs $followed)
    {
        $this->followed[] = $followed;

        return $this;
    }

    /**
     * Remove followed
     *
     * @param \VoyageBundle\Entity\Utilisateurs $followed
     */
    public function removeFollowed(\VoyageBundle\Entity\Utilisateurs $followed)
    {
        $this->followed->removeElement($followed);
    }

    /**
     * Get followed
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowed()
    {
        return $this->followed;
    }

    /**
     * Add voyages
     *
     * @param \VoyageBundle\Entity\Voyages $voyages
     *
     * @return Utilisateurs
     */
    public function addVoyages(\VoyageBundle\Entity\Voyages $voyages)
    {
        $this->voyages[] = $voyages;

        return $this;
    }

    /**
     * Remove voyages
     *
     * @param \VoyageBundle\Entity\Voyages $voyages
     */
    public function removeVoyages(\VoyageBundle\Entity\Voyages $voyages)
    {
        $this->voyages->removeElement($voyages);
    }

    /**
     * Get voyages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoyages()
    {
        return $this->voyages;
    }

    /**
     * Add countryVisited
     *
     * @param \VoyageBundle\Entity\Countries $countryVisited
     *
     * @return Countries
     */
    public function addCountryVisited(\VoyageBundle\Entity\Countries $countryVisited)
    {
        $this->countriesVisited[] = $countryVisited;

        return $this;
    }

    /**
     * Remove countryVisited
     *
     * @param \VoyageBundle\Entity\Countries $countryVisited
     */
    public function removeCountryVisited(\VoyageBundle\Entity\Countries $countryVisited)
    {
        $this->countriesVisited->removeElement($countryVisited);
    }

    /**
     * Get countriesVisited
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCountriesVisited()
    {
        return $this->countriesVisited;
    }

    /**
     * @return string
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param string $review
     */
    public function setReview($review)
    {
        $this->review = $review;
    }


    /**
     * Get toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->prenom.' '.$this->nom;
    }
}
