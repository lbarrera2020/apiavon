<?php

namespace App\Controller;

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

/**
 * Class ApiController
 *
 * @Route("/api")
 */
class ApiController extends AbstractFOSRestController
{
    private $serializer;
    private $kernel;
    private $connection;
    private $security;

    public function __construct( SerializerInterface $serializer, KernelInterface $kernel, Connection $connection, Security $security)
    {
        $this->serializer = $serializer;
        $this->kernel     = $kernel;
        $this->connection = $connection;
        $this->security   = $security;
    }

    /**
     * @Rest\Post("/login_check", name="user_login_check")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Se ha iniciado sesión exitosamente.",
     *     @SWG\Schema(
     *         type= "object",
     *         {
     *             @SWG\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Bearer token"
     *             )
     *         },
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Se han encontrado errores en la petición. Debe proveer Usuario y Contraseña."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="No se ha podido realizar el inicio de sesión."
     * )
     *
     * @SWG\Parameter(
     *     name="data",
     *     in="body",
     *     type="object",
     *     description="Nombre de usuario y password para autenticación.",
     *     schema={
     *         "type": "object",
     *         "properties": {
     *             "username": {
     *                 "type": "string",
     *                 "description": "Nombre de usuario"
     *             },
     *             "password": {
     *                 "type": "string",
     *                 "description": "Contraseña"
     *             }
     *         },
     *         "required": {"username", "password"}
     *     }
     * )
     *
     * @SWG\Tag(name="Usuario")
     */
    public function getLoginCheckAction() {

    }
}