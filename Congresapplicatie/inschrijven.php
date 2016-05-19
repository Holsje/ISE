<?php
	require_once('inschrijven_class.php');
	topLayout('Inschrijven',null,null);
?>

	 <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
				<div class="row">
					<?php
						for($i=0;$i<50;$i++)
							$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id$i","#popupNaam" . $i,"col-sm-3 col-md-3 col-xs-3","margin-right:50px; margin-bottom:50px; height:" . rand(200,800) . "px","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
							
					?>
				</div>
			</div>
		</div>
	</div>

<?php
	bottomLayout();

?>