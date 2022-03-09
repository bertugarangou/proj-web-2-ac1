<?php
    ob_start();

    require_once __DIR__ . '/vendor/autoload.php';
    use GuzzleHttp\Client as Client;

    session_start();
    if(!isset($_SESSION['email'])) redirectToLogin();

    $showResults = false;
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
                    $jsonArray =  searchGIF(implode($_POST));
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
    unset($_SESSION['email']);
    session_destroy();
    redirectToLogin();
}
function redirectToLogin():void{
    header("Location: /login.php");
    header("Header2: Session Expired / Not logged in");
    header("Header3: Redirecting to main page");
    exit();
}
function searchGIF(string $input){
    $sqlUser = 'root';
    $sqlPass = 'admin';
    $con = new PDO('mysql:host=pw_local-db;dbname=TheGIFClub', $sqlUser, $sqlPass);
    $stat = $con->prepare('INSERT INTO Search(query, timestamp) VALUES (?, now());');
    $stat->bindParam(1,$input,PDO::PARAM_STR);
    $stat->execute();

    $APIKey = "R0OsrTT4b64wOXbRAazkISyqoXbzWdsc";

    try {
        $client = new Client();
        $config = array('query' => ['api_key' => $APIKey, 'q' => $input, 'limit' => 20, 'lang' => 'es'], 'verify' => false,);
        $response = $client->request('GET', 'api.giphy.com/v1/gifs/search', $config);
        $response2 = $response->getBody()->getContents();
        $jsonArray = (json_decode($response2, true))['data'];

        $showResults = true;

        if($showResults == true){

            foreach($jsonArray as $packedGif){

                echo "<div class=\"gif\"> ";
                echo "<img class=\"gifImg\" src=\"".$packedGif['images']['fixed_width']['url']."\" alt=\"".$packedGif['title']."\" role=\"img\"> ";
                if(strlen($packedGif['username']) > 0) echo "<p> By: ".$packedGif['username'] ."</p>";
                else echo "<p class='gifUser'> Uploaded anonymously</p>";
                echo "</div>";
            }
        }




        return $jsonArray;

    }catch (Exception $fail){
        echo "Please try again. You may be disconnected from internet or your request was too long.";
        return null;
    }
}

?>
<?php
    ob_end_flush();
?>
