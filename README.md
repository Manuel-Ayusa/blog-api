# API Blog
<b>API Blog</b> es una <b>API RESTful</b> que permite gestionar los recursos de un blog web. La API proporciona endpoints para realizar operaciones <b>CRUD</b> (Crear, Leer, Actualizar y Eliminar) en entidades como <b>Posts</b>, <b>Categorías</b>, <b>Etiquetas</b> y <b>Roles de usuario</b>.

## Estructura del proyecto 
<b>Endpoints</b>: Permiten la comunicación entre el cliente y la API. <br>
<b>Controladores</b>: Gestionan la lógica de cada endpoint. <br>
<b>Modelos</b>: Representan las entidades o recursos de la base de datos. <br>
<b>Middlewares</b>: Se utilizan para la autenticación y proteger los controladores de accesos no autorizados. <br>

## Autenticación y seguridad
La <b>API</b> cuenta con un sistema de autenticación por tokens <b>(JWT)</b>. Ademas contiene un sistema de roles y permisos que restringe el acceso a distintas acciones de la aplicación según el usuario. <br>
Para la autenticación se utilizó el paquete <a href="https://laravel.com/docs/11.x/passport" target="_blank">Laravel Passport</a> y para el sistema de roles y permisos se implemento <a href="https://spatie.be/docs/laravel-permission/v6/introduction" target="_blank">Laravel Permissions</a>.

## Documentacion con Swagger
Se utilizó <a href="https://swagger.io/" target="_blank">Swagger</a> para documentar cada endpoint de la API, incluyendo métodos HTTP (GET, POST, PUT, DELETE), parámetros, respuestas y tipos de datos. <br>

Swagger mantiene el sistema de autenticación por token(JWT) para controlar el acceso a los recursos. <br>

Además nos permite explorar la API de forma interactiva, ya que genera una interfaz gráfica (Swagger UI) que permite probar las rutas de la API directamente desde el navegador.
Puedes enviar solicitudes con parámetros, cabeceras, y cuerpo (body), y ver las respuestas de la API en tiempo real.

La misma la podes encontrar <a href="http://api.codersfree.test/api/documentation#/" target="_blank">aqui</a>.

## Tecnologias implementadas
<b>Laravel</b>: Framework para el desarrollo backend de la aplicación. <br>
<b>MySQL</b>: Para la gestión de la base de datos.<br>
<b>Postman</b>: Ejecutar pruebas a los endpoints.<br>
<b>Swagger</b>: Herramienta para documentar los endpoints de la API.

## Autor
Manuel Alejandro Ayusa - Programador y Desarrollador web <br>
<a href="mailto:ayusamanuel6@gmail.com">ayusamanuel6@gmail.com</a> <br>
<a href="https://www.linkedin.com/in/manuel-alejandro-ayusa-aa7415282/">Linkedin</a>
