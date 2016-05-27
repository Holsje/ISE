 <?php   
	require_once('connectDatabase.php');
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
			$firstNameObject = new Text(null, "Voornaam", "firstName", null, true, true, true);
			$lastNameObject = new Text(null, "Achternaam", "lastName", null, true, true, true);
			$emailObject = new Text(null, "Mailadres", "mailAddress", null, true, true, true);
			$phoneNum = new Text(null, "Telefoonnummer", "phoneNum", null, true, true, true);
			$password = new Password(null, "Wachtwoord", "password", null, true, true);
			$submit = new Button("Registreer", null, "registrationSubmit", "btn btn-default pull-right", true, true, null);
			$this->screenCreator->createPopUp(array($firstNameObject, $lastNameObject, $emailObject, $phoneNum, $password, $submit), "Registreren", "Registration",'smallPop','');
		}
		
		public function addRecord($storedProcName, $params) {
			$execString = "{call " . $storedProcName . "(";
			for($i = 0;$i<sizeof($params)-1;$i++) {
				$execString .= " ?,";
			}
			$execString .= " ?)}";
			return $this->database->sendQuery($execString,$params);
		}
	}
 ?>
