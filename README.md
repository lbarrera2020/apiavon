# Plantilla para API - REST

### Ministerio de Salud de El Salvador (MINSAL)



<div align="center">
	<a href="http://codigo.salud.gob.sv/plantillas/api-rest">
		<img alt="SUIS" title="SUIS" src="https://next.salud.gob.sv/index.php/s/ykrrMGLGfBxEBGE/preview" width="250" style="width: 250px;">
	</a>
</div>

## Tabla de Contenido

* [Descripción](#descripción)
* [Instalación](#instalación)
* [Forma de uso](#forma-de-uso)
* [Primeros pasos](#primeros-pasos)
* [Colaboradores](#colaboradores)
* [Enlaces de ayuda](#enlaces-de-ayuda)
* [Licencia](#licencia)



## Descripción

Proyecto base que puede servir para el desarrollo de una API-REST, basada en:
- Symfony 4.4 en Modo Microservicio/API.
- RESTful (friendsofsymfony/rest-bundle).
- Autenticación Json Web Token (lexik/jwt-authentication-bundle).
- Open Api Specification, AKA Swagger (nelmio/api-doc-bundle).
- JsonSchema (justinrainbow/json-schema).

El objetivo de este proyecto es facilitar y agilizar la generación de Servicios Web del MINSAL, ofreciendo una estructura básica para iniciar un proyeto nuevo.


## Instalación

Requisitos y pasos de instalación se encuentran definidos en el archivo [**INSTALL.md**](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/INSTALL.md), seguir dicha guía para proceder con la instalación y posteriormente su uso.

Si se desea realizar su propia instalación desde cero siga la [**Guía de Instalación completa**](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/doc/guia-instalacion-completa.md).



## Forma de uso

La plantilla de APIs utiliza la arquitectura de comunicación REST-FULL y tiene a disposición los siguientes endpoints:



### Documentación

Gracias a la integración de Open Api Specification (Swagger)  la plantilla de API pone a disposición del cliente el listado de servicios disponibles (Endpoints) los cuales pueden ser consumidos a través de un cliente REST o del Navegador Web.



**Cliente REST**

```bash
curl -X GET -H "Accept: application/json" http://dominio/api/doc.json
```



**Navegador Web**

Ingresar a: http://dominio/api/doc, y se mostrará una pantalla como la siguiente:

![imagen-documentacion](https://next.salud.gob.sv/index.php/s/c5w2QcwfZ9f757s/preview)



### Autenticación

El método de autenticación definido e integrado a la plantilla de desarrollo de APIs es **`JWT`** el cuál utiliza un token para toda la comunicación que se realiza a través de los endpoints que han sido asegurados.



Método:         **`POST`**

URI:                 **`/api/login_check`**



**Headers:**

| Parámetro                     | Descripción |
| ----------------------------: | ----------- |
| `Content-type` <br />_semi-opcional_ | Parámetro que le indica al servidor que tipo de contenido es enviado, valor a enviar: `application/json` |



**Query String:**

> No se requiere ningún parámetro de búsqueda.



**Body: **

Formato: **`JSON`**

| Parámetro                     | Descripción |
| ----------------------------: | ----------- |
| `username` <br />_requerido_ | Nombre de usuario con el que se iniciará sesión para obtener el token. |
| `password` <br />_requerido_ | Contraseña con el que se iniciará sesión para obtener el token. |

```json
{
    "username": "username",
    "password": "passwrod"
}
```



**Response:**

```
HTTP 200 OK
```
```json
{
    "token": "string"
}
```



**Códigos de respuesta:**

|                           Código | Descripción                                                  |
| -------------------------------: | ------------------------------------------------------------ |
|                    `200`<br />OK | Implica que la petición fue completada exitosamente          |
|           `400`<br />Bad Request | Implica que hubo un error en la petición, <br />esto puede darse debido a que alguno de los parámetros<br />requeridos de Encabezado o Query String no ha sido proporcionado. |
|          `401`<br />Unauthorized | Implica que los datos de acceso son erróneo o que no se posee<br />privilegio para acceder al recurso. |
| `500`<br />Internal Server Error | Indica que hubo un error interno dentro de la API.           |



**Ejemplo de consumo:**

Request:

```bash
curl -X POST -H "Content-Type: application/json" http://dominio/api/login_check -d '{"username":"user","password":"pass"}'
```

En donde:

* **dominio:** Es el virtualHost configurado el e **[paso 2.8](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/INSTALL.md#28-creación-del-virtualhost)** del **[INSTALL.md](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/INSTALL.md)**.
* **user:** Es el nombre de usuario creado en el **[paso 2.7](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/INSTALL.md#27-creación-del-usuario-inicial)** del **[INSTALL.md](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/INSTALL.md)**.
* **password:** Es la contraseña del usuario creado en el **[paso 2.7](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/INSTALL.md#27-creación-del-usuario-inicial)** del **[INSTALL.md](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/INSTALL.md)**.



Response:

```json
{
	"token" : "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0MzQ3Mjc1MzYsInVzZXJuYW1lIjoia29ybGVvbiIsImlhdCI6IjE0MzQ2NDExMzYifQ.nh0L_wuJy6ZKIQWh6OrW5hdLkviTs1_bau2GqYdDCB0Yqy_RplkFghsuqMpsFls8zKEErdX5TYCOR7muX0aQvQxGQ4mpBkvMDhJ4-pE4ct2obeMTr_s4X8nC00rBYPofrOONUOR4utbzvbd4d2xT_tj4TdR_0tsr91Y7VskCRFnoXAnNT-qQb7ci7HIBTbutb9zVStOFejrb4aLbr7Fl4byeIEYgp2Gd7gY"
}
```



## Primeros pasos

Como parte de esta plantilla se brinda una guía de inicio rápido para la creación de los endpoints de la API de manera muy básica, la intención es brindar al lector conceptos básicos que le permitan crear su primer API, depende de este profundizar en los temas para la creación de APIs mas complejas. Se recomienda leer los enlaces de la documentación a las tecnologías utilizadas.

[**Ver guía de inicio rápido**](http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/doc/guia-inicio-rapido.md)




## Colaboradores

El proyecto es de propiedad intelectual del Ministerio de Salud de El Salvador y ha sido desarrollado en colaboración con las siguientes personas:

<div align="center">
    <table>
        <tr>
            <td align="center">
                <div align="center">
                    <a href="http://codigo.salud.gob.sv/caromero"  target="_blank"><img  style="width: 90px; height: 90px;" width="90" src="http://codigo.salud.gob.sv/uploads/-/system/user/avatar/13/avatar.png"></a><br />
                    Aaron Romero<br/>
                    <a href="mailto:caromero@salud.gob.sv">caromero@salud.gob.sv</a>
                </div>
            </td>
            <td align="center">
                <div align="center">
                    <a href="http://codigo.salud.gob.sv/crorozco"  target="_blank"><img  style="width: 90px; height: 90px;" width="90" src="http://codigo.salud.gob.sv/uploads/-/system/user/avatar/8/avatar.png"></a><br />
                    Caleb Rodriguez<br/>
                    <a href="mailto:crorozco@salud.gob.sv">crorozco@salud.gob.sv</a>
                </div>
            </td>
        </tr>
    </table>
</div>
<div align="center">
    <b>Dirección de Tecnologías de Información y Comunicaciones (DTIC).</b><br />
    <b>Ministerio de Salud</b><br />
    <a href="http://www.salud.gob.sv" alt="minsal" target="_blank">www.salud.gob.sv</a>
</div>



## Enlaces de ayuda
A continuación se presentan enlaces externos de ayuda referentes a tecnologías utilizadas para el desarrollo del proyecto:

* [Symfony 4.3](https://symfony.com/download) en Modo Microservicio/API.
* RESTful ([friendsofsymfony/rest-bundle](https://symfony.com/doc/master/bundles/FOSRestBundle/index.html)).
* Autenticación Json Web Token ([lexik/jwt-authentication-bundle](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md)).
* Open Api Specification, AKA Swagger ([nelmio/api-doc-bundle](https://symfony.com/doc/current/bundles/NelmioApiDocBundle/index.html)).
* JsonSchema ([justinrainbow/json-schema](https://github.com/justinrainbow/json-schema/tree/6.0.0-dev)).
* Gestor de contenedores [Docker](https://docs.docker.com/).
* Gestor de control de cambios [Git](https://git-scm.com/doc).



## Licencia

<a rel="license" href="https://www.gnu.org/licenses/gpl-3.0.en.html"><img alt="Licencia GNU GPLv3" style="border-width:0" src="https://next.salud.gob.sv/index.php/s/qxdZd5iwcqCyJxn/preview" width="96" /></a>

Este proyecto está bajo la <a rel="license" href="http://codigo.salud.gob.sv/plantillas/api-rest/blob/master/LICENSE">licencia GNU General Public License v3.0</a>
