<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Direccion
 *
 * @ORM\Table(name="direccion", indexes={@ORM\Index(name="fk_direccion_usuario_idx", columns={"idusuario"})})
 * @ORM\Entity
 */
class Direccion
{
    /**
     * @var int
     *
     * @ORM\Column(name="iddireccion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddireccion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccion", type="string", length=250, nullable=true)
     */
    private $direccion;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idusuario", referencedColumnName="idusuario")
     * })
     */
    private $idusuario;

    public function getIddireccion(): ?int
    {
        return $this->iddireccion;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

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

    public function getIdusuario(): ?Usuario
    {
        return $this->idusuario;
    }

    public function setIdusuario(?Usuario $idusuario): self
    {
        $this->idusuario = $idusuario;

        return $this;
    }


}
