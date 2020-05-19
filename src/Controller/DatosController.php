<?php

namespace App\Controller;

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

    public function __construct(SerializerInterface $serializer,EntityManagerInterface $em, KernelInterface $kernel, Connection $connection, Security $security)
    {
        $this->serializer = $serializer;
        $this->kernel = $kernel;
        $this->em         = $em;
        $this->connection = $connection;
        $this->security = $security;
    }

    /**
     * @Rest\Get("/categorias", name="categorias")
     *
     */
    public function getLibros(Request $request)
    {
        // Inicialización de variables
        $em = $this->em;
        $serializer = $this->serializer;
        $datos = '[]';
        $result = $em->getRepository(Categorias::class)->findAll();

        if ($result) {
            // Convirtiendo el resultado de objetos a jsons
            $datos = $serializer->serialize($result, 'json');
        }


// Retornando response
        return new JsonResponse($datos, JsonResponse::HTTP_OK, array(), true);


    }
}