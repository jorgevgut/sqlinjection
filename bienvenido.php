<?php
//iniciamos la sesión
session_start();

//primeramente validamos que exista un usuario en la sesión de Php,
if(isset($_SESSION['user']))
{
    $user = $_SESSION['user'];
    //Como se ha iniciado sesión se guarda la info a mostrar en una variable
    $data = "<p>Bienvenido {$user}</p>".
        "<p><a href=\"bienvenido.php?ss=1\">Cerrar sesión</a></p>";
    
    //También debemos revisar si el usuario ha cerrado sesión.
    if(isset($_GET['ss']))
    {
        if($_GET['ss']==1)//utilizamos el valor ss=1 a para referirnos a cerrar la sesión
        {
            session_destroy();//destruimos la sesión si así se comprueba
            redirect("index.php"); //regresamos el usuario al inicio
        }
    }
}
else
{
    $data="<p>Usted no ha iniciado sesión</p><p>Para iniciar entre <a href=\"index.php\">Aquí</a></p>".
            "<p>Si no se ha registrado haga click <a href=\"registro.php\">aqui</a></p>";
    session_destroy();//destruimos toda sesión ya que no hay sesión real iniciada.
}
//En caso de que no exista una sesión deberemos enviar un mensaje para que inicie sesión


?>

<!DOCTYPE html>
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 3.0 License

Name       : Prolific
Description: A two-column, fixed-width design with a bright color scheme.
Version    : 1.0
Released   : 20120719
-->
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title>Prolific by Free CSS Templates</title>
		<link href="http://fonts.googleapis.com/css?family=Abel" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
                <script type="text/javascript" src="js/scripts.js"></script>
	</head>
	<body>
		<div id="outer">
		<div id="wrapper">
			<div id="menu">
				<ul>
                                <li><a href="index.php">Login</a></li>
                                <li><a href="registro.php">Registro</a></li>
                                <li><a href="bienvenido.php">Bienvenida</a></li>
                            </ul>
				<br class="clearfix" />
			</div>
			<div id="header">
				<div id="logo">
					<h1><a href="#">Practica Sql Injection</a></h1>
				</div>
				
			</div>
			<div id="page">
				<div id="sidebar">
					<div class="box">
						
					</div>
					
				</div>
				<div id="content">
					<div class="box">
                                        <?=$data;?>
					</div>
					
					<br class="clearfix" />
				</div>
				<br class="clearfix" />
			</div>
		</div>
		<div id="footer">
			&copy; 2012 Untitled | Design by <a href="http://www.freecsstemplates.org/">FCT</a> | Images by <a href="http://fotogrph.com/">Fotogrph</a>
		</div>
		</div>
	</body>
</html>

<?php
function redirect($uri)
{
    //función para redirigir, notese en mi entorno estoy utilizando el puerto 3000
     header( "Location: http://localhost:3000/sqlinjection/{$uri}" );
}
?>