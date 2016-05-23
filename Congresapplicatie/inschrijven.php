<?php
	require_once('inschrijven_class.php');
	require_once('Index_Class.php');
	topLayout('Inschrijven',null,null);
	$inschrijven = new Inschrijven();
	$index = new Index();
?>
	 <div class="row">
        <div class="container   col-md-12 col-xs-12">
            <div class="content col-sm-9 col-sm-offset-1 col-md-9 col-md-offset-1 col-xs-9 col-xs-offset-1">
				<div class="row">
					<div id="Content" style="background-color:#FFF;" class="col-sm-12 col-md-12 col-xs-12">
						<?php
							$inschrijven->createSchedule();
						?>
				</div>
			</div>
		</div>
	</div>

<?php
	bottomLayout();
$index->createEventInfoPopup();
?>