<?php
ob_start();

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
            echo 'Attention! You need to write an email. Ex: pingu@pingu.moc';
        }else if(check_password($_POST['password']) == false){
            echo 'Attention! You need to write a password.';
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
    #Fem el hash de la contra
    $hashPassword = hash('sha512', $_POST['password'], false);
    $email = $_POST['email'];

    #Connectem a la bbdd
    $sqlUser = 'root';
    $sqlPass = 'admin';
    $con = new PDO('mysql:host=pw_local-db;dbname=TheGIFClub', $sqlUser, $sqlPass);

    #Mirem si el correu existeix per no haver de mirar la contra amb nulls
    $stat = $con->prepare('SELECT email FROM Users WHERE email=?');
    $stat->bindParam(1,$email,PDO::PARAM_STR);
    $stat->execute();
    $res = $stat->fetchAll(PDO::FETCH_ASSOC);

    #Si l'usuari no existeix; cancelar
    if(count($res) == 0){
        echo 'Wrong email or password. Try again.';
        return;
    }

    #Si l'usuari existeix agafem la contra i la data per comprovar el login
    $stat = $con->prepare('SELECT password, created_at, user_id FROM Users WHERE email=?');
    $stat->bindParam(1,$email,PDO::PARAM_STR);
    $stat->execute();
    $res = $stat->fetch();
    #Fem el hash del salt
    $localHash = hash('sha512', ($res[1] . $hashPassword));
    #comparem hashs de les contres. Si esta malament cancelem
    if(strcmp($localHash, $res[0]) != 0){
        echo 'Wrong email or password. Try again.';
        return;
    }

    #TODO: re-redirigeix sempre del search al login

    session_start();
    $_SESSION['email'] = $email;

    redirectToSearch();
}

function redirectToSearch(){
    header("Location: /search.php"); #TODO: 200 o custom?
    header("Header2: Login successful" );
    header("Header3: Redirecting to main page" );
    exit();
}


?>


<?php
    ob_end_flush();
?>