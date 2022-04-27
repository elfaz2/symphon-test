<?php

namespace App\Entity;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{    
    /*
     * @var int
     * @OA\Property(description="The unique identifier of the user.")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @OA\Property(type="string", maxLength=255)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $make;

    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @OA\Property(type="string", maxLength=255)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $model;

    /**
     * @Assert\NotBlank
     * 
     * @OA\Property(type="enum", description="used | new")
     */
    #[ORM\Column(type: 'string', length: 255, columnDefinition: "enum('used', 'new')")]
    private $vehicle_type;

    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @OA\Property(type="string", maxLength=255)
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $vin;

    /**
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @OA\Property(type="integer")
     */
    #[ORM\Column(type: 'integer')]
    private $miles;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^\d+(\.\d+)?/")
     * @OA\Property(type="decimal", description="precision: 20, scale: 2")
     */
    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private $msrp;

    /**
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @OA\Property(type="integer")
     */
    #[ORM\Column(type: 'integer')]
    private $year;

    /**
     * @Assert\NotBlank
     * @Assert\Type("datetime")
     * @OA\Property(type="datetime")
     */
    #[ORM\Column(type: 'datetime')]
    private $date_added;

    /**
     * @Assert\Type("boolean")
     * 
     * @OA\Property(type="boolean", description="vehicle status deleted or not")
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(string $make): self
    {
        $this->make = $make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getVehicleType(): ?string
    {
        return $this->vehicle_type;
    }

    public function setVehicleType(string $type): self
    {
        $this->vehicle_type = $type;

        return $this;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function setVin(string $vin): self
    {
        $this->vin = $vin;

        return $this;
    }

    public function getMiles(): ?int
    {
        return $this->miles;
    }

    public function setMiles(int $miles): self
    {
        $this->miles = $miles;

        return $this;
    }

    public function getMsrp(): ?string
    {
        return $this->msrp;
    }

    public function setMsrp(string $msrp): self
    {
        $this->msrp = $msrp;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->date_added;
    }

    public function setDateAdded(\DateTimeInterface $date_added): self
    {
        $this->date_added = $date_added;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function dataDefaults()
    {
        $this->date_added = new \DateTime();
    }
  
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'date_added' => $this->getDateAdded(),
            'vehicle_type' => $this->getVehicleType(),
            'msrp' => $this->getMsrp(),
            'year' => $this->getYear(),
            'make' => $this->getMake(),
            'model' => $this->getModel(),
            'miles' => $this->getMiles(),
            'vin' => $this->getVin(),
            'deleted' => $this->getDeleted(),
        ];
    }
}
