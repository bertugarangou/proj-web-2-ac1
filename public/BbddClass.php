<?php
class BbddClass{
    private $user = 'root';
    private $password  ='admin';
    private $con = null;


    /**
     * @throws Exception
     */
    public function connect(){
        try {
            $this->con = new PDO('mysql:host=pw_local-db;dbname=TheGIFClub', $this->user, $this->password);
        }catch (Exception $e){
            throw new Exception('No connection to bbdd');
        }
    }

    /**
     * @throws Exception
     */
    public function emailExists(string $email):bool{
        try {
            #Mirem si el correu existeix per no haver de mirar la contra amb nulls
            $stat = $this->con->prepare('SELECT email FROM Users WHERE email=?');
            $stat->bindParam(1, $email, PDO::PARAM_STR);
            $stat->execute();
            $res = $stat->fetchAll(PDO::FETCH_ASSOC);

            #Si l'usuari no existeix; cancelar
            if (count($res) == 0) {
                return false;
            }
            return true;
        }catch (Exception $e){
            throw new Exception('No connection to bbdd');
        }
    }


    /**
     * @throws Exception
     */
    public function registerUser(string $email, string $passwordSaltHash, $date){
        try {
        //Registrem l'usuari amb email, data utilitzada pel SALT i hash amb SALT
        $stat = $this->con->prepare('INSERT INTO Users(email, password, created_at, updated_at) VALUES (?, ?, ?, ?);');
        $stat->bindParam(1,$email,PDO::PARAM_STR);
        $stat->bindParam(2,$passwordSaltHash,PDO::PARAM_STR);
        $stat->bindParam(3,$date,PDO::PARAM_STR); #pel salt s'hauria de no enviar la date i generar-la a la bbdd
        $stat->bindParam(4,$date,PDO::PARAM_STR);
        $stat->execute();
        }catch (Exception $e){
            throw new Exception('No connection to bbdd');
        }
    }

    public function checkPassowrd(string $email,string $passwordSaltHash) : int{
        try {
            $stat = $this->con->prepare('SELECT password, created_at, user_id FROM Users WHERE email=?');
            $stat->bindParam(1, $email, PDO::PARAM_STR);
            $stat->execute();
            $res = $stat->fetch();
            #Fem el hash del salt
            $localHash = hash('sha512', ($res[1] . $passwordSaltHash));
            #comparem hashs de les contres. Si esta malament cancelem
            if (strcmp($localHash, $res[0]) != 0) {
                return -1; #incorrect passwords
            }
            return $res[2];
        }catch (Exception $e){
            return -2;
        }
    }

    public function guardarSearch(string $input)
    {
        #Enviar la Search
        $stat = $this->con->prepare('INSERT INTO Search(query, timestamp) VALUES (?, ?);');
        $stat->bindParam(1, $input, PDO::PARAM_STR);
        $nowTimeLast = date('Y-m-d H:i:s');
        $stat->bindParam(2, $nowTimeLast, PDO::PARAM_STR);
        $stat->execute();

        #Agafar search_id
        $stat2 = $this->con->prepare('SELECT LAST_INSERT_ID();');
        $stat2->execute();
        $res2 = $stat2->fetch();

        # $res2 => search_id
        # $_SESSION['user_id'] => user_id

        #Enviar la relació UserSeach
        $tmp = $res2['LAST_INSERT_ID()'];
        $stat = $this->con->prepare('INSERT INTO UserSearch(user_id, search_id) VALUES (?, ?);');
        $stat->bindParam(1, $_SESSION['user_id'], PDO::PARAM_STR);
        $stat->bindParam(2, $tmp, PDO::PARAM_STR);
        $stat->execute();
    }
}


?>