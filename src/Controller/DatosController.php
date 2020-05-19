<?php

namespace App\Controller;

use App\Entity\Campania;
use App\Entity\Categorias;
use App\Entity\User;
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
     * @Rest\Get("/validarUsuario", name="validarUsuario")
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

        $sql = "SELECT * FROM usuario WHERE usuario= : usu_usuario AND clave= :usu_password";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':usu_usuario' => $usuario,':usu_password' =>$clave]);
        $result = $stmt->fetchAll();
        $array['array'] = $result;

// Retornando response
        return new JsonResponse(json_encode($array), JsonResponse::HTTP_OK, array(), true);


    }
}