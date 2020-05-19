# Creación y configuración de proyecto base para API REST

A continuación se decriben los pasos para la creación de un proyecto base para el desarrollo de una API utilizando:

- Symfony 4.4 en Modo Microservicio/API.
- RESTful (friendsofsymfony/rest-bundle).
- Autenticación Json Web Token (lexik/jwt-authentication-bundle).
- Open Api Specification, AKA Swagger (nelmio/api-doc-bundle).
- JsonSchema (justinrainbow/json-schema).



## Requisitos

### Software

| Software   | Versión |
| ---------- | ------- |
| Apache     | 2.4     |
| PHP        | 7.1     |
| Composer   | lastest |
| PostgreSQL | \>= 9.6 |



### Sistema Operativo

API - REST se ha desarrollado sobre el Sistema Operativo Linux, en su distribución Debian Stretch
9.x o superior, los pasos de instalación y configuración se especifican tomando como base este sistema operativo.



### Cliente API

Para el consumo de la API es necesario que cliente se conecte a través del protocolo HTTP y permita ejecutar los métodos **GET, POST, PUT, DELETE**.




## Preparación del sistema

Deberá proceder con la instalación de los paquetes necesarios dentro del sistema operativo, cumpliendo con los requisitos de software listados en el [apartado de requisitos](#requisitos). La intalación y configuración de estos paquetes y/o sistema operativo, ya sea para entorno de desarrollo o producción, no son parte del objetivo de esta guía.



## Instalar Symfony Client

Descargar Symfony Client:

```bash
wget https://get.symfony.com/cli/installer -O - | bash
```

Agregar al archivo de configuracion de la shell:

```bash
export PATH="$HOME/.symfony/bin:$PATH"
```

Para instalarlo de manera global en el sistema, ejecutar como usuario root:

```bash
mv /home/user/.symfony/bin/symfony /usr/local/bin/symfony
```

## Instalar Composer

Ejecutar como usuario normal:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
```

Para instarlo de manera global en el sistema, ejecutar como usuario root:

```bash
php composer-setup.php --install-dir=/usr/bin --filename=composer
```

## Crear proyecto en modo API/Microservice

Ejecutar como usuario normal

```bash
$ symfony new project-name
```

Ingresar al proyecto:

```bash
cd project-name
```

Instalar los siguientes paquetes, ejecutando en la raíz del proyecto las siguientes instrucciones:

    composer require symfony/apache-pack
    composer require symfony/orm-pack
    composer require symfony/maker-bundle --dev
    composer require lexik/jwt-authentication-bundle
    composer require nelmio/cors-bundle
    composer require mashape/unirest-php
    composer require symfony/swiftmailer-bundle
    composer require symfony/translation
    composer require friendsofsymfony/user-bundle

Al instalar user-bundle se producirá el siguiente error, el cual en este caso será normal:

> The child node "db_driver" at path "fos_user" must be configured.

Proceder a la configuración de FOSUserBundle.

## Configuración de FOSUserBundle

Crear la Entidad `src/Entity/User.php` la cual debe extender la la clase BaseUser de FOSUserBundle:

```php
<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

}
```

Configurar la seguridad de la aplicación, modificando el archivo `config/packages/security.yaml`:

```yaml
security:
    encoders:
        FOS\UserBundle\Model\UserInterface: bcrypt

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN

    # https://symfony.com/doc/current/security.html#where-do-users-come-from-user-providers
    providers:
        fos_userbundle:
            id: fos_user.user_provider.username

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            pattern: ^/
            form_login:
                provider: fos_userbundle
                csrf_token_generator: security.csrf.token_manager

            logout:       true
            anonymous:    true

    # Easy way to control access for large sections of your site
    # Note: Only the *first* access control that matches will be used
    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: ROLE_ADMIN }
```

Crear el archivo `config/packages/fos_user.yaml` para la configuracion del FOSUserBundle y agregar las siguientes lineas:

```yaml
fos_user:
    db_driver: orm # other valid values are 'mongodb' and 'couchdb'
    firewall_name: main
    user_class: App\Entity\User
    from_email:
        address: "yourmail@salud.gob.sv"
        sender_name: "yourmail@salud.gob.sv"
```

Modificar el archivo `config/packages/framework.yaml` para configurar el manejo de plantillas:

```yaml
framework:
    templating:
        engines: ['twig', 'php']
```

Crear el archivo `config/routes/fos_user.yaml` y agregar las siguientes lineas:

```yaml
fos_user:
    resource: "@FOSUserBundle/Resources/config/routing/all.xml"
