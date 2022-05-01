<?php

if ( ! class_exists( 'WP_List_Table') ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class RMC_RestaurantMenu_Table extends WP_List_Table {

    var $_sort_columns, $_bulk_actions, $_query, $_where, $_table_name, $_columns;

    public static function define_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Title', 'rcm'),
            'shortcode' => __('Shortcode', 'rcm'),
            'date' => __('Date', 'rcm')
        );

        return $columns;
    }

    function __construct( $args ) {
		parent::__construct( array(
			'singular'=> 'wp_review_item', //Singular label
			'plural' => 'wp_review_items', //plural label, also this well be one of the table css class
			'ajax'	=> false //We won't support Ajax for this table
		) );
		if ( is_null($args) ) return false;
		if ( is_array($args) ) {
			foreach ($args as $var => $val) $this->{"_".$var} = $val;
		}
	}

    public function get_columns() {
        return $this->_columns;
    }

    protected function get_sortable_columns() {
        return $this->_sort_columns;
    }

    protected function get_bulk_actions() {
        return $this->_bulk_actions;
    }

    public function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();
        
        // Prepare the query
        if ( !isset( $this->_table_name ) ) {
            return false;
        }

        $tbl = $wpdb->prefix . $this->_table_name;
        $page = @$_GET['page'];
        
        if( !isset( $where) && isset( $this->_where) ) {
            $where = "WHERE ({$this->_where})";
        }

        if ( isset($this->_query) ) $query = $this->_query;
		else $query = "SELECT * FROM $tbl ".(isset($where)?$where:"");

        // Order the query
        $orderBy = !empty( $_GET['orderby'] ) ? $_GET['orderby'] : "";
        $order = !empty( $_GET['order'] ) ? $_GET['order'] : "ASC";
        if ( !empty( $orderBy) && !empty($order) ) {
            $query = ' ORDER BY ' . $orderBy . ' ' . $order;
        }

        // Pagination
        $totalItems = $wpdb->query($query);
        // How many items per page
        $perPage = 20;
        // Which page
        $paged = !empty($_GET['paged']) ? $_GET['paged'] : '';
        // Page number 
        if( empty($paged) || !is_numeric($paged) || $paged < 0 ) {
            $paged = 1;
        }
        // How many pages in total
        $totalPages = ceil($totalitems / $perPage);
        // Take pagination for query
        if( !empty($paged) && !empty($perPage)) {
            $offset = ($paged-1) * $perPage;
            $query .= ' LIMIT ' . (int)$offset . ', ' . (int)$perPage;
        }

        // Register the pagination
        $this->set_pagination_aargs( array( 
            "total_items" => $totalItems,
            "total_pages" => $totalPages,
            "per_page" => $perPage,
        ) );

        // Register columns
        $columns = $this->get_columns();
        $hidden = array('col_id');
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable);

        // Fetch items
        $this->items = $wpdb->get_results( $query );
    }

    public function display_rows() {
        $page = @$_GET['page'];
        $i = 0;

        $records = $this->items;

        list( $columns, $hidden ) = $this->get_column_info();

        if ( !empty( $records ) ) {
            foreach( $records as $rec ) {
                $i++;

                if ( empty( $rec->menu_id ) ) {
                    $checkbox = $rec->item_id;
                } else {
                    $checkbox = $rec->menu_id;
                }

                echo '<tr id="record_'.$checkbox.'" class="'.($i%2?'alternate':'').'">';
                foreach ( $columns as $column_name => $column_display_name ) {
                    // Zelle anzeigen
                    if ( !empty( $rec->menu_id ) ) {
                        $checkbox = '<th scope="row" class="check_column"><label class="screen-reader-text" for="cb-select-' . $rec->menu_id .'">' . $rec->menu_name . ' auswählen</label><input id="cb-select-' . $rec->menu_id .'" type="checkbox" name="post[]" value="'. $rec->menu_id .'" /></th>';
                    } elseif ( !empty( $rec->item_id ) ) {
                        $checkbox = '<th scope="row" class="check_column"><label class="screen-reader-text" for="cb-select-' . $rec->item_id .'">' . $rec->item_name . ' auswählen</label><input id="cb-select-' . $rec->item_id .'" type="checkbox" name="post[]" value="'. $rec->item_id .'" /></th>';
                    } elseif ( !empty ( $rec->cat_id ) ) {
                        $checkbox = '<th scope="row" class="check_column"><label class="screen-reader-text" for="cb-select-' . $rec->cat_id .'">' . $rec->cat_name . ' auswählen</label><input id="cb-select-' . $rec->cat_id .'" type="checkbox" name="post[]" value="'. $rec->cat_id .'" /></th>';
                    }

                    switch ( $column_name ) {
                        
                        case "cb" : echo $checkbox; break;
                        case "cat_id" : echo '<td>' . $rec->cat_id . '</td>'; break;
                        case "menu_id": echo '<td>'. $rec->menu_id .'</td>'; break;
                        case "item_id": echo '<td>'. $rec->item_id .'</td>'; break;
                        case "cat_name": echo '<td>'. $rec->cat_name .'<div class="row-actions"><span class="editItem"><a class="mcpEdit" onclick="getEditData(this.href)" href="'.$editlink.'" title="Bearbeiten">Bearbeiten</a> | </span><span class="trash"><a href="' . $deletelink . '" title="Löschen">Papierkorb</a></span></div></td>'; break;
                        case "menu_name": echo '<td>'. $rec->menu_name .'<div class="row-actions"><span class="editItem"><a class="mcpEdit" onclick="getEditData(this.href)" href="'.$editlink.'" title="Bearbeiten">Bearbeiten</a> | </span><span class="trash"><a href="' . $deletelink . '" title="Löschen">Papierkorb</a></span></div></td>'; break;
                        case "menu_parent": echo '<td>'. $this->getParent( $rec->menu_parent ) .'<div class="row-actions"><span class="editItem"><a class="mcpEdit" onclick="getEditData(this.href)" href="'.$editlink.'" title="Bearbeiten">Bearbeiten</a> | </span><span class="trash"><a href="' . $deletelink . '" title="Löschen">Papierkorb</a></span></div></div></td>'; break;
                        case "menu_shortcode": echo '<td>' . $rec->menu_shortcode . '</td>'; break;
                        case "item_name": echo '<td>'. $rec->item_name . '</td>'; break;
                        case "item_price": echo '<td>'. (empty ($rec->item_price) ? 'Kein Preis angegeben' : $rec->item_price . "€") . '</td>'; break;
                        case "item_cat": echo '<td>'. $this->getCat( $rec->item_cat ) . '</td>'; break;
                        case "item_desc": echo '<td>'. $rec->item_desc . '</td>'; break;
                        case "item_type": echo '<td>' . $itemType . '</td>'; break;
                        case "cat_type" : echo '<td>' . $catType . '</td>'; break;
                        
                    }
                }

                echo '</tr>';
            }
        }
    }

    function getParent( $parent ) {
		global $wpdb;
		$menuItems = $wpdb->prefix . 'mcp_menu_items';
		$listItems = $wpdb->prefix . 'mcp_menu_list';
		
		$sql = "SELECT $menuItems.menu_parent, $listItems.menu_name FROM $menuItems LEFT JOIN $listItems ON $menuItems.menu_parent = $listItems.menu_id WHERE $menuItems.menu_parent = $parent";
		
		$return = $wpdb->get_results($sql);
		
		foreach ( $return as $ret ) {
			return $ret->menu_name;
		}
		
	 }
	 
	 function getCat ( $cat ) {
		 global $wpdb;
		 $catItems = $wpdb->prefix . 'mcp_menu_cat';
		 $menuItems = $wpdb->prefix . 'mcp_menu_items';
		 
		 $sql = "SELECT $menuItems.item_cat, $catItems.cat_name FROM $menuItems LEFT JOIN $catItems ON $menuItems.item_cat = $catItems.cat_id WHERE $menuItems.item_cat = $cat";
		 
		 $return = $wpdb->get_results($sql);
		 
		 foreach ( $return as $ret ) {
			 return $ret->cat_name;
		 }
	 }
}

?>