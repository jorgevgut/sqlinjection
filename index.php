<?php
session_start();
//En esta página revisamos  que no se haya iniciado sesión, si el usuario inicia
//sesión, no hay necesidad de hacer el logín, por lo que lo redirigimos a la página
//de bienvenido
if(isset($_SESSION['user']))
{
    redirect("bienvenido.php");
}

//Posteriormente ya que revisamos que no hay nadie "logeado", revisamos si hubo un
//intento de iniciar sesión, si hubo un "submit" 
if(isset($_POST['user']) && isset($_POST['password']))
{
    //usuario y password de la DB
    $dbuser ='root';
    $dbpassword ='';

    //primero realizamos la conexión usando esta funcion
    $db = connect($dbuser,$dbpassword);
    if($db)
    {
        //si la conexión se realiza correctamente procedemos a iniciar sesión
        login($db,$_POST['user'],$_POST['password']);    
    }
    else
    {
        echo "<p>No se pudo establecer la conexión</p>";
    }    
}
//En caso de que no se haya colocado data desde la forma 
//no hacemos nada y esperamos a que se suba la data


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
<html xmlns="http://www.w3.org/1999/xhtml">
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
						<form method="post" action="index.php">
                                                <p>USUARIO: <input name="user" type="text"/></p>
                                                <p>CONTRASEÑA: <input name="password"type="password"/></p>
                                                <input type="submit" value="Ingresar"/>
                                                <p>Si no se ha registrado haga click <a href="registro.php">aqui</a></p>
                                            </form>
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

function connect($dbuser,$dbpassword)
{
    try {
        $db = new PDO('mysql:host=localhost;dbname=practicas', $dbuser, $dbpassword);
        return $db;
        /*foreach($dbh->query('SELECT * from USERS') as $row) {
            print_r($row);
        }
        $dbh = null;*/
    } catch (PDOException $e) {
        return false;
    }
}

function login($db,$usr,$pass)
{
    $user = mysql_real_escape_string($usr); //usamos un string absoluto para evitar sqlinjection
    $password = sha1($pass); //encriptamos el password
    $query ="SELECT user FROM users WHERE user='{$user}' AND password='{$password}'";
    try {
        $db->beginTransaction();
        $result = $db->query($query);
        
        foreach($result as $row) {
            $suser = $row['user'];
            }
        
    //Establecemos información de sesión
    $_SESSION['user']=$suser;
    $db->commit(); //termina la consulta con la DB
    
    //En este momento ya hemos validado el login de usuario, por lo que redirigimos a la página de bienvenido
    redirect("bienvenido.php");
    } catch (PDOException $e) {
        $db->rollBack();
        echo "<p>Ha ocurrido un error en el inicio de sesión.</p>";
        die();//termino del script
    }
}

function redirect($uri)
{
    //función para redirigir, notese en mi entorno estoy utilizando el puerto 3000
     header( "Location: http://localhost:3000/sqlinjection/{$uri}" );
}
?>