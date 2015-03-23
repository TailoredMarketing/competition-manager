<?php

class comp_list extends WP_List_Table {
    
    function __construct( ){
        global $status, $page;
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'competition',     //singular name of the listed records
            'plural'    => 'competitions',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }

    function column_default($item, $column_name){
        return $item[$column_name];
    }

    function column_title($item){
        
         if( !isset( $_REQUEST['post_status'] ) || $_REQUEST['post_status'] != 'trash' ) {
            //Build row actions
            $actions = array(
                'edit'      => sprintf('<a href="?page=%s&action=%s&wp_comp_man=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
                'delete'    => sprintf('<a href="?page=%s&action=%s&wp_comp_man=%s">Trash</a>',$_REQUEST['page'],'trash',$item['ID']),
            );
            return sprintf('<strong><a href="?page=%3$s&action=edit&wp_comp_man=%1$s">%2$s</a></strong>%4$s',
            /*$1%s*/ $item['ID'],
            /*$2%s*/ $item['title'],
                     $_REQUEST['page'],
            /*$3%s*/ $this->row_actions($actions)
            );
         } else {
            $actions = array(
                'restore'      => sprintf('<a href="?page=%s&action=%s&wp_comp_man=%s">Restore</a>',$_REQUEST['page'],'restore',$item['ID']),
                'delete'    => sprintf('<a href="?page=%s&action=%s&wp_comp_man=%s">Delete Permanently</a>',$_REQUEST['page'],'delete',$item['ID']),
            );
             return sprintf('<strong>%2$s</strong>%4$s',
            /*$1%s*/ $item['ID'],
            /*$2%s*/ $item['title'],
                     $_REQUEST['page'],
            /*$3%s*/ $this->row_actions($actions)
            );
         }
        //Return the title contents
        return sprintf('<strong><a href="?page=%3$s&action=edit&wp_comp_man=%1$s">%2$s</a></strong>%4$s',
            /*$1%s*/ $item['ID'],
            /*$2%s*/ $item['title'],
                     $_REQUEST['page'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
    function column_winner($item) {
        if( isset( $item['winner'] ) || ( isset( $_REQUEST['post_status']) && $_REQUEST['post_status'] == 'trash' ) ) {
            
        } else {
            return sprintf('<a class="button-primary" href="?page=%3$s&action=pick_winner&wp_comp_man=%1$s">%2$s</a>',
                $item['ID'],
                'Pick Winner(s)',
                $_REQUEST['page']
            );   
        }
    }
    
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ 'wp_comp_man',  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'     => 'Name',
            'sdate'     => 'Start Date',
            'edate'     => 'End Date',
            'entries'     => 'Entries',
            'winner'    => 'Winner(s)'
            
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false),     //true means it's already sorted
            'sdate'    => array('sdate',false),
            'edate'    => array('edate',false),
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'trash'    => 'Trash'
        );
        return $actions;
    }

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'trash'===$this->current_action() ) {
            //wp_trash_post( $_REQUEST['wp_comp_man'] );
        }
        
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        $per_page = 10;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $this->process_bulk_action();
        
        if( isset( $_REQUEST['post_status'] ) ) {
            $post_status = $_REQUEST['post_status'];
                $args = array(
                'post_type' => 'wp_comp_man',
                'post_status' => $post_status
            );
        } else {
            $args = array(
                'post_type' => 'wp_comp_man',
            );
        }
        
        $query = new WP_Query( $args );
        
        $data = array();
        $i = 0;
        foreach($query->posts AS $post) {
            setup_postdata($post);
            $post_meta = get_post_meta($post->ID);
            $data[$i] = array(
                'ID' => $post->ID,
                'title' => get_the_title($post->ID),
                'sdate' => $post_meta['wp_comp_sdate'][0],
                'edate' => $post_meta['wp_comp_edate'][0],
                'entries' => $post_meta['wp_comp_entries'][0],
            );
            $i++;
        }
        wp_reset_query();
        //print_var($data);
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'DESC'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        
        $current_page = $this->get_pagenum();
        
        $total_items = count($data);
        
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        $this->items = $data;
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }


}