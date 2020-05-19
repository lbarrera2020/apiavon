<?php

namespace App\Controller;

use App\Entity\Campania;
use App\Entity\Categorias;
use App\Entity\Detallepedido;
use App\Entity\Formapago;
use App\Entity\Pedidos;
use App\Entity\Productos;
use App\Entity\User;
use App\Entity\Usuario;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Security\Core\Security;
use App\Service\JsonSchema;
use JsonSchema\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DatosController
 *
 * @Route("/")
 */
class DatosController extends AbstractFOSRestController
{
    private $serializer;
    private $kernel;
    private $connection;
    private $security;
    private $em;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em, KernelInterface $kernel, Connection $connection, Security $security)
    {
        $this->serializer = $serializer;
        $this->kernel = $kernel;
        $this->em = $em;
        $this->connection = $connection;
        $this->security = $security;
    }

    /**
     * @Rest\Get("/spinnercanpania", name="spinnercanpania")
     *
     */
    public function getCampania(Request $request)
    {
        $em = $this->em;
        $serializer = $this->serializer;
        //$headers = $request->headers;
        $conn = $em->getConnection();
        $result = [];
        $array =[];

        $sql = "SELECT codigocampania as valor FROM campania where estado= :estado";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':estado' => 1]);
        $result = $stmt->fetchAll();
        $array['array'] = $result;

// Retornando response
        return new JsonResponse(json_encode($array), JsonResponse::HTTP_OK, array(), true);


    }

    /**
     * @Rest\Get("/spinnerProductos", name="spinnerProductos")
     *
     */
    public function getProductos(Request $request)
    {
        $em = $this->em;
        $serializer = $this->serializer;
        //$headers = $request->headers;
        $conn = $em->getConnection();
        $result = [];
        $array =[];

        $sql = "select idproductos,concat(a.descripcion,'   Precio: ','        $',a.precio) as descripcion, precio
        from productos as a inner join categorias as b on a.categorias=b.idcategorias
        inner join tipo_campania as c on b.idcategorias=c.categorias
        inner join campania as d on c.idtipo_campania=tipo_campania
        where a.estado=1 and codigocampania= :codigocampania";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':codigocampania' => 'CODIGO1']);
        $result = $stmt->fetchAll();
        $array['array'] = $result;

// Retornando response
        return new JsonResponse(json_encode($array), JsonResponse::HTTP_OK, array(), true);


    }

    /**
     * @Rest\Post("/validarUsuario", name="validarUsuario")
     *
     */
    public function getUsuario(Request $request)
    {
        $em = $this->em;
        $usuario             = $request->get('usuario');
        $clave             = $request->get('clave');
        $serializer = $this->serializer;
        //$headers = $request->headers;
        $conn = $em->getConnection();
        $result = [];
        $array =[];

        $sql = "SELECT * FROM usuario WHERE usuario= :usu_usuario AND clave= :usu_password";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':usu_usuario' => $usuario,':usu_password' =>$clave]);
        $result = $stmt->fetchAll();
        $array['array'] = $result;

// Retornando response
        return new JsonResponse(json_encode($array), JsonResponse::HTTP_OK, array(), true);


    }

    /**
     * @Rest\Get("/spinnercliente", name="spinnercliente")
     *
     */
    public function getUsuariocliente(Request $request)
    {
        $em = $this->em;
        $idtipo    = $request->get('idtipo');
        $serializer = $this->serializer;
        //$headers = $request->headers;
        $conn = $em->getConnection();
        $result = [];
        $array =[];

        $sql = "SELECT usuario as valor FROM usuario where idtipousuario= :idtipousuario order by nombre";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':idtipousuario' => $idtipo]);
        $result = $stmt->fetchAll();
        $array['array'] = $result;

// Retornando response
        return new JsonResponse(json_encode($array), JsonResponse::HTTP_OK, array(), true);

    }

    /**
     * @Rest\Post("/obtenerdirecciones", name="obtenerdirecciones")
     *
     */
    public function getDirecciones(Request $request)
    {
        $em = $this->em;
        $usuario             = $request->get('usuario');
        $serializer = $this->serializer;
        //$headers = $request->headers;
        $conn = $em->getConnection();
        $result = [];
        $array =[];

        $sql = "SELECT direccion1,direccion2 FROM usuario where usuario= :usuario";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':usuario' => $usuario]);
        $result = $stmt->fetchAll();
        $array['array'] = $result;

