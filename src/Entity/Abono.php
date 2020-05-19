<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abono
 *
 * @ORM\Table(name="abono", indexes={@ORM\Index(name="fk_abono_pedidos1_idx", columns={"idpedidos"})})
 * @ORM\Entity
 */
class Abono
{
    /**
     * @var int
     *
     * @ORM\Column(name="idabono", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idabono;

    /**
     * @var float|null
     *
     * @ORM\Column(name="monto", type="float", precision=10, scale=0, nullable=true)
     */
    private $monto;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechaabono", type="date", nullable=true)
     */
    private $fechaabono;

    /**
     * @var \Pedidos
     *
     * @ORM\ManyToOne(targetEntity="Pedidos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idpedidos", referencedColumnName="idpedidos")
     * })
     */
    private $idpedidos;

    public function getIdabono(): ?int
    {
        return $this->idabono;
    }

    public function getMonto(): ?float
    {
        return $this->monto;
    }

    public function setMonto(?float $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    public function getFechaabono(): ?\DateTimeInterface
    {
        return $this->fechaabono;
    }

    public function setFechaabono(?\DateTimeInterface $fechaabono): self
    {
        $this->fechaabono = $fechaabono;

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


}
