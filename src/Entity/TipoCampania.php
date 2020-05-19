<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoCampania
 *
 * @ORM\Table(name="tipo_campania", indexes={@ORM\Index(name="fk_tipo_campania_categorias1_idx", columns={"categorias"})})
 * @ORM\Entity
 */
class TipoCampania
{
    /**
     * @var int
     *
     * @ORM\Column(name="idtipo_campania", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtipoCampania;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=true)
     */
    private $descripcion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="estado", type="string", length=45, nullable=true)
     */
    private $estado;

    /**
     * @var \Categorias
     *
     * @ORM\ManyToOne(targetEntity="Categorias")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorias", referencedColumnName="idcategorias")
     * })
     */
    private $categorias;

    public function getIdtipoCampania(): ?int
    {
        return $this->idtipoCampania;
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

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getCategorias(): ?Categorias
    {
        return $this->categorias;
    }

    public function setCategorias(?Categorias $categorias): self
    {
        $this->categorias = $categorias;

        return $this;
    }


}
