# API Blog
API Blog es una API RESTful que permite gestionar los recursos de un blog web. La API proporciona endpoints para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) en entidades como Posts, Categorías, Etiquetas y Roles de usuario.

#Estructura del proyecto 
Endpoints: Permiten la comunicación entre el cliente y la API.
Controladores: Gestionan la lógica de cada endpoint.
Modelos: Representan las entidades o recursos de la base de datos.
Middlewares: Se utilizan para la autenticación y proteger los controladores de accesos no autorizados.

## Autenticación y seguridad
La API cuenta con un sistema de autenticación por tokens (JWT). Ademas contiene un sistema de roles y permisos que permite restringir el acceso a distintas acciones de la aplicación según el usuario.
Para la autenticación se utilizó el paquete Laravel Passport y para el sistema de roles y permisos se implemento Laravel Permissions.

## Documentacion con Swagger
Se utilizó Swagger para documentar cada endpoint de la API, incluyendo métodos HTTP (GET, POST, PUT, DELETE), parámetros, respuestas y tipos de datos.

Swagger mantiene el sistema de autenticación por token(JWT) para controlar el acceso a los recursos.

Además nos permite explorar la API de forma interactiva, ya que genera una interfaz gráfica (Swagger UI) que permite probar las rutas de lA API directamente desde el navegador.
Puedes enviar solicitudes con parámetros, cabeceras, y cuerpo (body), y ver las respuestas de la API en tiempo real.

## Tecnologias implementadas
-Laravel: Framework para el desarrollo backend de la aplicación.
-MySQL: Para la gestión de la base de datos.
-Postman: Ejecutar pruebas a los endpoints.
-Swagger: Herramienta para documentar los endpoints de la API.#API Blog
API Blog es una API RESTful que permite gestionar los recursos de un blog web. La API proporciona endpoints para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) en entidades como Posts, Categorías, Etiquetas y Roles de usuario.

## Estructura del proyecto 
Endpoints: Permiten la comunicación entre el cliente y la API.
Controladores: Gestionan la lógica de cada endpoint.
Modelos: Representan las entidades o recursos de la base de datos.
Middlewares: Se utilizan para la autenticación y proteger los controladores de accesos no autorizados.

## Autenticación y seguridad
La API cuenta con un sistema de autenticación por tokens (JWT). Ademas contiene un sistema de roles y permisos que permite restringir el acceso a distintas acciones de la aplicación según el usuario.
Para la autenticación se utilizó el paquete Laravel Passport y para el sistema de roles y permisos se implemento Laravel Permissions.

## Documentacion con Swagger
Se utilizó Swagger para documentar cada endpoint de la API, incluyendo métodos HTTP (GET, POST, PUT, DELETE), parámetros, respuestas y tipos de datos.

Swagger mantiene el sistema de autenticación por token(JWT) para controlar el acceso a los recursos.

Además nos permite explorar la API de forma interactiva, ya que genera una interfaz gráfica (Swagger UI) que permite probar las rutas de lA API directamente desde el navegador.
Puedes enviar solicitudes con parámetros, cabeceras, y cuerpo (body), y ver las respuestas de la API en tiempo real.

## Tecnologias implementadas
-Laravel: Framework para el desarrollo backend de la aplicación.
-MySQL: Para la gestión de la base de datos.
-Postman: Ejecutar pruebas a los endpoints.
-Swagger: Herramienta para documentar los endpoints de la API.
