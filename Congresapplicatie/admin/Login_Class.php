<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 9-5-2016
 * Time: 13:09
 */
    require_once('Management.php');
    class Login extends Management{

        public function checkLogin($username, $password){

            $result = parent::getDatabase()->sendQuery("SELECT username FROM Employee WHERE Username = ? AND Password = ?", array($username, hash('sha256', $password)));

            if ($result){
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                {
                    return true;
                }
                return false;
            }
        }

    }
?>