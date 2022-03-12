<?php
    ob_start();

    require_once('BbddClass.php');
    use \BbddClass as BaseDades;
    $visibleEmail = 'hidden';   #vars per fer toogle als errors
    $visiblePasswd = 'hidden';
?>
<?php
if(!empty($_POST)) { #comprovar si l'email i la contra tenen formats vàlids. Es mira i es fa toogle
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
        $visibleEmail = 'visible';

    }
    if (check_password($_POST['password']) == false) {
        $visiblePasswd = 'visible';

    }
    if(strcmp($visiblePasswd, 'hidden') == 0 && strcmp($visibleEmail, 'hidden') == 0){
        do_register();  #fer el registre si són correctes
    }
}
?>
    <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="authors" content="Albert Garangou">
        <title>THE GIF CLUB</title>
        <link rel="stylesheet" href="./css/union.css">
        <META NAME="robots" CONTENT="nofollow">
        <META name="description" content="PHP AC1 Proj Web 2">
        <META name="keywords" content="GIF, AC1, PROJ-WEB-2">
        <link rel="icon" href="/media/logo.webp">
    </head>
    <body>
        <h1>The GIF CLUB</h1><br>
        <p>Please register here</p>
        <form method="POST">
            <label for="email"><b>Email: </b></label>
            <input id="email" type="text" placeholder="Enter Email" name="email">
            <p class="errorMsg" style="visibility: <?php echo $visibleEmail?>">Incorrect email format.</p>

            <br>
            <br>
            <label for="password"><b>Password: </b></label>
            <input id="password" type="password" placeholder="Enter Password" name="password">
            <p class="errorMsg" style="visibility: <?php echo $visiblePasswd?>">At least one letter and number and 8 characters long. No common passwords.</p>
            <button type="submit" value="Send">Sign up</button>
            <p style="font-size: small;">GIF or JIF?</p>
        </form>
        <a href="./login.php"><img class="login-img" src="./media/register.jpg" alt="Already account image" role="presentation">
        </a>
    </body>
</html>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php
/**
 * Funció que mira si la contrasenya té els requeriments vàlids
 * @param string $passwd contrasenya a inspeccionar
 * @return bool
 */
    function check_password(string $passwd):bool{
        if(strlen($passwd) >= 8 && preg_match('/[A-Za-z]/', $passwd) && preg_match('/[0-9]/', $passwd) && password_check_similar($passwd) == false){
            return true; #es mira aA09 i 8 chars
        }else {
            return false;
        }
    }

/**
 * Funció que compara la contrasenya amb possibles similituds de contrasenyes fàcils d'endevina
 * Hauria de comprovar d'una llista moolt més gran totes les possibles paraules comunes
 * @param string $password Contrasenya a inspeccionar
 * @return bool true: permesa
 *              false: no permesa
 */
    function password_check_similar(string $password):bool{
        $similarPasswd = ['abcd1234', '1234abcd', '12345678a', 'abcdefg1', 'password1234', 'letmein1']; #contrassenyes facils no permeses (uns pocs exemples)
        foreach ($similarPasswd as $mostra){
            if(strcmp($password,$mostra) == 0){
                return true;
            }
        }

        return false;
    }
/**
 * Funció que fa el registr de l'usuari
 * @return void
 */
    function do_register(){
        try {
            $email = $_POST['email'];
            $bbdd = new BaseDades();
            $bbdd->connect();   #connexió a la bbdd
            #Si ja existeix (array plena); cancelar
            if($bbdd->emailExists($email) == true){
                echo '<p class="errorMsg">Email already in use. Please login instead or use a new one.</p>';
                return;
            }
            #Si no existeix; registrar
            #Fem un hash de la contrassenya
            $hashPassword = hash('sha512', $_POST['password'], false);
            #Fem un SALT
            $date = date('Y-m-d H:i:s');
            $salthash = hash('sha512', ($date . $hashPassword));    #per no tenir dos hashs iguals en dos bbdd diferents
            $bbdd->registerUser($email, $salthash, $date);  #es registra l'usuari correcte
            #http redirect al login
            header("HTTP 200 OK");
            header("Location: /login.php");
            exit();
        }catch (Exception $e){
            # si la bbdd està desconnectada
            echo '<p class="errorMsg">Currently having problems for the registration service. Try again later.</p>';
        }
    }
?>
<?php
    ob_end_flush();
?>
