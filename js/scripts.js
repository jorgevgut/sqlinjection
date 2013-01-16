
/* Funciones JS para su uso en el registro de un nuevo usuario*/

//Funcion errVal utilizada en el formulario de registro
function errVal()
{
    r = valPassword( document.getElementById('password').value,
        document.getElementById('rpassword').value);
    r+= valUser(document.getElementById('user').value);
    
    errText = document.getElementById('err');
    errText.innerHTML = r;
    
    
}

function valPassword(pass,rpass) //validar que el password sea igual en ambos campos
{
    if(pass!="" && pass!=rpass)
        {
            return "<p><a class=\"error\">las contraseñas no coinciden</a></p>";
        }
    return "<p><a class=\"fine\">las contraseñas coinciden</a></p>";
}

function valUser(user) //validar que el usuario sea email
{
    if(user.indexOf(".")>0 && user.indexOf("@")>0)
        return "<p><a class=\"fine\">Email correcto</a></p>";
    else
        return "<p><a class=\"error\">Introduzca un Emal valido</a></p>";
}



