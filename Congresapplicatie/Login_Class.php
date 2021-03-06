<?php
/**
 * Created by PhpStorm.
 * User: erike
 * Date: 26-4-2016
 * Time: 14:29
 */
require_once('database.php');
require_once('ScreenCreator/CreateScreen.php');
require_once('connectDatabasePublic.php');
require_once('pageConfig.php');


    class Login{
        private $database;
        private $createScreen;

        public function __construct(){
            global $server, $databaseName, $uid, $password;
            $this->database = new Database($server, $databaseName, $uid, $password);
            $this->createScreen = new CreateScreen();
        }

        public function createLoginScreen($css) {
            $inputUsername = new Text(null, $_SESSION['translations']['emailLabel'], "input-username", null, true, true, true);
            $inputPassword = new Password(null, $_SESSION['translations']['passwordLabel'], "input-password", null, true, true, true);
            $submitLogin = new Submit($_SESSION['translations']['submitLogin'], null, "submitLogin", null, false, true);
            //public function __construct($value, $label, $name, $classes, $startRow, $endRow, $datafile){
            $openRegistration = new Button($_SESSION['translations']['Registreren'],null,"Registreren",'col-md-4 btn btn-default popupButton',true,false,'#popUpRegistration');
            $loginScreenObjects = array();
            array_push($loginScreenObjects, $inputUsername);
            array_push($loginScreenObjects, $inputPassword);
            if(isset($_SESSION['loginFail'])){
                $errorText = new Span($_SESSION['loginFail'],'','','col-md-offset-4 col-md-8 col-xs-12 errText',true,true);
                array_push($loginScreenObjects,$errorText);
            }
            array_push($loginScreenObjects,$openRegistration, $submitLogin);

            $this->createScreen->createPopup($loginScreenObjects, $_SESSION['translations']['loginTitle'], "Login", 'smallPop','first',$css,'');
        }

        public function checkLogin($username, $password){

            $result = $this->database->sendQuery("SELECT P.PersonNo, P.FirstName, P.LastName FROM Visitor V INNER JOIN Person P ON V.PersonNo = P.PersonNo WHERE P.MailAddress = ? AND V.Password = ?", array($username, hash('sha256', $password)));

            if ($result){
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                {
                    $_SESSION['userWeb'] = $row['FirstName'] . ' '. $row['LastName'];
                    $_SESSION['userPersonNo'] = $row['PersonNo'];
                    return true;
                }
                return false;
            }
        }

    }