```

Agregar en archivo `composer.json`

```json
"require": {
    "friendsofsymfony/user-bundle": "*"
}
```

Si no se tiene creada la base de datos, debe ser creada. Se debe especificar en el archivo `.env` los datos de la conexión a la BD:

```
DATABASE_URL="engine://db_user:db_password@host:port/db_name"
```

Por ejemplo:

```
DATABASE_URL="postgresql://db_user:p4ssw0rd@localhost:5432/db_name"
```

Modificar el archivo `config/packages/doctrine.yaml` para editar el driver de doctrine, quedando de la siguiente manera en el caso de postgresql:

```yaml
doctrine:
dbal:
    # configure these for your database server
    driver: 'pdo_pgsql'
    server_version: '9.6'
    charset: utf8
    default_table_options:
        charset: utf8
        collate: utf8

    url: '%env(resolve:DATABASE_URL)%'
```

Finalmente actualizar el esquema de la BD a partir de la clase User.php:

```bash
php bin/console doctrine:schema:update --force
# esto creara la tabla fos_user_user en la base de datos
```

Ejecutar el siguiente comando para verificar que todo este correctamente configurado:

```bash
composer update
```

## Finalizar la instalación de paquetes
```bash
composer require jms/serializer-bundle
composer require friendsofsymfony/rest-bundle
composer require sensio/framework-extra-bundle
composer require nelmio/api-doc-bundle
composer require symfony/asset
composer require justinrainbow/json-schema "dev-6.0.0-dev"
```

## Configuración de jwt-bundle

### Generar SSH Keys

Crear la carpeta `jwt` dentro de la carpeta `config` del proyecto:

```bash
mkdir -p config/jwt
```

Generar el certificado privado utilizando el pass phrase de su preferencia:

```bash
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
```

Generar el certificado publico utilizando el mismo pass phrase:

```bash
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

### Creación y modificación de archivos de configuración:

Configurar parámetros de JWT en el archivo `config/packages/lexik_jwt_authentication-bundle.yaml`:

```yaml
lexik_jwt_authentication:
secret_key: '%env(resolve:JWT_SECRET_KEY)%'
public_key: '%env(resolve:JWT_PUBLIC_KEY)%'
pass_phrase: '%env(JWT_PASSPHRASE)%'
token_ttl:   '%env(JWT_TOKENTTL)%'
```

Verificar en el archivo `.env` la configuracion, debe quedar de la siguiente manera:

```yaml
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_passphrase
JWT_TOKENTTL=3600
###< lexik/jwt-authentication-bundle ###
```

Modificar el archivo `config/packages/security.yaml`:

```yaml
security:
    encoders:
        App\Entity\User:
            algorithm: sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN

    # https://symfony.com/doc/current/security.html#where-do-users-come-from-user-providers
    providers:
        api_user_provider:
            entity:
                class:    App\Entity\User
                property: username

    firewalls:
        login:
            pattern:   ^/api/login
            stateless: true
            anonymous: true
            json_login:
                check_path:      /api/login_check
                success_handler: lexik_jwt_authentication.handler.authentication_success
                failure_handler: lexik_jwt_authentication.handler.authentication_failure
        api:
            pattern:   ^/api/v1
            stateless: true
            anonymous: false
            provider: api_user_provider
            guard:
                authenticators:
                    - lexik_jwt_authentication.jwt_token_authenticator
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false

    # Easy way to control access for large sections of your site
    # Note: Only the *first* access control that matches will be used
    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        #- { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        #- { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: ROLE_ADMIN }
        - { path: ^/api/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/api/login_check, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/api/v1,       roles: IS_AUTHENTICATED_FULLY }
```

## 																																																																																																																																																																																																																																																																																																																																																																																																																																																																																					Configuración general de api-doc-bundle

### Modificación de archivos de configuración

Abrir el archivo `config/packages/nelmio_api_doc.yaml` en el cual se puede especificar la información de la API y seguridad a utilizar:

```yaml
nelmio_api_doc:
        documentation:
            info:
                title: Nombre de la API
                description: Descripción de la API
                version: 1.0.0
            securityDefinitions:
                Bearer:
                    type: apiKey
                    description: 'Value: Bearer {jwt}'
                    name: Authorization
                    in: header
            security:
                - Bearer: []
        routes: # to filter documented routes
            path_patterns:
                - ^/api(?!/doc$) # Accepts routes under /api except /api/doc
```

