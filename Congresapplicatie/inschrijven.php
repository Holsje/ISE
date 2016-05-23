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
				</div>
			</div>
		</div>
	</div>

<?php
	bottomLayout();

?>