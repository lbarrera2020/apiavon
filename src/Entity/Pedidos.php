<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pedidos
 *
 * @ORM\Table(name="pedidos", indexes={@ORM\Index(name="fk_pedidos_formapago1_idx", columns={"idformapago"}), @ORM\Index(name="fk_pedidos_campania1_idx", columns={"idcampania"})})
 * @ORM\Entity
 */
class Pedidos
{
    /**
     * @var int
     *
     * @ORM\Column(name="idpedidos", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpedidos;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechacreacion", type="date", nullable=true)
     */
    private $fechacreacion;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_add", type="string", length=10, nullable=false)
     */
    private $usuarioAdd;

    /**
     * @var float|null
     *
     * @ORM\Column(name="montopedido", type="float", precision=10, scale=0, nullable=true)
     */
    private $montopedido;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dui_cliente", type="string", length=10, nullable=true)
     */
    private $duiCliente;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_entrega", type="date", nullable=true)
     */
    private $fechaEntrega;

    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true)
     */
    private $estado;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pedidoscol", type="string", length=45, nullable=true)
     */
    private $pedidoscol;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccionentrega", type="string", length=200, nullable=true)
     */
    private $direccionentrega;

    /**
     * @var \Campania
     *
     * @ORM\ManyToOne(targetEntity="Campania")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcampania", referencedColumnName="idcampania")
     * })
     */
    private $idcampania;

    /**
     * @var \Formapago
     *
     * @ORM\ManyToOne(targetEntity="Formapago")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idformapago", referencedColumnName="idformapago")
     * })
     */
    private $idformapago;

    public function getIdpedidos(): ?int
    {
        return $this->idpedidos;
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

    public function getUsuarioAdd(): ?string
    {
        return $this->usuarioAdd;
    }

    public function setUsuarioAdd(string $usuarioAdd): self
    {
        $this->usuarioAdd = $usuarioAdd;

        return $this;
    }

    public function getMontopedido(): ?float
    {
        return $this->montopedido;
    }

    public function setMontopedido(?float $montopedido): self
    {
        $this->montopedido = $montopedido;

        return $this;
    }

    public function getDuiCliente(): ?string
    {
        return $this->duiCliente;
    }

    public function setDuiCliente(?string $duiCliente): self
    {
        $this->duiCliente = $duiCliente;

        return $this;
    }

    public function getFechaEntrega(): ?\DateTimeInterface
    {
        return $this->fechaEntrega;
    }

    public function setFechaEntrega(?\DateTimeInterface $fechaEntrega): self
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(?int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getPedidoscol(): ?string
    {
        return $this->pedidoscol;
    }

    public function setPedidoscol(?string $pedidoscol): self
    {
        $this->pedidoscol = $pedidoscol;

        return $this;
    }

    public function getDireccionentrega(): ?string
    {
        return $this->direccionentrega;
    }

    public function setDireccionentrega(?string $direccionentrega): self
    {
        $this->direccionentrega = $direccionentrega;

        return $this;
    }

    public function getIdcampania(): ?Campania
    {
        return $this->idcampania;
    }

    public function setIdcampania(?Campania $idcampania): self
    {
        $this->idcampania = $idcampania;

        return $this;
    }

    public function getIdformapago(): ?Formapago
    {
        return $this->idformapago;
    }

    public function setIdformapago(?Formapago $idformapago): self
    {
        $this->idformapago = $idformapago;

        return $this;
    }


}
