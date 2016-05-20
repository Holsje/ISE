<?php
	require_once('inschrijven_class.php');
	topLayout('Inschrijven',null,null);
	$inschrijven = new Inschrijven();
?>
	 <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">
				<div class="row">
					<div id="Content" style="background-color:#FFF;" class="col-sm-12 col-md-12 col-xs-12">
						<?php
							$inschrijven->createSchedule();
						?>
						<div id="track1" class="col-sm-3 col-md-3 col-xs-3">
							<div id="header" class="col-sm-12 col-md-12 col-xs-12" style="height:100px"><h1>Track 1</h1></div>
							<div id="track1" class="col-sm-12 col-md-12 col-xs-12" style="margin-top:0px; border-style:solid; border-width:1px; background-color:#F0F0F0; height:1300px;">
								<?php
									$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","position:absolute; top:112px; height:400px; width:90%; left:5%; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","position:absolute; top:612px; height:300px; width:90%; left:5%; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","13:00 - 16:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
								?>
							</div>
						</div>
						<div id="track2" class="col-sm-3 col-md-3 col-xs-3">
							<div id="header" class="col-sm-12 col-md-12 col-xs-12" style="height:100px"><h1>Track 2</h1></div>
							<div id="track2" class="col-sm-12 col-md-12 col-xs-12" style="margin-left:10px; margin-top:0px; border-style:solid; border-width:1px; background-color:#F0F0F0; height:1300px;">
								<?php
									$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","position:absolute; top:12px; height:300px; width:90%; left:5%; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","07:00 - 10:00");
									$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","position:absolute; top:612px; height:300px; width:90%; left:5%; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","13:00 - 16:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
									//$CreateScreen->createEventInfo("Event","Dit is een heel erg mooie maar redelijk lange Omschrijving want dit event gaat eigenlijk meer om te testen of dit werkt met lange tekst en anders niet","id","#popupNaam","col-sm-12 col-md-12 col-xs-12","height:" . rand(300,400) . "px; background-color:#FFF;","https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTJCbCNisYubIecn5aFi7J1T0SfD-zK9VCzCzp4FnOpRhFsjpeyXw","08:00 - 12:00");
								?>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	bottomLayout();

?>