Agregar al archivo `config/routes.yaml`:

```yaml
app.swagger_ui:
    path: /api/doc
    methods: GET
    defaults: { _controller: nelmio_api_doc.controller.swagger_ui }
```

### Personalizar Vista de SwaggerUI

Crear el archivo `templates/bundles/NelmioApiDocBundle/SwaggerUi/index.html.twig`:

```twig
{# templates/bundles/NelmioApiDocBundle/SwaggerUi/index.html.twig #}

{#
    To avoid a "reached nested level" error an exclamation mark `!` has to be added
    See https://symfony.com/blog/new-in-symfony-3-4-improved-the-overriding-of-templates
#}
{% extends '@!NelmioApiDoc/SwaggerUi/index.html.twig' %}

{% block stylesheets %}
    {{ parent() }}
    <link rel="stylesheet" href="{{ asset('css/custom-swagger-style.css') }}">
{% endblock stylesheets %}

{% block header %}
    <a id="logo-minsal" href="https://salud.gob.sv"><img src="{{ asset('minsal-white.png') }}" alt="Minsal"></a>
    <a id="logo" href="https://salud.gob.sv"><img src="{{ asset('logo.png') }}" alt="My-App"></a>
{% endblock header %}
```

Crear el archivo personalizado para css `public/css/custom-swagger-style.css`:

```css
body {
    margin-top: 100px !important;
}

header:before {
  content:"";
  background-color:#111c40;
  height:100px;
  width:100%;
  text-align:center;
  position:fixed;
  top:0;
  z-index:100;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}

header #logo {
  position:fixed;
  top : 50px;
  right:40px;
  z-index:102;
  transform:translateY(-50%);
}

header #logo img {
  height: 85px;
  background-color: #111c40;
}

header #logo-minsal {
  position:fixed;
  top : 50px;
  left:40px;
  z-index:102;
  transform:translateY(-50%);
}

header #logo-minsal img {
  height: 90px;
  background-color: #111c40;
}
```

Agregar los logos en necesarios en el directorio `public/images`:

## Creación de controlador para manejo de endpoints

Para iniciar y a manera de ejemplo, se creará el metodo para iniciar sesión. Crear el  archivo `App/ApiController.php`:

```php
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
```

## Probar la API-REST

Crear un virtual host para realizar las pruebas del proyecto.

Si se ha decidido utilizar la aplicación a traves de `docker`, puede seguir los pasos que se describen en el siguiente enlace: [Ver aquí](http://codigo.salud.gob.sv/plantillas/docker)

No olvidar que debe limpiar cache y assets del contenedor de la siguiente manera:

```bash
docker exec -ti nombre_contenedor bash -c "php bin/console cache:clear; php bin/console assets:install --symlink;"
```

Y en el archivo `.env` en la variable `DATA_BASE_URL` en el host debe especificar la dirección del host anfitrión.



##### Prueba de funcionamiento

Registrar un usuario de ejemplo por medio de la siguiente instrucción a la BD:

```sql
INSERT INTO fos_user_user (id,username,username_canonical,email,email_canonical,enabled,salt,password,last_login,confirmation_token,password_requested_at,roles) VALUES (1,'api_user','api_user','mail@salud.gob.sv','mail@salud.gob.sv',true,'yps0bgc50jWQVGhmt9LOjx0fGAAJpFKo8ARxBkVAnsk','2sZ1kRB1QgUnHFR8w8e3gU/7JxG7xb1+2mDp7vypA/uc+h2Srk0+Q+5h7AbP9gsuiU0bZfKnquPHiyJAtao/Vw==','2019-10-09 00:00:00.000',NULL,NULL,'a:0:{}');
```



Probar la API utilizando el endpoint para iniciar sesión de usuario, utilizando la uri:

`http://virtualhost/api/login_check`

*En donde:*

**virtualhost** es el virtualhost creado.



En el caso de docker, utilizar la ruta de docker por ejemplo:

`http://localhost:puerto/api/login_check`

*En donde:*

**puerto** es el número de puerto que se le dio para acceder al contenedor.



Configurar en el `header` el `Content-Type` como `application/json` y definir en el `body` de la petición:

```json
{"username" : "api_user", "password": "123"}
```

La api debera responder con el `HTTP_STATUS` 200 OK y con los siguientes datos en formato json:

```json
{ "token" : "token_generado" }
```



##### Visualizar documentación de la API

Tambien  probar desde un navegador web la ruta a la documentación de la API:

`http://virtualhost/api/doc`