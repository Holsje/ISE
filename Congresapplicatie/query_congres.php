<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 19-4-2016
 * Time: 10:59
 */

    require('database.php');

    class Congres extends Database
    {
        public function __construct($server, $database, $uid, $password)
        {
            parent::__construct($server, $database, $uid, $password);
        }

        public function checkLogin($username, $password){

            $result = $this->sendQuery("SELECT username FROM Employee WHERE Username = ? AND Password = ?", array($username, hash('sha256', $password)));

            if ($result){
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                {
                    return true;
                }
                return false;
            }
        }
		
		
		

		

		
		public function createCongress($congressName,$location,$subject,$startDate,$endDate) {
            $this->sendQuery("IF NOT EXISTS(SELECT 1 FROM Subject WHERE Subject = ?) INSERT INTO Subject values(?);",array($subject,$subject));
            $result = $this->sendQuery("INSERT INTO Congress(Name,Location,[Subject],StartDate,EndDate) VALUES(?,?,?,?,?)", array($congressName,$location,$subject,$startDate,$endDate));
            if($result) {
                return true;
            }
            return false;
		}
    }

?>