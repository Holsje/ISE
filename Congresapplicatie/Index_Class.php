<?php
    require_once('pageConfig.php');
    require_once('database.php');
    require_once('ScreenCreator/CreateScreen.php');
    require_once('connectDatabase.php');

    class Index{
        protected $createScreen;
        
        public function __construct(){
            $this->createScreen = new CreateScreen();
        }
        
        function createCongresOverzicht(){
            $button= new Button("test","test","test","form-control col-md-4 pull-right btn btn-default popUpButton moreInfoButton", true, true,'#popUpeventInfo');
            $this->createScreen->createForm(array($button),'eventNo1','');
        }
        
        function createEventInfoPopup(){
            $image = new Img('img/emptyIMG.png','','thumbnail','col-md-4 col-sm-6',true,false);
            $spanDescription = new Span('','','eventDescription','col-md-8 col-sm-6',false,true);
            $spanSubjects = new Span('','Onderwerp(en)','subjects','col-md-12 col-sm-12',true,true);
            $spanSpeakers = new Span('','Spreker(s)','speakers','',true,true);
            $this->createScreen->createPopup(array($image,$spanDescription,$spanSubjects,$spanSpeakers),"","eventInfo",null);
        }
    }
?>
