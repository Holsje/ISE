<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 9-5-2016
 * Time: 13:09
 */
    require_once('Management.php');
    class Login extends Management{


        public function createLoginScreenNotLoggedIn($errorstring){
            $inputUsername = new Text(null, "Gebruikersnaam", "input-username", "form-control col-xs-12 col-md-4 col-sm-4", true, true, true);
            $inputPassword = new Password(null, "Wachtwoord", "input-password", "form-control col-xs-12 col-md-4 col-sm-4", true, true, true);
            $submitButtonLogin = new Submit("Inloggen", null, "login", "form-control col-md-4 col-md-offset-4 btn btn-default", true, true);
            $screenObjects = array($inputUsername, $inputPassword, $submitButtonLogin);

            $submitButtonLogout = new Submit("Uitloggen", null, "logout", "form-control col-md-4 col-md-offset-4 btn btn-default", true, true);
            $screenObjectsLoggedIn = array($submitButtonLogout);
            $this->getCreateScreen()->createForm($screenObjects, null);
            if (!empty($errorstring)){
                echo $errorstring;
            }
        }

        public function createLoginScreenLoggedIn(){
            $submitButtonLogout = new Submit("Uitloggen", null, "logout", "form-control col-md-4 col-md-offset-4 btn btn-default", true, true);
            $screenObjects = array($submitButtonLogout);

            $this->getCreateScreen()->createForm($screenObjects, null);
        }

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

        public function checkUser($username){
            $result = parent::getDatabase()->sendQuery("SELECT Type FROM Employee WHERE Username = ?", array($username));
            if ($result){
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                    if ($row['Type']=="Congresbeheerder"){
                        return $this->getAdminCongresses($username);
                    }
                    else{
                        return $row['Type'];
                    }
                }
            }
        }

        public function getAdminCongresses($username){
            $result = parent::getDatabase()->sendQuery("SELECT CE.CongressNo FROM CongressEmployee CE INNER JOIN Employee E ON CE.EmployeeNo = E.EmployeeNo WHERE E.Username = ?", array($username));
            $congressesarray = array();
            if ($result){
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                    array_push($congressesarray, $row['CongressNo']);
                }

                return $congressesarray;
            }
        }
    }
?>