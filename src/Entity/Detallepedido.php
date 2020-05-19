<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Detallepedido
 *
 * @ORM\Table(name="detallepedido", indexes={@ORM\Index(name="fk_detallepedido_productos1_idx", columns={"idproductos"}), @ORM\Index(name="fk_detallepedido_pedidos1_idx", columns={"idpedidos"})})
 * @ORM\Entity
 */
class Detallepedido
{
    /**
     * @var int
     *
     * @ORM\Column(name="iddetallepedido", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddetallepedido;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @var float|null
     *
     * @ORM\Column(name="precio", type="float", precision=10, scale=0, nullable=true)
     */
    private $precio;

    /**
     * @var \Pedidos
     *
     * @ORM\ManyToOne(targetEntity="Pedidos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idpedidos", referencedColumnName="idpedidos")
     * })
     */
    private $idpedidos;

    /**
     * @var \Productos
     *
     * @ORM\ManyToOne(targetEntity="Productos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idproductos", referencedColumnName="idproductos")
     * })
     */
    private $idproductos;

    public function getIddetallepedido(): ?int
    {
        return $this->iddetallepedido;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(?int $cantidad): self
    {
        $this->cantidad = $cantidad;

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

    public function getIdpedidos(): ?Pedidos
    {
        return $this->idpedidos;
    }

    public function setIdpedidos(?Pedidos $idpedidos): self
    {
        $this->idpedidos = $idpedidos;

        return $this;
    }

    public function getIdproductos(): ?Productos
    {
        return $this->idproductos;
    }

    public function setIdproductos(?Productos $idproductos): self
    {
        $this->idproductos = $idproductos;

        return $this;
    }


}
