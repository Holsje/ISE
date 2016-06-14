 <?php   
	require_once('connectDatabasePublic.php');
	require_once('database.php');
	require_once('ScreenCreator/createScreen.php');
	require_once('pageConfig.php');
	
	class Registration {
		
		private $screenCreator;
		private $database;
		
        public function __construct() {
			global $server, $databaseName, $uid, $password;
            $this->database = new Database($server, $databaseName, $uid, $password);
			$this->screenCreator = new CreateScreen();
        }
		
		public function createRegistrationScreen() {
			$firstNameObject = new Text(null, $_SESSION['translations']['firstNameRegister'], "firstName", null, true, true, true);
			$lastNameObject = new Text(null, $_SESSION['translations']['lastNameRegister'], "lastName", null, true, true, true);
			$emailObject = new Text(null, $_SESSION['translations']['emailLabel'], "mailAddress", null, true, true, true);
			$phoneNum = new Text(null, $_SESSION['translations']['phoneNumberRegister'], "phoneNum", null, true, true, true);
			$password = new Password(null, $_SESSION['translations']['passwordLabel'], "password", null, true, true, true);
			$submit = new Button($_SESSION['translations']['sendRegister'], null, "registrationSubmit", "btn btn-default pull-right", true, true, null);
			$this->screenCreator->createPopUp(array($firstNameObject, $lastNameObject, $phoneNum, $emailObject, $password, $submit), $_SESSION['translations']['registerTitle'], "Registration",'smallPop','','','');
		}
		
		public function addRecord($storedProcName, $params) {
			$execString = "{call " . $storedProcName . "(";
			for($i = 0;$i<sizeof($params)-1;$i++) {
				$execString .= " ?,";
			}
			$execString .= " ?)}";
			return $this->database->sendQuery($execString,$params);
		}
		
		public function getDatabase() {
			return $this->database;
		}
	}
 ?>
