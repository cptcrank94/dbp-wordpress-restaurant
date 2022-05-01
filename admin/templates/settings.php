<div class="wrap">
    <h1>Restaurant Menu Card Plugin</h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
		<li class="active"><a href="#tab-1">Allgemeine Einstellungen</a></li>
		<li><a href="#tab-2">Template</a></li>
		<li><a href="#tab-3">Info</a></li>
	</ul>

    <div class="tab-content">
		<div id="tab-1" class="tab-pane active">

            <form action="options.php" method="post">
                <?php 
					settings_fields("rmc_general_settings_section");
					do_settings_sections("rmc_general");
                    submit_button(); 
                ?>
            </form>
			
		</div>

		<div id="tab-2" class="tab-pane">
			<h3>Template</h3>
		</div>

		<div id="tab-3" class="tab-pane">
			<h3>Info</h3>
		</div>
	</div>    
</div>