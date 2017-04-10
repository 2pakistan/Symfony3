<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Medias
 *
 * @ORM\Table(name="medias", indexes={@ORM\Index(name="FK_medias_idEtape", columns={"idEtape"}), @ORM\Index(name="FK_medias_idVoyage", columns={"idVoyage"})})
 * @ORM\Entity (repositoryClass="VoyageBundle\Repository\MediasRepository")
 * @Vich\Uploadable
 */
class Medias
{

    /**
     * @var integer
     *
     * @ORM\Column(name="idMedia", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmedia;

    /**
     * @var \VoyageBundle\Entity\Etapes
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\Etapes", inversedBy="medias" ,cascade={"persist"})
     * @ORM\JoinColumns=({
     *   @ORM\JoinColumn(name="idEtape", referencedColumnName="idEtape")
     *   @ORM\JoinColumn(name="voyage_id", referencedColumnName="voyage_id")
     * })
     */
    private $idetape;

    /**
     * @var \VoyageBundle\Entity\Voyages
     *
     * @ORM\ManyToOne(targetEntity="VoyageBundle\Entity\Voyages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idVoyage", referencedColumnName="idVoyage")
     * })
     */
    private $idvoyage;

    /**
     *
     * @Vich\UploadableField(mapping="step_image", fileNameProperty="pathMedia")
     * @Assert\Image(
     *     maxSize = "4096k",
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
     * @ORM\Column(name="pathMedia", type="string", length=255, nullable=true)
     */
    private $pathMedia;


    /**
     * Medias constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return File
     */
    public function getImagefile()
    {
        return $this->imagefile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imagefile
     *
     * @return Medias
     */
    public function setImagefile(File $imagefile = null)
    {
        $this->imagefile = $imagefile;

        if ($imagefile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getPathMedia()
    {
        return $this->pathMedia;
    }

    /**
     * @param string $pathMedia
     */
    public function setPathMedia( $pathMedia)
    {
        $this->pathMedia = $pathMedia;
    }

    /**
     * Get idmedia
     *
     * @return integer
     */
    public function getIdmedia()
    {
        return $this->idmedia;
    }

    /**
     * Set idetape
     *
     * @param \VoyageBundle\Entity\Etapes $idetape
     *
     * @return Medias
     */
    public function addEtapes(\VoyageBundle\Entity\Etapes $idetape = null)
    {
        if (!$this->idetape->contains($idetape)) {
            $this->setIdEtape($idetape);
        }
    }

    /**
     * Get idetape
     *
     * @return \VoyageBundle\Entity\Etapes
     */
    public function getIdetape()
    {
        return $this->idetape;
    }
    /**
     * Set idEtape
     *
     * @param \VoyageBundle\Entity\Etapes $etape
     *
     * @return Medias
     */
    public function setIdEtape(\VoyageBundle\Entity\Etapes $etape = null)
    {
        $this->idetape = $etape;

        return $this;
    }
    /**
     * Set idvoyage
     *
     * @param \VoyageBundle\Entity\Voyages $idvoyage
     *
     * @return Medias
     */
    public function setIdvoyage(\VoyageBundle\Entity\Voyages $idvoyage = null)
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
