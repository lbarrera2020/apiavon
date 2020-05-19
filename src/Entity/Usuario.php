<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", uniqueConstraints={@ORM\UniqueConstraint(name="usuario_UNIQUE", columns={"usuario"}), @ORM\UniqueConstraint(name="idusuario_UNIQUE", columns={"idusuario"})}, indexes={@ORM\Index(name="fk_usuario_tipousuario1_idx", columns={"idtipousuario"})})
 * @ORM\Entity
 */
class Usuario
{
    /**
     * @var int
     *
     * @ORM\Column(name="idusuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idusuario;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="usuario", type="string", length=10, nullable=true)
     */
    private $usuario;

    /**
     * @var string|null
     *
     * @ORM\Column(name="clave", type="string", length=100, nullable=true)
     */
    private $clave;

    /**
     * @var string|null
     *
     * @ORM\Column(name="correo", type="string", length=100, nullable=true)
     */
    private $correo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telcel", type="string", length=9, nullable=true)
     */
    private $telcel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telfijo", type="string", length=9, nullable=true)
     */
    private $telfijo;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechacreacion", type="date", nullable=true)
     */
    private $fechacreacion;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechainicio", type="date", nullable=true)
     */
    private $fechainicio;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccion1", type="string", length=200, nullable=true)
     */
    private $direccion1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccion2", type="string", length=200, nullable=true)
     */
    private $direccion2;

    /**
     * @var \Tipousuario
     *
     * @ORM\ManyToOne(targetEntity="Tipousuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtipousuario", referencedColumnName="idtipousuario")
     * })
     */
    private $idtipousuario;

    public function getIdusuario(): ?int
    {
        return $this->idusuario;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(?string $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getClave(): ?string
    {
        return $this->clave;
    }

    public function setClave(?string $clave): self
    {
        $this->clave = $clave;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(?string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getTelcel(): ?string
    {
        return $this->telcel;
    }

    public function setTelcel(?string $telcel): self
    {
        $this->telcel = $telcel;

        return $this;
    }

    public function getTelfijo(): ?string
    {
        return $this->telfijo;
    }

    public function setTelfijo(?string $telfijo): self
    {
        $this->telfijo = $telfijo;

        return $this;
    }

    public function getFechacreacion(): ?\DateTimeInterface
    {
        return $this->fechacreacion;
    }

    public function setFechacreacion(?\DateTimeInterface $fechacreacion): self
    {
        $this->fechacreacion = $fechacreacion;

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

    public function getEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(?bool $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getDireccion1(): ?string
    {
        return $this->direccion1;
    }

    public function setDireccion1(?string $direccion1): self
    {
        $this->direccion1 = $direccion1;

        return $this;
    }

    public function getDireccion2(): ?string
    {
        return $this->direccion2;
    }

    public function setDireccion2(?string $direccion2): self
    {
        $this->direccion2 = $direccion2;

        return $this;
    }

    public function getIdtipousuario(): ?Tipousuario
    {
        return $this->idtipousuario;
    }

    public function setIdtipousuario(?Tipousuario $idtipousuario): self
    {
        $this->idtipousuario = $idtipousuario;

        return $this;
    }


}
