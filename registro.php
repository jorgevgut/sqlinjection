<?php
session_start();
//En esta página revisamos  que no se haya iniciado sesión, si el usuario inicia
//sesión, no hay necesidad de hacer el logín, por lo que lo redirigimos a la página
//de bienvenido
if(isset($_SESSION['user']))
{
    redirect("bienvenido.php");
}

//Primero debemos revisar si se ha subido la data de la forma de registro
//en caso de que sí, validamos y registramos el usuario y password
if(isset($_POST['user']) && isset($_POST['password']))
{
    //usuario y password de la DB
    $dbuser ='root';
    $dbpassword ='';

    //primero realizamos la conexión usando esta funcion
    $db = connect($dbuser,$dbpassword);
    if($db)
    {
        //si la conexión se realiza correctamente  realizamos el registro utilizando la conexión
        register($db);    
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
                                        <form method="post" action="registro.php">
                                            <p>USUARIO: <input name="user" id="user" type="email" size="10"
                                               onkeyup="errVal();"/></p>
                                            <p>CONTRASEÑA: <input name="password" id="password" type="password" size="10"
                                               onkeyup="errVal();"/></p>
                                            <p>REPITA CONTRASEÑA: <input name="rpassword" id="rpassword" type="password" size="10"
                                               onkeyup="errVal();"/></p>
                                            <input type="submit" value="Registrar"/>
                                            <div name="err" id="err"></div>
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

function register($db)
{
    //Primero obtenemos las entradas de la forma
    $user = mysql_real_escape_string($_POST['user']); //usamos un string absoluto para evitar sqlinjection
    $password = sha1($_POST['password']); //encriptamos el password
    $rpassword = sha1($_POST['rpassword']); //encriptamos la confirmación del password
    
    //Ahora validamos, si la validación es correcta procedemos a ejecutar la inserción en la DB
    if(validateInputs($user,$password,$rpassword))
    {
        //ya hemos validado los inputs, ahora comprobemos que el usuario este libre
        if(!validateUsername($db, $user))
        {
        //ahora creamos nuestra query
        $query = "INSERT INTO users(user,password) values('{$user}','{$password}')";
        try {  
            $db->beginTransaction();//iniciamos transacción DBO
            $db->exec($query); //ejecutamos la inserción de datos y el registro
            $db->commit();//terminamos la conexión exitosamente
            echo "Registro completado\n su usuario:{$user} y su password:{$_POST['password']}".
                  "\n Entre <a href=\"bienvenido.php\">Aqui</a> para ir a la pagina de bienvenida";
          } catch (Exception $e) {
            $db->rollBack(); //Si falla la conexión, tiramos la conexión
            echo "<p>Ocurrio un error, el registro no pudo ser completado</p>";
          }            
        }else{
            echo "<p>El nombre de usuario ya existe, por lo que no se pudo completar el registro.</p>";
        }
    
    }
    else //de lo contrario cancelamos el proceso
    {
        echo "<p>Los datos de registro son invalidos, intente de nuevo.</p>";
        $db = null;
        die();
    }
    
    
    
}

function validateInputs($user,$pass,$rpass)
{
    //primeramente validamos que los passwords sean coincidentes
    if($pass!="" && $pass!=$rpass)
        return false; //si no lo son no se realiza el registro.
    //segundo validamos que el usuario sea un email
    if($user!="")
    {
        if(!filter_var($user,FILTER_VALIDATE_EMAIL))
        {
            return false; //si no es un email valido no pasa la prueba
        }
    }
    return true; // si pasa las pruebas se regresa un valor verdadero
}

//Esta función es muy importante, pues validamos que el nombre de usuario sea unico
function validateUsername($db,$username)
{
    $existe=false; //por defecto asumimos que no existe el nombre de usuario
    $username = mysql_real_escape_string($username);
    $query = "SELECT user FROM users WHERE user='{$username}'";
        try {  
            $db->beginTransaction();//iniciamos transacción DBO
            $result = $db->query($query); //Consultamos si ya existe el username
            //terminamos la conexión exitosamente
            $db->commit();
            foreach ($result as $value) {
            echo $value['user']."<br/>";  
            echo $username."<br/>";
           //en caso de haber encontrado una coincidencia regresamos false para denegar el registro
           if($value['user']==$username)
               $existe = true; //se encontro una coincidencia
               return $existe;
           }
            
          } catch (Exception $e) {
            $db->rollBack(); //Si falla la conexión, tiramos la conexión
            echo "<p>Ocurrio un error, el registro no pudo ser completado</p>";
            $existe=false; //colocamos falso para evitar crear un registro cuando hubo error
          }   
          return $existe;
}

function redirect($uri)
{
    //función para redirigir, notese en mi entorno estoy utilizando el puerto 3000
     header( "Location: http://localhost:3000/sqlinjection/{$uri}" );
}
?>
