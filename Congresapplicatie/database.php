<?php

class Database
{
    private $server; 
    private $database;
    private $uid;
    private $password;
    
    private $conn;
    
    public function __construct($server, $database, $uid, $password)
    {
        $this->server = $server;
        $this->database = $database;
        $this->uid = $uid;
        $this->password = $password;
        $this->conn = false;

        $this->maakConnectie($server, $database, $uid, $password);
    }
    
/*   -----------------------------------------------------------------------------------

     Maak de connectie met de database
     Parameters:
     Server     String      
     Database   String
     Uid        String
     Password   String
     returns true of false
*/
    
    public function maakConnectie($server, $database, $uid, $password)
    {
        $connectionInfo = array("Database" => $database, "UID" => $uid, "PWD" => $password);
        $this->conn = sqlsrv_connect($server, $connectionInfo);
        
        if($this->checkConnectie())
        {
            return true;
        }
        
        return false;
    }

/*   -----------------------------------------------------------------------------------

     Kijkt of de connectie is verbonden met de database 
     returns true of false
*/
        
    public function checkConnectie()
    {
        if(isset($this->conn))
        {
            if($this->conn)
                return true;
        }
        
        return false;
    }
        
    /*    Connection check testscript */

     

    
/*   -----------------------------------------------------------------------------------

     Verzend query naar de database 
     Parameters:
     Sql        String      
     Param      Array Numeric
     returns sql dataset
*/
    
    protected function verzendQuery($sql, $param)
    {
        if(!$this->checkConnectie())
        {
            if(!$this->maakConnectie(   $this->server,
                                        $this->database,
                                        $this->uid,
                                        $this->password))
            {
                return false;
            }
        }

        $result = sqlsrv_query($this->conn, $sql, $param);

        if ($result === false)
        {
            die(FormatErrors(sqlsrv_errors()));
        }

        return $result;
    }

    
/*   -----------------------------------------------------------------------------------

     Checks whether the given gebruikersnaam + wachtwoord is correct 
     Parameters:
     Gebruikersnaam    String      
     Wachtwoord        String
     returns true of false
*/

    public function loginCheck($gebruikersnaam, $wachtwoord) 
    {    
        if(!$this->checkConnectie())
        {
            if(!$this->maakConnectie(   $this->server,
                                        $this->database,
                                        $this->uid,
                                        $this->password))
            {
                return false;
            }
        }
 
        $sqlstmntUserLogin = 'SELECT gebruikersnaam, wachtwoord FROM Gebruiker WHERE gebruikersnaam = ?';
        
        $paramsUserLogin = array($gebruikersnaam);

        $resultUserLogin = $this->verzendQuery($sqlstmntUserLogin, $paramsUserLogin);
        
        while ($row = sqlsrv_fetch_array($resultUserLogin, SQLSRV_FETCH_ASSOC)) {
            
            if($row['gebruikersnaam'] == $gebruikersnaam AND 
               $row['wachtwoord'] == $this->encryptWachtwoord($wachtwoord)) {
                // Login correct!
                return true;
            } else {
                // Login incorrect!
                return false;
            }
        }
        
        return false;
    }

/*  ----------------------------------------------------------------------------------- //

    encrypt password with sha256 hash
    returns encrypted password
*/

    public function encryptWachtwoord($wachtwoord) {
        return hash("sha256", $wachtwoord);
    }
    
/*  ----------------------------------------------------------------------------------- //

    encrypt password with sha256 hash
    returns encrypted password
*/
    public function maakWillekeurigWachtwoord($lengte)
    {
        $karakters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $karakterLengte = strlen($karakters);
        
        $wachtwoord = '';
        
        for ($i = 0; $i < $lengte; $i++) {
            $wachtwoord .= $karakters[rand(0, $karakterLengte - 1)];
        }
        
        return $wachtwoord;
    }
    
    public function geefError()
    {
        $err = "";
        
        if( ($errors = sqlsrv_errors() ) != null) 
        {
            foreach( $errors as $error ) 
            {
                $err .= "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                $err .= "code: ".$error[ 'code']."<br />";
                $err .= "message: ".$error[ 'message']."<br />";
            }
        }
        
        echo $err;
    }
    
}

?>