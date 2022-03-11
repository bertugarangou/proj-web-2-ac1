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
        <p>Please login here</p>
        <form method="POST">
            <label for="email"><b>Email: </b></label>
            <input id="email" type="text" placeholder="Enter Email" name="email">
            <br>
            <br>
            <label for="password"><b>Password: </b></label>
            <input id="password" type="password" placeholder="Enter Password" name="password">
            <button type="submit" value="Send">Login</button>
            <p style="font-size: small;">GIF or JIF?</p>
        </form>
        <?php
            if(empty($_POST)){
            }
            else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false){
                echo '<p class="errorMsg">Attention! You need to write an email. Ex: pingu@pingu.moc</p>';
            }else if(check_password($_POST['password']) == false){
                echo '<p class="errorMsg">Attention! You need to write a password.</p>';
            }else{
                do_login();
            }
        ?>
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
    function check_password(string $passwd):bool{
        if(strlen($passwd) >= 1){
            return true;
        }else {
            return false;
        }
    }
function do_login(){
    try {
        $bbdd = new BaseDades();
        $bbdd->connect();
        #Fem el hash de la contra
        $hashPassword = hash('sha512', $_POST['password'], false);
        $email = $_POST['email'];
        #Mirem si el correu existeix
        if ($bbdd->emailExists($email) == false) {
            echo '<p class="errorMsg">Wrong email or password. Try again.</p>';
            # millor no dir si la compta existeix o no
            return;
        }
        #Si l'usuari existeix agafem la contra i la data per comprovar el login
        $tmpID = $bbdd->checkPassowrd($email, $hashPassword);
        if($tmpID == -1){#wrong password
            echo '<p class="errorMsg">Wrong email or password. Try again.</p>';
            return;
        }else if($tmpID == -2){
            echo '<p class="errorMsg">Currently having problems on the login service. Try again later.</p>';
        }
        session_start();
        $_SESSION['user_id'] = $tmpID;
        redirectToSearch();
    }catch (Exception $e){
        echo '<p class="errorMsg">Currently having problems on the login service. Try again later.</p>';
        return;
    }
}
function redirectToSearch(){
    header("HTTP 200 OK");
    header("Location: /search.php");
    exit();
}
?>
<?php
    ob_end_flush();
?>