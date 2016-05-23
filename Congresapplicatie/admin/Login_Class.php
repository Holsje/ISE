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
            $this->getCreateScreen()->createForm($screenObjects, "Login", null);
            if (!empty($errorstring)){
                echo $errorstring;
            }
        }

        public function createLoginScreenLoggedIn(){
            $submitButtonLogout = new Submit("Uitloggen", null, "logout", "form-control col-md-4 col-md-offset-4 btn btn-default", true, true);
            $screenObjects = array($submitButtonLogout);

            $this->getCreateScreen()->createForm($screenObjects, "Logout", null);
        }

        public function checkLogin($username, $password){

            $result = parent::getDatabase()->sendQuery("IF EXISTS (SELECT 1 FROM CongressManager CM INNER JOIN Person P ON CM.PersonNo = P.PersonNo WHERE P.MailAddress = ? AND CM.Password = ?) OR EXISTS (SELECT 1 FROM GeneralManager GM INNER JOIN Person P ON GM.PersonNo = P.PersonNo WHERE P.MailAddress = ? AND GM.Password = ?) BEGIN SELECT 1 END ", array($username, hash('sha256', $password), $username, hash('sha256', $password)));

            if ($result){
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                {
                    return true;
                }
                return false;
            }
        }

        public function checkUser($username){
            $result = parent::getDatabase()->sendQuery("SELECT TypeName FROM Person P INNER JOIN PersonTypeOfPerson PTOP ON P.PersonNo = PTOP.PersonNo WHERE MailAddress = ?", array($username));
            if ($result){
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                    if ($row['TypeName']=="Congresbeheerder"){
                        return $this->getAdminCongresses($username);
                    }
                    elseif ($row['TypeName']=="Algemene beheerder"){
                        return $row['TypeName'];
                    }
                }
            }
        }

        public function getAdminCongresses($username){
            $result = parent::getDatabase()->sendQuery("SELECT COC.CongressNo FROM CongressManagerOfCongress COC INNER JOIN CongressManager CM ON COC.PersonNo = CM.PersonNo WHERE P.MailAddress = ?", array($username));
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