// Retornando response
        return new JsonResponse(json_encode($array), JsonResponse::HTTP_OK, array(), true);
    }

    /**
     * @Rest\Post("/insertProductosdetalle", name="insertProductosdetalle")
     *
     */
    public function insertProductodeta(Request $request)
    {
        $em = $this->em;
        $idpedidos             = $request->get('idpedidos');
        $idproductos             = $request->get('idproductos');
        $cantidad             = $request->get('cantidad');
        $precio             = $request->get('precio');
        $serializer = $this->serializer;
        $objProducto = $em->getRepository(Productos::class)->findOneBy(array('idproductos' => $idproductos));
        $objPedido = $em->getRepository(Pedidos::class)->findOneBy(array('idpedidos' => $idpedidos));
        //$headers = $request->headers;
        $conn = $em->getConnection();
        try {
            $detalle = new Detallepedido();
            $detalle->setIdpedidos($objPedido);
            $detalle->setIdproductos($objProducto);
            $detalle->setCantidad($cantidad);
            $detalle->setPrecio($precio);
            $em->persist($detalle);
            $em->flush();
        } catch (\Exception $ex) {
// throw $ex;
            return new JsonResponse('[ "Se ha producido un error interno" ]', JsonResponse::HTTP_INTERNAL_SERVER_ERROR, array(), true);

        }
        $response = [
            "status" => true,
            "code" => 200,
            "message" => "creado",
            "success" => true
        ];
// Retornando response
        return new JsonResponse(json_encode('ok'), JsonResponse::HTTP_CREATED, array(), true);
    }

    /**
     * @Rest\Post("/insertProductos", name="insertProductos")
     *
     */
    public function insertProducto(Request $request)
    {
        $em = $this->em;
        //verificar las fechas
        $fechacrea             = date("Y-m-d");
        $mod_date = strtotime($fechacrea."+ 15 days");
        $usuario_add             = $request->get('usuario_add');
        $montopedido             = $request->get('montopedido');
        $dui_cliente             = $request->get('dui_cliente');
        $fecha_entrega             = date("Y-m-d",$mod_date);
        $estado=1;
        $pedidoscol=null;
        $idcampania             = $request->get('idcampania');
        $direccionentrega             = $request->get('direccionentrega');
        $idformapago             = $request->get('idformapago');



        $serializer = $this->serializer;
        $objUsuarioAdd = $em->getRepository(Usuario::class)->findOneBy(array('idusuario' => $usuario_add));
        $objCampania = $em->getRepository(Campania::class)->findOneBy(array('idcampania' => $idcampania));
        $objFormaPago = $em->getRepository(Formapago::class)->findOneBy(array('idformapago' => $idformapago));
        //$headers = $request->headers;
        $conn = $em->getConnection();
        try {
            $detalle = new Pedidos();
            $detalle->setFechacreacion($fechacrea);
            $detalle->setUsuarioAdd($objUsuarioAdd);
            $detalle->setMontopedido($montopedido);
            $detalle->setDuiCliente($dui_cliente);
            $detalle->setFechaEntrega($fecha_entrega);
            $detalle->setEstado($estado);
            $detalle->setPedidoscol($pedidoscol);
            $detalle->setIdcampania($objCampania);
            $detalle->setDireccionentrega($direccionentrega);
            $detalle->setIdformapago($objFormaPago);

            $em->persist($detalle);
            $em->flush();
        } catch (\Exception $ex) {
// throw $ex;
            return new JsonResponse('[ "Se ha producido un error interno" ]', JsonResponse::HTTP_INTERNAL_SERVER_ERROR, array(), true);

        }
        $response = [
            "status" => true,
            "code" => 200,
            "message" => "creado",
            "success" => true
        ];
// Retornando response
        return new JsonResponse(json_encode('ok'), JsonResponse::HTTP_CREATED, array(), true);
    }

    /**
     * @Rest\Get("/idmaximopedidos", name="idmaximopedidos")
     *
     */
    public function getIdmaximopedidos(Request $request)
    {
        $em = $this->em;
        $serializer = $this->serializer;
        //$headers = $request->headers;
        $conn = $em->getConnection();
        $result = [];
        $array =[];

        $sql = "select max(idpedidos) as id_ from pedidos";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();
        $result = $stmt->fetchAll();
        $array['array'] = $result;

// Retornando response
        return new JsonResponse(json_encode($array), JsonResponse::HTTP_OK, array(), true);

    }

}