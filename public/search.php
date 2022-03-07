<?php
ob_start();
?>
<?php
    if(in_array('carquinyolisSession', $_COOKIE) == false){ #existeix la cookie
        if($_COOKIE['carquinyolisSession'] == null) { #està plena
           redirectToLogin();
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

        <link rel="icon" href="/media/logo.webp"></head>

    <body>
        <h1>The GIF CLUB</h1><br>

        <form method="POST">
            <label for="search">Search a topic: </label>
            <input id="search" type="text" placeholder="Mems, FPI open up, urss, pokimon, doramion..." name="search">
            <button id="search-button" type="submit" value="Send" onclick="search">Find it!</button>
            <br>

        </form>
        <?php
        if($_POST && isset($_POST['search'])){
            searchGIF(implode($_POST));
        }
        ?>

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

$APIKey = "R0OsrTT4b64wOXbRAazkISyqoXbzWdsc";



    function removeCookie():void{
        setcookie("carquinyolisSession", null, time() - 3600);
        redirectToLogin();

    }
    function redirectToLogin():void{
        header("Location: /login.php");
        header("Header2: Session Expired / Not logged in");
        header("Header3: Redirecting to main page");
    }
    function searchGIF(string $input):void{
        echo 'Search for " ' . $input . ' ":';
        #codi crida api
        #s'ha de protegir una session de session hijacking?
    }
?>
<?php
ob_end_flush();
?>
