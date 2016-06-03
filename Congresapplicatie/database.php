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

        $this->createConnection($server, $database, $uid, $password);
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
    
    public function createConnection($server, $database, $uid, $password)
    {
        $connectionInfo = array("Database" => $database, "UID" => $uid, "PWD" => $password, "CharacterSet" => "UTF-8");
        $this->conn = sqlsrv_connect($server, $connectionInfo);
        
        if($this->checkConnection())
        {
            return true;
        }
        
        return false;
    }

/*   -----------------------------------------------------------------------------------

     Kijkt of de connectie is verbonden met de database 
     returns true of false
*/
        
    public function checkConnection()
    {
        if(isset($this->conn))
        {
            if($this->conn)
                return true;
        }
        
        return false;
    }


    
/*   -----------------------------------------------------------------------------------

     Verzend query naar de database 
     Parameters:
     Sql        String      
     Param      Array Numeric
     returns sql dataset
*/
    
    public function sendQuery($sql, $param)
    {
        if(!$this->checkConnection())
        {
            if(!$this->createConnection($this->server,
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
            return $this->getError();
        }

        return $result;
    }


    public function getError()
    {
        $err = "";
        
        if( ($errors = sqlsrv_errors() ) != null) 
        {
            foreach( $errors as $error ) 
            {
				if($error['code'] == 2627) {
					return 'Deze waarden komen al voor in de database';
				}
                $err=substr($error['message'],54,strlen($error['message'])-54).'<br>';
            }
        }
        
        return $err;
    }
	
	public function getConn() {
		return $this->conn;
	}
    
}

?>
