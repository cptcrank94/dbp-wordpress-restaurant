<div class="wrap">
    <h1>Restaurant Menu Card Plugin</h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
		<li class="active"><a href="#tab-1">Speisekarten</a></li>
		<li><a href="#tab-2">Speisekarte hinzufügen</a></li>
	</ul>

    <div class="tab-content">
		<div id="tab-1" class="tab-pane active">

            <?php
                require_once ( RMC_PLUGIN_DIR . '/admin/includes/class-restaurantmenu-list-table.php' );
                $args = array(
					'columns' => array(
						'cb'=>__('ID','abana'),
						'menuName'=>__('Name','abana'),
						'menu_shortcode' => __('Shortcode', 'abana'),
					),
					'sort_columns' => array(
						'id'=>array('id', true),
						'menuName'=>array('menuName', true),
					),
					'bulk_actions' => array(
						"edit" => "Bearbeiten",
						"delete" => "Löschen",
					),
					'table_name' => 'mcp_menu_list',
					'search' => array( 'id' ),
				);
                $wp_list_table = new RMC_RestaurantMenu_Table($args);
                $wp_list_table->prepare_items();
                $wp_list_table->display();
            ?>
			
		</div>

		<div id="tab-2" class="tab-pane">
			<h3>Template</h3>
		</div>

	</div>    
</div>