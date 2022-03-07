<?php
ob_start();

if(in_array('cookisessio', $_COOKIE) == false){ #no existeix la cookie
    if($_COOKIE['cookiesessio'] == null) { #no està plena
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
    <input id="search" type="text" placeholder="Mems, FBI open up, urss, pokimon, doramion..." name="search">
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





function removeCookie():void{
    setcookie("cookiesessio", null, time() - 3600);

    unset($_SESSION['sessio']);
    redirectToLogin();

}
function redirectToLogin():void{
    header("Location: /login.php");
    header("Header2: Session Expired / Not logged in");
    header("Header3: Redirecting to main page");
    exit();
}
function searchGIF(string $input):void{
    $sqlUser = 'root';
    $sqlPass = 'admin';
    $con = new PDO('mysql:host=pw_local-db;dbname=TheGIFClub', $sqlUser, $sqlPass);

    $stat = $con->prepare('INSERT INTO Search(query, timestamp) VALUES (?, now());');
    $stat->bindParam(1,$input,PDO::PARAM_STR);
    $stat->execute();


/*
    $APIKey = "R0OsrTT4b64wOXbRAazkISyqoXbzWdsc";
    #TODO: installar composer i guzle; composer s'ha de modificar el dockerfile, és segur?
    #TODO: codi crida api
    #TODO: què passa amb la sessió oberta si anem del search al login i loguegem una altra o registrem una altra?
    $client = new GuzzleHttp\Client(['base_uri' => 'api.giphy.com/v1/gifs/search?api_key='. $APIKey .'&q='.$input]);
    $request = new Request('PUT', 'http://httpbin.org/put');
    $response = $client->send($request, ['timeout' => 5]);

    var_dump($response);

*/


}
?>
<?php
ob_end_flush();
?>
