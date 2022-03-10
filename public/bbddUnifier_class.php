<?php

class BbddUnifier{
    private $user = 'root';
    private $password  ='admin';
    private $con;


    public function __constructor(){
        $this->con = new PDO('mysql:host=pw_local-db;dbname=TheGIFClub', $this->user, $this->password);
    }

    public function emailExists(){

    }


    public function registerUser(){

    }

    public function checkPassowrd(){

    }

    public function guardarSearch(){

    }

}


?>