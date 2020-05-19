<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formapago
 *
 * @ORM\Table(name="formapago")
 * @ORM\Entity
 */
class Formapago
{
    /**
     * @var int
     *
     * @ORM\Column(name="idformapago", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idformapago;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=true)
     */
    private $descripcion;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    public function getIdformapago(): ?int
    {
        return $this->idformapago;
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

    public function getEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(?bool $estado): self
    {
        $this->estado = $estado;

        return $this;
    }


}
