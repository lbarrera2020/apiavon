<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipousuario
 *
 * @ORM\Table(name="tipousuario")
 * @ORM\Entity
 */
class Tipousuario
{
    /**
     * @var int
     *
     * @ORM\Column(name="idtipousuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtipousuario;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tipo_usuario", type="string", length=10, nullable=true)
     */
    private $tipoUsuario;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", length=30, nullable=true)
     */
    private $descripcion;

    public function getIdtipousuario(): ?int
    {
        return $this->idtipousuario;
    }

    public function getTipoUsuario(): ?string
    {
        return $this->tipoUsuario;
    }

    public function setTipoUsuario(?string $tipoUsuario): self
    {
        $this->tipoUsuario = $tipoUsuario;

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


}
