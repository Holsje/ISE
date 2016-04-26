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
		
		
		public function getSubjects() {

            $result = $this->sendQuery("SELECT Subject FROM Subject",null);

            if ($result){
				$array = array();
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                {
					array_push($array,$row['Subject']);
                }
				return $array;
            }
			return false;
		}
		
		public function getCongresses() {
			
			$result = $this->sendQuery("SELECT * FROM Congress");
			
			if ($result){
				$array = array();
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                {
					array_push($array,array($row['CongressNo']=>array($row['Name'], $row['Subject'], $row['LOCATION'], $row['StartDate'], $row['EndDate'])));
                }
				return $array;
            }
			return false;
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