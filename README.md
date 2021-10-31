# Geniat_API
 Examen_Back_Geniat

1.- Descargar el repositorio Geniat_API
2.- Ejecuta el script de la Base de datos db_Geniat.sql en tu gestor de BD (Mysl)
3.- Copiar el contenido de la carpeta Geniat_API-master a la ruta C:\xampp\htdocs\Geniat_API
4.- Abrir el archivo config.inc.php ubicado en la ruta C:\xampp\htdocs\Geniat_API\inc\config.inc.php y 
	actualizar la configuración de la base de datos a la tuya, 
	por default el proyecto esta configurado con una bd local y sin contraseña.
5.- Si el nombre de la carpeta donde se copio el archivo es diferente de "Geniat_API" 
	actualiza la variable $CARPETA_BASE al nombre de la carpeta correspondiente. 
6.- Ejecuta tu servidor apache para poder hacer el consumo de las Apis.


Una vez realizado los pasos anteriores podras realizar el consumo de los diferentes endpoints
Para realizar el incio de sesión utiliza las credenciales default
	Correo = rcuamatzi@gmail.com
     	Password = Geniat123

Especificación de los End Points disponibles
Nota importante: Los endpoins porporcionados a continuación se estan ejecutando desde un servidor local en el puerto 80. 
	         Remplaza la ruta "http://localhost/" por la correspondiente a tu servidor.

endPoint 1 -> http://localhost/Geniat_API/controller/Usuario/registrarUsuario.php
Method -> POST
Bearer Token -> required
eg request ->
{
    "Correo" : "jose@gmail.com",
    "Password" : "Geniat123",
    "Nombre" : "Jose",
    "ApellidoPaterno" : "Perez",
    "ApellidoMaterno" : "Leon",
    "Rol" : "1"
}


endPoint 2 -> http://localhost/Geniat_API/controller/Usuario/Login.php
Method -> GET
Bearer Token -> No required
eg request ->
{
    "Correo" :"rcuamatzi@gmail.com",
    "Password" : "Geniat123"
}


endPoint 3 -> http://localhost/Geniat_API/controller/Publicacion/registrarPublicacion.php
Method -> POST
Bearer Token -> required
eg request ->
{
    "Titulo" : "La historia del loco",
    "Descripcion" : "publicación de Jhon Katzenbach"
}


endPoint 4 -> http://localhost/Geniat_API/controller/Publicacion/actualizarPublicacion.php
Method -> PUT
Bearer Token -> required
eg request ->
{
    "IdPublicacion" : "3",
    "Titulo" : "La historia del loco",
    "Descripcion" : "publicación de Jhon Katzenbach"
}

endPoint 5 -> http://localhost/Geniat_API/controller/Publicacion/eliminarPublicacion.php
Method -> DELETE
Bearer Token -> required
eg request ->
{
    "IdPublicacion" : "3"
}


endPoint 6 -> http://localhost/Geniat_API/controller/Publicacion/getPublicaciones.php
Method -> GET
Bearer Token -> required
eg request -> {}


