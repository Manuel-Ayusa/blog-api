# API Blog
<b>API Blog</b> es una API RESTful que permite gestionar los recursos de un blog web. La API proporciona endpoints para realizar operaciones <b>CRUD</b> (Crear, Leer, Actualizar y Eliminar) en entidades como Posts, Categorías, Etiquetas y Roles de usuario.

## Estructura del proyecto 
<b>Endpoints</b>: Permiten la comunicación entre el cliente y la API. <br>
<b>Controladores</b>: Gestionan la lógica de cada endpoint. <br>
<b>Modelos</b>: Representan las entidades o recursos de la base de datos. <br>
<b>Middlewares</b>: Se utilizan para la autenticación y proteger los controladores de accesos no autorizados. <br>

## Autenticación y seguridad
La <b>API</b> cuenta con un sistema de autenticación por tokens <b>(JWT)</b>. Ademas contiene un sistema de roles y permisos que permite restringir el acceso a distintas acciones de la aplicación según el usuario. <br>
Para la autenticación se utilizó el paquete <a href="https://laravel.com/docs/11.x/passport">Laravel Passport</a> y para el sistema de roles y permisos se implemento <a href="https://spatie.be/docs/laravel-permission/v6/introduction">Laravel Permissions</a>.

## Documentacion con Swagger
Se utilizó <a href="https://swagger.io/">Swagger</a> para documentar cada endpoint de la API, incluyendo métodos HTTP (GET, POST, PUT, DELETE), parámetros, respuestas y tipos de datos. <br>

Swagger mantiene el sistema de autenticación por token(JWT) para controlar el acceso a los recursos. <br>

Además nos permite explorar la API de forma interactiva, ya que genera una interfaz gráfica (Swagger UI) que permite probar las rutas de la API directamente desde el navegador.
Puedes enviar solicitudes con parámetros, cabeceras, y cuerpo (body), y ver las respuestas de la API en tiempo real. 

## Tecnologias implementadas
<b>Laravel</b>: Framework para el desarrollo backend de la aplicación. <br>
<b>MySQL</b>: Para la gestión de la base de datos.<br>
<b>Postman</b>: Ejecutar pruebas a los endpoints.<br>
<b>Swagger</b>: Herramienta para documentar los endpoints de la API.
