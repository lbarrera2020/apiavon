<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorias
 *
 * @ORM\Table(name="categorias")
 * @ORM\Entity
 */
class Categorias
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcategorias", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcategorias;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", length=45, nullable=true)
     */
    private $descripcion;

    public function getIdcategorias(): ?int
    {
        return $this->idcategorias;
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


}
