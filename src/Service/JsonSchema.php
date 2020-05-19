<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\JsonSchema\translations\ConstraintErrorES;

class JsonSchema {

    private $container;
    private $requestStack;

    public function __construct(ContainerInterface $container, RequestStack $requestStack) {
        $this->container    = $container;
        $this->requestStack = $requestStack;
    }

    public function getTranslateErrors( $value, $error ) {
        $constraint = new ConstraintErrorES();
        $name       = $constraint ? $error['constraint']['name'] : '';
        $message    = $constraint ? $constraint->getMessage($name) : '';
        $errorMsg   = "";

        if( !isset( $error['property'] ) || !isset( $error['constraint'] ) ) {

            if( !isset( $error['property'] ) ) {
                throw new \Exception("Error la propiedad \"property\" del Array de Error no esta definido.");
            }

            if( !isset( $error['constraint'] ) ) {
                throw new \Exception("Error la propiedad \"constraint\" del Array de Error no esta definido.");
            }

        }

        $errorMsg = ucfirst( vsprintf( $message, array_map(function ($val) {
                if (is_scalar($val)) {
                    return $val;
                }

                return json_encode($val);
            }, array_values($error['constraint']['params']))));

        return $errorMsg;
    }
}
