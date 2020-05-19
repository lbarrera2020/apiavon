# Plantilla para API - REST

### Guía de inicio rápido



Plantilla API -REST que utiliza el Framework Symfony de PHP, el objetivo de este proyecto es facilitar y agilizar la generación de Servicios Webs ofreciendo una estructura básica.
Los pasos que se muestran a continuación, están enfocados a introducir al lector a la construcción de una API básica y sencilla, para que éste posteriormente pueda adecuarla a la complejidad de los proyectos requeridos.



## Tabla de Contenido

* [Descripción](#descripción)
* [Instalación](#instalación)
* [Estructura de directorios](#estructura-de-directorios)
* [Preparación de la base](#preparación-de-la-base)
* [Creación de la entidad](#creación-de-la-entidad)
* [Creación del repositorio](#creación-del-repositorio)
* [Creación del controlador](#creación-del-controlador)
* [Creación Métodos REST](#creación-métodos-rest)
  * [HEAD](#head)
  * [GET](#get)
  * [GET/{id}](#getid)
  * [POST](#post)
* [Documentación Swagger](#documentación-swagger)



## Descripción

Proyecto base que puede servir para el desarrollo de una API-REST, basada en:
- Symfony 4.4 en Modo Microservicio/API.
- RESTful (friendsofsymfony/rest-bundle).
- Autenticación Json Web Token (lexik/jwt-authentication-bundle).
- Open Api Specification, AKA Swagger (nelmio/api-doc-bundle).
- JsonSchema (justinrainbow/json-schema).



## Instalación

Requisitos y pasos de instalación se encuentran definidos en el archivo [**INSTALL.md**](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/INSTALL.md), seguir dicha guía para proceder con la instalación y posteriormente su uso.

Si se desea realizar su propia instalación desde cero siga la [**Guía de Instalación completa**](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/doc/guia-instalacion-completa.md).



## Estructura de directorios

A continuación se muestra la estructura de directorios del proyecto.

```
.
├── bin
│   └── console
├── composer.json
├── composer.lock
├── config
│   ├── bundles.php
│   ├── jwt
│   ├── packages
│   └── routes
├── docs
├── .env
├── .env.dist
├── INSTALL.md
├── LICENSE
├── public
│   ├── bundles
│   ├── css
│   ├── images
│   └── index.php
├── README.md
├── src
│   ├── Controller
│   ├── Entity
│   ├── JsonSchema
│   ├── Repository
│   └── Service
├── symfony.lock
├── templates
├── translations
├── var
│   ├── cache
│   └── log
└── vendor
```



De la estructura anterior se destacarán los siguientes archivos y directorios los cuales serán de importancia para el desarrollo del servicio web:

- **config:** Almacena los archivos de configuración y conexión a la base de datos.
- **log**: Como su nombre lo indica en este directorio se almacenarán todos los logs que permitirán realizar DEBUG en el caso de que existan errores en la aplicación.
- **public**: Directorio que contiene el front-controller (index.php) que se encarga de cargar todas las configuraciones del aplicativo.
- **src:** Directorio que contiene los archivos fuentes del proyecto.
  - **Controller**: Contiene los archivos en el que se declaran las rutas (Endpoint) del Servicio Web.
  - **Entity**: Contiene los archivos de clases relacionados a las tablas de la base de datos del aplicativo.
  - **JsonSchema**: Contiene los archivos de validación y traducción del json.
- **.env**: Archivo que contiene los datos sensibles de configuración como lo son credenciales a la base de datos, etc., este archivo debe ser creado a partir del **`.env.dist`**, este archivo se omite en el gitignore.



## Preparación de la base

En este apartado se asume que el lector tiene creada y configurada una base de datos según la guía de instalación. En la base de datos crear una tabla con el nombre de **libro** y cuya estructura sea similar a la siguiente:

```
            Tabla «libro»
      Columna      | Tipo   | Nullable
-------------------+--------+----------
 id                | serial | not null
 isbn              | text   |
 descripcion       | text   |
 autor             | text   |
 fecha_publicacion | date   |
Índices:
    "pk_libro" PRIMARY KEY (id)
```



**Datos de ejemplo.**

Cargar el archivo **CSV** [**libros.csv**](https://next.salud.gob.sv/index.php/s/xb6R8qnF6tXiZjG/download) en la tabla `libro` el cual contiene datos de ejemplo que se requerirán para el desarrollo de esta guía.



## Creación de la entidad

Antes de comenzar con la creación del controlador es necesario la creación de la entidad con la ayuda de doctrine ejecutando los siguientes comandos:

```bash
# Creación de la metadata xml de la entidad
php bin/console doctrine:mapping:import "App\Entity" xml --path=config/doctrine --filter="Libro"
# Creación de la entidad
php bin/console doctrine:mapping:import "App\Entity" annotation --path=src/Entity --filter="Libro"
# Generando Getters y Setters
php bin/console make:entity --regenerate App
```



Posterior a la creación de la entidad, editar el archivo `Libro.php` que se  encuentra en el directorio `src/Entity` y agregar al final de la clase la función `__toString` tal y como se muestra a continuación:



**Libro.php**

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Libro
 *
 * @ORM\Table(name="libro")
 * @ORM\Entity
 */
class Libro
{
    // codigo...

    public function __toString()
    {
        return $this->id ? $this->isbn.' - '.$this->autor : '';
    }
}
```

Para más información visitar la documentación oficial de [Doctrine en Symfony](https://symfony.com/doc/current/doctrine/reverse_engineering.html)



## Creación del Repositorio

El repositorio permite realizar búsquedas de manera más sencilla a través de los campos de la entidad, para crear el repositorio es necesario editar el archivo `Libro.php` que se encuentra dentro del directorio `src/Entity`, quedando el archivo de la siguiente manera:



**Libro.php**

```php
<?php
//src/Entity/Libro.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Libro
 *
 * @ORM\Table(name="libro")
 * @ORM\Entity(repositoryClass="App\Repository\LibroRepository")
 */
class Libro
{
    // codigo...
}
```



Y crear el archivo `LibroRepository` dentro del directorio `src/Repository`, para ello es necesario ejecutar el siguiente comando:

```bash
php bin/console make:entity --regenerate App
```

Para más información ver la documentación oficial de [Doctrine y Symfony](https://symfony.com/doc/3.4/doctrine/repository.html)



## Creación del controlador

Antes de comenzar con la creación de los métodos es necesario la creación del controlador que los contendrá. Para ello se recomienda crear un controlador por cada recurso que se ha de poner a disposición, para esta guía se debe de crear el controlador llamado `LibroController.php` dentro del directorio  `src/Controller` de la plantilla, dicho archivo debe de tener el siguiente contenido:



**LibroController.php**

```php
<?php
// src/Controller/LibroController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;
use Swagger\Annotations as SWG;
use App\Service\JsonSchema;
use App\Service\JsonSchemaService;
use \JsonSchema\Validator AS JsonValidator;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Libro;

/**
 * Class LibroController
 *
 * @Route("/api/v1")
 */
class LibroController extends AbstractController
{
    private $serializer;
    private $em;
    private $security;

    public function __construct( SerializerInterface $serializer, EntityManagerInterface $em, Security $security)
    {
        $this->serializer = $serializer;
        $this->em         = $em;
        $this->security   = $security;
    }

    // codigo...
}
```



## Creación Métodos REST

Una vez ya configurado y ejecutándose la app, lo siguiente es crear los **endpoints** o métodos del servicio web que permitirá la interacción con el cliente, basándose en los [Estándares de Desarrollo de Servicios Web](https://github.com/klb-rodriguez/EstandaresInteroperabilidad/blob/master/Desarrollo.md) desarrollados por diferentes instituciones en coordinación con Gobierno Electrónico de El Salvador.



### HEAD

Endpoint que permite verificar si el recurso está disponible.

versión: **v1**

uri: **/libros**

dato de respuesta: **Vacío **

Editar el archivo `LibroController.php` que se encuentra dentro del directorio `src/Controller` y agregar el código que se lista a continuación:



**LibroController.php**

```php
<?php
// src/Controller/LibroController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// codigo...

/**
 * Class LibroController
 *
 * @Route("/api/v1")
 */
class LibroController extends AbstractController
{
    // codigo...

    /**
     * @Rest\Head("/libros", name="head_libros")
     *
     * @SWG\Response(
     *     response=200,
     *     description="La petición ha sido procesada exitosamente."
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Acceso no autorizado.",
     *     @SWG\Schema(
     *         type="object",
     *         {
     *             @SWG\Property(
     *                 property="code",
     *                 type="integer",
     *                 description="HTTP Status Code."
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Descripción del error."
     *             )
     *         },
     *     )
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="Se ha producido un error interno."
     * )
     *
     * @SWG\Tag(name="Libro")
     */
    public function headLibros() {
        // Retornando response sin cuerpo
        return new Response( null, Response::HTTP_OK, array('Content-type' => 'application/json'), true );
    }
}
```

**Ejemplo de consumo**

```bash
curl -X HEAD "http://dominio/api/v1/libros" -H "accept: application/json" -H "Authorization: Bearer token"
```

**Resultado:**

**Response**: 200 Ok

> Este servicio no retorna cuerpo de respuesta.



### GET

Endpoint que permite listar todos los libros según los parámetros de búsqueda proporcionados.

versión: **v1**

uri: **/libros**

dato de respuesta: **JSON**

Editar el archivo `LibroController.php` que se encuentra dentro del directorio `src/Controller` y agregar el código que se lista a continuación:



**LibroController.php**

```php
<?php
// src/Controller/LibroController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// codigo...

/**
 * Class LibroController
 *
 * @Route("/api/v1")
 */
class LibroController extends AbstractController
{
    // codigo...

    /**
     * @Rest\Get("/libros", name="get_libros")
     *
     * @SWG\Parameter(
     *     name="isbn",
     *     in="query",
     *     type="string",
     *     description="Número estandar internacional"
     * )
     * @SWG\Parameter(
     *     name="autor",
     *     in="query",
     *     type="string",
     *     description="Nombre del autor"
     * )
     * @SWG\Parameter(
     *     name="fecha_publicacion",
     *     in="query",
     *     type="string",
     *     format="date",
     *     description="Fecha de publicación"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="La petición ha sido procesada exitosamente, y se retornan los libros que coinciden con los parámetros de búsqueda",
     *     @SWG\Schema(
     *         type= "array",
     *         @SWG\Items(
     *             @SWG\Property(
     *                 property="isbn",
     *                 type="string",
     *                 description="Número estandar internacional"
     *             ),
     *             @SWG\Property(
     *                 property="autor",
     *                 type="string",
     *                 description="Nombre del autor."
     *             ),
     *             @SWG\Property(
     *                 property="descripcion",
     *                 type="string",
     *                 description="Descripción"
     *             ),
     *             @SWG\Property(
     *                 property="fecha_publicacion",
     *                 type="string",
     *                 format="date",
     *                 description="Fecha de publicación"
     *             )
     *         )
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Se han encontrado errores en la petición."
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Acceso no autorizado.",
     *     @SWG\Schema(
     *         type="object",
     *         {
     *             @SWG\Property(
     *                 property="code",
     *                 type="integer",
     *                 description="HTTP Status Code."
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Descripción del error."
     *             )
     *         },
     *     )
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="Se ha producido un error interno."
     * )
     *
     * @SWG\Tag(name="Libro")
     */
    public function getLibros(Request $request) {
        // Inicialización de variables
        $em         = $this->em;
        $serializer = $this->serializer;
        $datos      = '[]';
        $where      = array();

         // Obteniendo parámetros de búsqueda
        $isbn             = $request->get('isbn');
        $autor            = $request->get('autor');
        $fechaPublicacion = $request->get('fecha_publicacion');

        // creando el objeto de la fecha
        $fechaPublicacion = $fechaPublicacion ? \DateTime::createFromFormat('Y-m-d', $fechaPublicacion) : null;

        // verificando que al menos un parametro de busqueda sea proporcionado
        if( $isbn || $autor || $fechaPublicacion ) {
            // preparando los parámetros de búsqueda
            if( $isbn )
                $where = array_merge( $where, array( "isbn" => $isbn ) );

            if( $autor )
                $where = array_merge( $where, array( "autor" => $autor ) );

            if( $fechaPublicacion )
                $where = array_merge( $where, array( "fechaPublicacion" => $fechaPublicacion ) );

            // realizando la búsqueda en la base de datos a través del repository
            $result = $em->getRepository(Libro::class)->findBy( $where );

            if( $result ) {
                // Convirtiendo el resultado de objetos a jsons
                $datos = $serializer->serialize($result, 'json');
            }
        } else {
            // Retornando error
            return new JsonResponse( '[ "Se han encontrado errores en la petición" ]', JsonResponse::HTTP_BAD_REQUEST, array(), true );
        }

        // Retornando response
        return new JsonResponse( $datos, JsonResponse::HTTP_OK, array(), true );
    }
}
```

**Ejemplo de consumo**

```bash
curl -X GET "http://dominio/api/v1/libros?isbn=9780530239033" -H "accept: application/json" -H "Authorization: Bearer token"
```

**Resultado:**

**Response**: 200 Ok

```json
[
  {
    "id": 1,
    "isbn": "9780530239033",
    "descripcion": "Iste modi accusantium autem suscipit quia et et dolorum.",
    "autor": "Roslyn Morissette",
    "fecha_publicacion": "2002-09-08T00:00:00-06:00"
  }
]
```



### GET/{id}

Endpoint que permite obtener un libro, para este método es requerido proporcionar el id o llave del libro que se desea obtener, que para este ejemplo se usará el isbn, debido a que es un código internacional estandarizado.

versión: **v1**

uri: **/libros/{id}**

dato de respuesta: **JSON**



Editar el archivo `LibroController.php` que se encuentra dentro del directorio `src/Controller` y agregar el código que se lista a continuación:



**LibroController.php**

```php
<?php
// src/Controller/LibroController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// codigo...

/**
 * Class LibroController
 *
 * @Route("/api/v1")
 */
class LibroController extends AbstractController
{
    // codigo...

    /**
     * @Rest\Get("/libros/{id}", name="get_libro")
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="Número estandar internacional (isbn)"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="La petición ha sido procesada exitosamente, y se retornan el libro que coincide con el id proporcionado",
     *     @SWG\Schema(
     *         type="object",
     *         {
     *             @SWG\Property(
     *                 property="isbn",
     *                 type="string",
     *                 description="Número estandar internacional"
     *             ),
     *             @SWG\Property(
     *                 property="autor",
     *                 type="string",
     *                 description="Nombre del autor."
     *             ),
     *             @SWG\Property(
     *                 property="descripcion",
     *                 type="string",
     *                 description="Descripción"
     *             ),
     *             @SWG\Property(
     *                 property="fecha_publicacion",
     *                 type="string",
     *                 format="date",
     *                 description="Fecha de publicación"
     *             )
     *         }
     *     )
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Acceso no autorizado.",
     *     @SWG\Schema(
     *         type="object",
     *         {
     *             @SWG\Property(
     *                 property="code",
     *                 type="integer",
     *                 description="HTTP Status Code."
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Descripción del error."
     *             )
     *         }
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Recurso no encontrado."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="Se ha producido un error interno."
     * )
     *
     * @SWG\Tag(name="Libro")
     */
    public function getLibro(string $id, Request $request) {
        // Inicialización de variables
        $em         = $this->em;
        $serializer = $this->serializer;

        // Obteniendo el isbn de la uri
        $isbn = $id;

        // realizando la búsqueda en la base de datos a través del repository
        $result = $em->getRepository(Libro::class)->findBy( [ 'isbn' => $isbn ] );

        if( $result ) {
            // Convirtiendo el resultado de objetos a jsons
            $datos = $serializer->serialize($result, 'json');
        } else {
            // no se encontro el recurso
            return new JsonResponse( '[ "Recurso no encontrado" ]', JsonResponse::HTTP_NOT_FOUND, array(), true );
        }

        // Retornando response
        return new JsonResponse( $datos, JsonResponse::HTTP_OK, array(), true );
    }
}
```

**Ejemplo de consumo**

```bash
curl -X GET "http://dominio/api/v1/libros/9780530239033" -H "accept: application/json" -H "Authorization: Bearer token"
```

**Resultado:**

**Response**: 200 Ok

```json
{
    "id": 1,
    "isbn": "9780530239033",
    "descripcion": "Iste modi accusantium autem suscipit quia et et dolorum.",
    "autor": "Roslyn Morissette",
    "fecha_publicacion": "2002-09-08 00:00:00"
}
```



### POST

Enpoint que permite insertar uno o más libros a la base a través del servicio web.

versión: **v1**

uri: **/libros**

datos de entrada: **Array de JSONs**

dato de respuesta: **JSON**



En el directorio `src/JsonSchema` crear un directorio si no existiera llamado `schemas`, y además crear un archivo llamado **libro.json** el cual debe de contener las restricciones JSON que se han de utilizar para validar los datos entrantes similar al siguiente código:



**libro.json**

```json
{
    "$schema": "http://json-schema.org/draft-06/schema#",
    "title": "Libro",
    "definitions": {
        "stringNoBlank": {
            "type": "string",
            "minLength": 1
        },
        "stringOptional": {
            "type": ["string", "null"],
            "minLength": 1
        },
        "dateRequired": {
            "type": "string",
            "format": "date"
        }
    },
    "required": [ "isbn", "autor", "fecha_publicacion"],
    "additionalProperties": false,
    "properties": {
        "isbn": {
            "$ref": "#/definitions/stringNoBlank"
        },
        "autor": {
            "$ref": "#/definitions/stringNoBlank"
        },
        "descripcion": {
            "$ref": "#/definitions/stringOptional"
        },
        "fecha_publicacion": {
            "$ref": "#/definitions/dateRequired"
        }
    }
}
```

Para más información ver la documentación oficial de [JsonSchema](https://json-schema.org/learn/getting-started-step-by-step.html)



Editar el archivo `LibroController.php` que se encuentra dentro del directorio `src/Controller`, asegurarse de haber importado todas las librerías que se definieron en la sección de [Creación del controlador](#creación-del-controlador) de esta guía. Agregar el código que se lista a continuación:



**LibroController.php**

```php
<?php
// src/Controller/LibroController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// codigo...

/**
 * Class LibroController
 *
 * @Route("/api/v1")
 */
class LibroController extends AbstractController
{
    /**
     * @Rest\Post("/libros", name="post_libros")
     *
     * @SWG\Parameter(
     *     name="json",
     *     in="body",
     *     description="Json que contiene los datos del libro",
     *     @SWG\Schema(
     *         type="object",
     *         {
     *             @SWG\Property(
     *                 property="isbn",
     *                 type="string",
     *                 description="Número estandar internacional"
     *             ),
     *             @SWG\Property(
     *                 property="autor",
     *                 type="string",
     *                 description="Nombre del autor."
     *             ),
     *             @SWG\Property(
     *                 property="descripcion",
     *                 type="string",
     *                 description="Descripción"
     *             ),
     *             @SWG\Property(
     *                 property="fecha_publicacion",
     *                 type="string",
     *                 format="date",
     *                 description="Fecha de publicación"
     *             )
     *         }
     *     )
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Recurso creado exitosamente"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Entrada no válida.",
     *     @SWG\Schema(
     *         type= "object",
     *         {
     *             @SWG\Property(
     *                 property="status",
     *                 type="boolean",
     *                 description="Estado de la petición."
     *             ),
     *             @SWG\Property(
     *                 property="code",
     *                 type="string",
     *                 description="HTTP Status Code."
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Mensaje de descripción/error general."
     *             ),
     *             @SWG\Property(
     *                 property="errors",
     *                 type="array",
     *                 description="Detalle de errores.",
     *                 @SWG\Items(
     *                     @SWG\Property(
     *                         property="propiedad",
     *                         type="string",
     *                         description="Propiedad asociada al error. Si es null aplica sobre el objeto completo."
     *                     ),
     *                     @SWG\Property(
     *                         property="error",
     *                         type="string",
     *                         description="Descripción del error."
     *                     )
     *                 )
     *             )
     *         }
     *     )
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Acceso no autorizado.",
     *     @SWG\Schema(
     *         type="object",
     *         {
     *             @SWG\Property(
     *                 property="code",
     *                 type="integer",
     *                 description="HTTP Status Code."
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Descripción del error."
     *             )
     *         }
     *     )
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="Se ha producido un error interno."
     * )
     *
     * @SWG\Tag(name="Libro")
     */
    public function postLibros(Request $request, JsonSchema $jsonService) {
        // Inicialización de variables
        $em         = $this->em;
        $serializer = $this->serializer;
        $headers    = $request->headers;

        // Validando los encabezados requeridos
        if( !$headers->has('content-type') ) {
            // Error en la petición
            $response = [
                "status"  => true,
                "code"    => 400,
                "message" => "Se han econtrado errores en la petición",
                "errors"  => [
                    "propieda" => "Content-type",
                    "error" => "El parámetro Content-type es requerido"
                ]
            ];

            return new JsonResponse( json_encode( $response ), JsonResponse::HTTP_BAD_REQUEST, array(), true );
        }

        // obteniendo el contenido del cuerpo en formato string
        $json = $request->getContent();

        // Convirtiendo el json a objeto
        try {
            $jsonObject = json_decode($json);
            $json       = json_decode($json, true);
        } catch(\Exception $ex) {
            // Error en la petición
            $response = [
                "status"  => true,
                "code"    => 400,
                "message" => "El json proporcionado posee una estructura no válida",
                "errors"  => []
            ];

            return new JsonResponse( json_encode( $response ), JsonResponse::HTTP_BAD_REQUEST, array(), true );
        }

        try {
            // Obteniendo el JSON SCHEMA
            // Url del Json Schema original
            $schema = json_decode( file_get_contents( __DIR__.'/../JsonSchema/schemas/libro.json' ) );
        } catch (\Exception $ex) {
            throw $ex;
            return new JsonResponse( '[ "Se ha producido un error interno" ]', JsonResponse::HTTP_INTERNAL_SERVER_ERROR, array(), true );
        }

        $errorArray = array();
        $validator  = new JsonValidator();

        // Validando el json con el schema
        $validator->validate( $jsonObject, $schema );

        if( $validator->isValid() === false ) {
            foreach ($validator->getErrors() as $error) {
                $errorArray[] = array( 'propiedad' => $error['property'], 'error' => $jsonService->getTranslateErrors( null, $error ) );
            }

            $result = array(
                'status'  => 'true',
                'code'    => 400,
                'message' => 'Se han encontrado errores en la peticion.',
                'errors'  => $errorArray
            );

            // Mensaje para la bitacora
            return new JsonResponse( json_encode( $result ), JsonResponse::HTTP_BAD_REQUEST, array(), true );
        }

        // definiendo el campo opcional en el json
        $descripcion = array_key_exists( 'descripcion', $json ) ? $json['descripcion'] : null;
        // preparando la fecha de publicacion
        $fechaPublicacion = \DateTime::createFromFormat( 'Y-m-d', $json['fecha_publicacion'] );

        try {
            $Libro = new Libro();
            // asignado los valores a los campos de la base
            $Libro->setIsbn( $json['isbn'] );
            $Libro->setAutor( $json['autor'] );
            $Libro->setDescripcion( $descripcion );
            $Libro->setFechaPublicacion( $fechaPublicacion );

            // persistiendo el objeto a la base de datos
            $em->persist($Libro);
            $em->flush();
        } catch(\Exception $ex) {
            throw $ex;
            return new JsonResponse( '[ "Se ha producido un error interno" ]', JsonResponse::HTTP_INTERNAL_SERVER_ERROR, array(), true );
        }

        return new JsonResponse( '[ "Recurso creado" ]', JsonResponse::HTTP_CREATED, array(), true );
    }
}
```

**Ejemplo de consumo**

json a enviar:

```json
{
    "isbn": "9782575801305",
    "descripcion": "Ab necessitatibus exercitationem nemo et expedita culpa. Mollitia et veniam eaque et recusandae. Qui tenetur aut perspiciatis molestias sed dicta.",
    "autor": "Mr. Odell Schuster V",
    "fecha_publicacion": "1990-02-18"
}
```

Consumo del endpoint:

```bash
curl -X POST "http://dominio/api/v1/libros" -H "accept: application/json" -H "Authorization: Bearer token" -H "Content-Type: application/json" -d '{ "isbn": "9782575801305", "descripcion": "Ab necessitatibus exercitationem nemo et expedita culpa. Mollitia et veniam eaque et recusandae. Qui tenetur aut perspiciatis molestias sed dicta.", "autor": "Mr. Odell Schuster V", "fecha_publicacion": "1990-02-18"}'
```

**Resultado:**

**Response**: 201 Created

```json
["Recurso creado"]
```



## Documentación Swagger

Gracias a la incorporación de `nelmio/api-doc-bundle` que permite integrar `swagger` en un proyecto de `symfony` una vez configurado los endpoints antes listados podremos tener automáticamente la documentación de estos servicios tal y como se muestra a continuación:



![libro_swagger](https://next.salud.gob.sv/index.php/s/FxzC7Diq6ybRPoZ/preview)



Para acceder a esta documentación ingresar a: **http://dominio/api/doc**



Para más información acerca de la librería ver la documentación oficial de [**nelmio/api-doc-bundle**](https://symfony.com/doc/current/bundles/NelmioApiDocBundle/index.html) y [**swagger**](https://swagger.io/docs/specification/about/)