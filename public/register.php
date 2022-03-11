<?php
ob_start();

require_once('BbddClass.php');
    use \BbddClass as BaseDades;
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
            <br>
            <br>
            <label for="password"><b>Password: </b></label>
            <input id="password" type="password" placeholder="Enter Password" name="password">
            <button type="submit" value="Send">Sign up</button>
            <p style="font-size: small;">GIF or JIF?</p>
        </form>
        <?php
            if(!empty($_POST)) {
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                    echo '<p class="errorMsg">Attention! Email must have a valid format.</p>';
                } else if (check_password($_POST['password']) == false) {
                    echo '<p class="errorMsg">Attention! Password must have at least 8 characters and contain numbers and letters</p>';
                } else {
                    do_register();
                }
            }
        ?>
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
    function check_password(string $passwd):bool{
        if(strlen($passwd) >= 8 && preg_match('/[A-Za-z]/', $passwd) && preg_match('/[0-9]/', $passwd)){
            return true;
        }else {
            return false;
        }
    }
    function do_register(){
        try {
            $email = $_POST['email'];
            $bbdd = new BaseDades();
            $bbdd->connect();
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
            $salthash = hash('sha512', ($date . $hashPassword));
            $bbdd->registerUser($email, $salthash, $date);
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
