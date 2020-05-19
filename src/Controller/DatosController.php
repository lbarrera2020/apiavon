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
     * @Rest\Get("/campania", name="campania")
     *
     */
    public function getCampania(Request $request)
    {
        $em = $this->em;
        $serializer = $this->serializer;
        $headers = $request->headers;
        $conn = $em->getConnection();
        $result = [];

        $sql = "SELECT codigocampania as valor FROM campania where estado= :estado";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([':estado' => 1]);
        $result = $stmt->fetchAll();



// Retornando response
        return new JsonResponse($result, JsonResponse::HTTP_OK, array(), true);


    }
}