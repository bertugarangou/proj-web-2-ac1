<?php
    ob_start();
    require_once('BbddClass.php');
    use \BbddClass as BaseDades;
    $visibleEmail = 'hidden';   #toggles per mostrar els errors
    $visiblePasswd = 'hidden';

?>
    <?php
    if(!empty($_POST)) {    #comprovar els errors de l'email i psswd. Innecessari pel login si ja ho mira a la bbdd però
        # ho demana l'enunciat
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
            $visibleEmail = 'visible';

        }
        if (check_password($_POST['password']) == false) {
            $visiblePasswd = 'visible';

        }
        if(strcmp($visiblePasswd, 'hidden') == 0 && strcmp($visibleEmail, 'hidden') == 0){
            do_login(); #si són correctes fer el login
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
        <p>Please login here</p>
        <form method="POST">
            <label for="email"><b>Email: </b></label>
            <input id="email" type="text" placeholder="Enter Email" name="email">
            <p class="errorMsg" style="visibility: <?php echo $visibleEmail?>">Incorrect email format.</p>
            <br>
            <br>
            <label for="password"><b>Password: </b></label>
            <input id="password" type="password" placeholder="Enter Password" name="password">
            <p class="errorMsg" style="visibility: <?php echo $visiblePasswd?>">Password needs a-A,0-9 and 8 chars minimum.</p>
            <button type="submit" value="Send">Login</button>
            <p style="font-size: small;">GIF or JIF?</p>
        </form>

        <a href="./register.php"><img class="register-img" src="./media/no-account.jpg" alt="No account image" role="presentation">
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
     * Funció que comprova si la contrasenya té els requisits mínims
     * @param string $passwd contrasenya a comprovar
     * @return bool true si és correcte
     *              false si conté errors
     */
    function check_password(string $passwd):bool{
        if(strlen($passwd) >= 8 && preg_match('/[A-Za-z]/', $passwd) && preg_match('/[0-9]/', $passwd)){
            return true;
        }else {
            return false;
        }
    }
function do_login(){
    try {   #Assegurar que la bbdd és accessible
        $bbdd = new BaseDades();
        $bbdd->connect();   #establir connexió
        #Fem el hash de la contra
        $hashPassword = hash('sha512', $_POST['password'], false);  #es fa un hash de la contra
        $email = $_POST['email'];
        #Mirem si el correu existeix
        if ($bbdd->emailExists($email) == false) {
            echo '<p class="errorMsg">Wrong email or password. Try again.</p>';
            # millor no dir si la compta existeix o no
            return; #si no existeix no es fa el login
        }
        #Si l'usuari existeix agafem la contra i la data per comprovar el login
        $tmpID = $bbdd->checkPassowrd($email, $hashPassword);   #comprovem que la contra és correcte
        if($tmpID == -1){#wrong password
            echo '<p class="errorMsg">Wrong email or password. Try again.</p>';
            return; #contrasenya incorrecte
        }else if($tmpID == -2){ #problemes generals a la bbdd
            echo '<p class="errorMsg">Currently having problems on the login service. Try again later.</p>';
        }
        session_start();    #iniciar sessió php
        $_SESSION['user_id'] = $tmpID;  #aprofitem i enviem l'ID als altres fitxers amb la sessió
        redirectToSearch(); #enviem l'usuari al login
    }catch (Exception $e){
        echo '<p class="errorMsg">Currently having problems on the login service. Try again later.</p>';
        return; #si no pot accedir a la bbdd
    }
}

/**
 * Funció que redirigeix a la pàgina del search
 * @return void
 */
function redirectToSearch(){
    header("HTTP 200 OK");
    header("Location: /search.php");
    exit();
}
?>
<?php
    ob_end_flush();
?>