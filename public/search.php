<?php
    ob_start();

    require_once __DIR__ . '/vendor/autoload.php';
    use GuzzleHttp\Client as Client;

    session_start();
    if(!isset($_SESSION['user_id'])) redirectToLogin();

    require_once('BbddClass.php');
    use \BbddClass as BaseDades;
    require_once ('GiphySearcher.php');
    use \GiphySearcher as Giphy;
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

    <link rel="icon" href="/media/logo.webp"></head>
    <body>
    <?php
        if(rand(0,1)) echo "<h1>The GIF CLUB</h1><br>";
        else echo "<h1>The JIF CLUB</h1><br>";
    ?>

        <form method="POST">
            <label for="search">Search a topic: </label>
            <input id="search" type="text" placeholder="Mems, FBI open up, urss, pokimon, doramion..." name="search">
            <button id="search-button" type="submit" value="Send" onclick="search">Find it!</button>
            <br>
        </form>
        <section class="gifSection">
        <?php
            if($_POST && isset($_POST['search'])){
                if(strlen(implode($_POST)) >= 1) {
                    searchGIF(implode($_POST));
                }
            }
        ?>
        </section>

        <form method="POST">
            <input type="submit" name="logout" value=" Logout " onclick="logout">
        </form>
        <?php
            if($_POST && isset($_POST['logout'])) removeCookie();
        ?>

    </body>
</html>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php

function removeCookie():void{
    unset($_SESSION['user_id']);
    session_destroy();
    redirectToLogin();
}
function redirectToLogin():void{
    header("HTTP 301 Moved Permanently");
    header("Location: /login.php");

    exit();
}
function searchGIF(string $input){

    try {

        $bbdd = new BaseDades();
        $bbdd->connect();

        $bbdd->guardarSearch($input);

    }catch (Exception $e){
        echo '<p class="errorMsg">Currently having problems in the service. Try again later.</p>';
        return;
    }


    try {
            $giphy = new Giphy();
            $jsonArray= $giphy->connect($input, 'es', 20);

            foreach($jsonArray as $packedGif){

                echo "<div class=\"gif\"> ";
                echo "<img class=\"gifImg\" src=\"".$packedGif['images']['fixed_width']['url']."\" alt=\"".$packedGif['title']."\" role=\"img\"> ";
                if(strlen($packedGif['username']) > 0) echo "<p> By: ".$packedGif['username'] ."</p>";
                else echo "<p class='gifUser'> Uploaded anonymously</p>";
                echo "</div>";

        }


    }catch (Exception $fail){
        echo '<p class="errorMsg">Please try again. You may be disconnected from internet or your request was too long.</p>';
        return null;
    }
}

?>
<?php
    ob_end_flush();
?>
