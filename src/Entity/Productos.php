<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Productos
 *
 * @ORM\Table(name="productos", indexes={@ORM\Index(name="fk_productos_categorias1_idx", columns={"categorias"})})
 * @ORM\Entity
 */
class Productos
{
    /**
     * @var int
     *
     * @ORM\Column(name="idproductos", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idproductos;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=true)
     */
    private $descripcion;

    /**
     * @var float|null
     *
     * @ORM\Column(name="precio", type="float", precision=10, scale=0, nullable=true)
     */
    private $precio;

    /**
     * @var int|null
     *
     * @ORM\Column(name="stoc", type="integer", nullable=true)
     */
    private $stoc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="imagen", type="string", length=200, nullable=true)
     */
    private $imagen;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_creacion", type="date", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
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

    public function getIdproductos(): ?int
    {
        return $this->idproductos;
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

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(?float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getStoc(): ?int
    {
        return $this->stoc;
    }

    public function setStoc(?int $stoc): self
    {
        $this->stoc = $stoc;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(?\DateTimeInterface $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

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
