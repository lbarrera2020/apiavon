<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campania
 *
 * @ORM\Table(name="campania", indexes={@ORM\Index(name="fk_campania_tipo_campania1_idx", columns={"tipo_campania"})})
 * @ORM\Entity
 */
class Campania
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcampania", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcampania;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codigocampania", type="string", length=30, nullable=true)
     */
    private $codigocampania;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechainicio", type="date", nullable=true)
     */
    private $fechainicio;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechafin", type="date", nullable=true)
     */
    private $fechafin;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var \TipoCampania
     *
     * @ORM\ManyToOne(targetEntity="TipoCampania")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_campania", referencedColumnName="idtipo_campania")
     * })
     */
    private $tipoCampania;

    public function getIdcampania(): ?int
    {
        return $this->idcampania;
    }

    public function getCodigocampania(): ?string
    {
        return $this->codigocampania;
    }

    public function setCodigocampania(?string $codigocampania): self
    {
        $this->codigocampania = $codigocampania;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechainicio(): ?\DateTimeInterface
    {
        return $this->fechainicio;
    }

    public function setFechainicio(?\DateTimeInterface $fechainicio): self
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    public function getFechafin(): ?\DateTimeInterface
    {
        return $this->fechafin;
    }

    public function setFechafin(?\DateTimeInterface $fechafin): self
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    public function getEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(?bool $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getTipoCampania(): ?TipoCampania
    {
        return $this->tipoCampania;
    }

    public function setTipoCampania(?TipoCampania $tipoCampania): self
    {
        $this->tipoCampania = $tipoCampania;

        return $this;
    }


}
