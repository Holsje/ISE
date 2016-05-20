<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 26-4-2016
 * Time: 14:29
 */
require_once('database.php');
require_once('ScreenCreator/CreateScreen.php');
require_once('connectDatabase.php');
require_once('pageConfig.php');


    class Login{
        private $database;
        private $createScreen;

        public function __construct(){
            global $server, $databaseName, $uid, $password;
            $this->database = new Database($server, $databaseName, $uid, $password);
            $this->createScreen = new CreateScreen();
        }

        public function createLoginScreen() {
            $inputUsername = new Text(null, "Gebruikersnaam", "input-username", null, true, true, true);
            $inputPassword = new Password(null, "Wachtwoord", "input-password", null, true, true);
            $submitLogin = new Submit("Inloggen", null, "submitLogin", null, true, true);
            $loginScreenObjects = array($inputUsername, $inputPassword, $submitLogin);
            $this->createScreen->createPopup($loginScreenObjects, "Inloggen", "Login", 'smallPop');
        }

        public function checkLogin($username, $password){

            $result = $this->database->sendQuery("SELECT 1 FROM Visitor V INNER JOIN Person P ON V.PersonNo = P.PersonNo WHERE P.MailAddress = ? AND V.Password = ?", array($username, hash('sha256', $password)));

            if ($result){
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                {
                    return true;
                }
                return false;
            }
        }

    }
