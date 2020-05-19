# Plantilla para API - REST
A continuación se muestra la guía para la instalación de la plantilla para una API-REST, basada en:
- Symfony 4.4 en Modo Microservicio/API.
- RESTful (friendsofsymfony/rest-bundle).
- Autenticación Json Web Token (lexik/jwt-authentication-bundle).
- Open Api Specification, AKA Swagger (nelmio/api-doc-bundle).
- JsonSchema (justinrainbow/json-schema).



## Contenido

* [Requisitos](#requisitos)
  * [Software](#1-software)
  * [Sistema Operativo](#2-sistema-operativo)
  * [Cliente API](#3-cliente-api)
* [Instalación del Sistema](#instalación-de-la-aplicación)
  * [Preparación del servidor](#1-preparación-del-servidor)
    * [Configuración de repositorios Debian](#11-configuración-de-repositorios-debian)
    * [Instalación de Docker](#12-instalación-de-docker)
    * [Instalación de Docker-Compose](#13-instalación-de-docker-compose)
  * [Instalación y configuración de la API](#2-instalación-y-configuración-de-la-api)
    * [Clonación del proyecto](#21-clonación-del-proyecto)
    * [Instalación de vendors](#22-instalación-de-vendors)
    * [Configurar la conexión a la base de datos](#23-configurar-las-conexiones-a-la-base-de-datos)
    * [Creación del esquema](#24-creación-del-esquema)
    * [Generar SSH Keys](#25-generar-ssh-keys)
    * [Limpiar cache y assets](#26-limpiar-cache-y-assets)
    * [Creación del usuario inicial](#27-creación-del-usuario-inicial)
    * [Creación del VirtualHost](#28-creación-del-virtualhost)
    * [Pruebas de acceso a la api](#29-pruebas-de-acceso-a-la-api)



## Requisitos



### 1 Software

| Software   | Versión |
| ---------- | ------- |
| Apache     | 2.4     |
| PHP        | 7.1     |
| Composer   | lastest |
| PostgreSQL | \>= 9.6 |



### 2 Sistema Operativo

API - REST se ha desarrollado sobre el Sistema Operativo Linux, en su distribución Debian Stretch
9.x o superior, los pasos de instalación y configuración se especifican tomando como base este sistema operativo.



### 3 Cliente API

Para el consumo de la API es necesario que cliente se conecte a través del protocolo HTTP y permita ejecutar los métodos **GET, POST, PUT, DELETE**.




## Instalación de la aplicación

A continuación se listan los pasos de descarga e instalación de la API - REST:



### 1 Preparación del servidor

A continuación se listan los pasos para la preparación del servidor para entorno de desarrollo o producción.

La API - REST puede ser instalado directamente dentro del sistema operativo cumpliendo con los requisitos de software listados en el [apartado 1](#1-software) o puede configurarse a través de docker.



#### 1.1 Configuración de repositorios Debian

Antes de comenzar con la instalación es necesario configurar los repositorios de los cuales se obtendrán los paquetes a instalar, cabe aclarar que si ya se posee repositorios configurados se puede omitir esta sección. Los pasos para la configuración de los repositorios son los siguientes:

1. Abrir una terminal e identificarse como usuario root:

```bash
su             # Presionar Enter
Contraseña:    # Ingresar Contraseña
```

2. Editar el archivo sources.list que se encuentra dentro del directorio `/etc/apt/`:

```bash
vim /etc/apt/sources.list # alternativa a vim: nano
```

Agregar los repositorios que se listan a continuación:

```bash
##Repositorios del MINSAL para debian stretch.
deb http://debian.salud.gob.sv/debian/ stretch main contrib non-free
deb-src http://debian.salud.gob.sv/debian/ stretch main contrib non-free

deb http://debian.salud.gob.sv/debian/ stretch-updates main contrib non-free
deb-src http://debian.salud.gob.sv/debian/ stretch-updates main contrib non-free

deb http://debian.salud.gob.sv/debian-security/ stretch/updates main contrib non-free
deb-src http://debian.salud.gob.sv/debian-security/ stretch/updates main contrib non-free
```

3. Actualizar la lista de paquetes de los repositorios.

```bash
aptitude update
```

Una vez hecho lo anterior ya se tienen los repositorios actualizados y con ello se puede proceder con la instalación de los paquetes necesarios para la la instalación de la API - REST.



#### 1.2 Instalación de Docker

Los pasos de instalación de Docker se describen en el siguiente enlace: [**Click aquí**](https://docs.docker.com/install/linux/docker-ce/debian/),

> Se recomienda la configuración del uso de Docker como usuario no root en Linux: [**Click aqui**](https://docs.docker.com/install/linux/linux-postinstall/)



#### 1.3 Instalación de Docker-Compose

Los pasos de instalación de Docker-compose se describen en el siguiente enlace: [**Click aquí**](https://docs.docker.com/compose/install/).



### 2 Instalación y configuración de la API

A continuación se listan los pasos para la instalación y configuración de la API-REST:



#### 2.1 Clonación del proyecto

Clonar el proyecto desde los repositorios oficiales ejecutando el siguiente comando:

```bash
git clone git@codigo.salud.gob.sv:plantillas/api-rest.git
```



#### 2.2 Instalación de vendors

Descargar los vendors en el directorio raíz del proyecto ejecutando el siguiete comando:

```bash
wget https://next.salud.gob.sv/index.php/s/wZkc9XJP5xZZaa7/download -O vendors.tar.gz
```

Descomprimir los vendors descargados ejecutando el siguiente comando:

```
tar xzvf vendors.tar.gz
```

Instalar los vendors ejecutando el siguiente comando:

```bash
composer install
```



#### 2.3 Configurar la conexión a la base de datos

Si no se tiene creada la base de datos, debe ser creada. Se debe especificar en el archivo `.env` los datos de la conexión a la BD:



Crear del archivo **.env** desde el directorio raíz del proyecto ejecutando el siguiente comando

```bash
cp .env.dist .env
```

Editar la siguiente línea del archivo **.env**

```
DATABASE_URL="engine://db_user:db_password@host:port/db_name"
```

Por ejemplo:

```
DATABASE_URL="postgresql://db_user:p4ssw0rd@localhost:5432/db_name"
```



#### 2.4 Creación del esquema

Si no se posee la estructura de tablas de la base de datos se puede crear las tablas a partir de las entidades de la API ejecutando el comando que se lista a continuación, si ya se posee esquema se puede omitir este paso.

```bash
php bin/console doctrine:schema:update --force
# Esto creará la tabla fos_user_user
```



#### 2.5 Generar SSH Keys

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

Editar en el archivo `.env` y reemplazar **passphrase**  por la asignada en la llave privada de los comandos anteriores:

```yaml
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=passphrase
JWT_TOKENTTL=3600
###< lexik/jwt-authentication-bundle ###
```

Dar permisos de lectura al archivo `config/jwt/private.pem` ejecutando el siguiente comando:

```
chmod 755 config/jwt/*.pem
```



#### 2.6 Limpiar cache y assets

```bash
php bin/console cache:clear; php bin/console cache:clear --env=prod; php bin/console assets:install --symlink; php bin/console assets:install --symlink --env=prod
```



#### 2.7 Creación del usuario Inicial

Para poder iniciar sesion en la API es necesario registrar los usuarios, para poder registrar un usuario, es necesario ejecutar el siguiente comando:

```bash
# Comando de creación de usuario
php bin/console fos:user:create
# Información que solicita el comando
Please choose a username:admin
Please choose an email:mail@email.com
Please choose a password:
Created user admin

```

En donde:

* **admin:** Es el nombre de usuario que se ha de crear.
* **email:** Correo electrónico del usuario.
* **password:** Contraseña a asignar al usuario



#### 2.8 Creación del VirtualHost

Para probar la API se recomienda la creación del VirtualHost para realizar las pruebas del proyecto o puede utilizarse la API a través de Docker.

Si se ha decidido utilizar la aplicación a traves de **`Docker`**, puede seguir los pasos que se describen en el siguiente enlace: [Ver aquí](http://codigo.salud.gob.sv/plantillas/docker)



#### 2.9 Pruebas de acceso a la API

Una vez realizado los pasos anteriores puede realizar las pruebas de acceso, para ello puede acceder a la documentación de la **[forma de uso](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/README.md#forma-de-uso)** del **[README.md](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/README.md)**
