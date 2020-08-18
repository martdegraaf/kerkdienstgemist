<?php


if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Meldingen_Table extends WP_List_Table {
    
    public $data = array(

    );

    function getData(){
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM
                    wp_kg_meldingen");
        foreach ($results as $result){
            $item = array(
              'id'=> $result->id,
              'type'=>$result->type,
              'idpage'=>$result->idpage,
              'text'=>$result->text
            );
            array_push($this->data,$item);
        }
    }
    
    function get_columns(){
        $columns = array(
            'id' => 'ID',
            'text' => 'Tekst',
            'type' => 'Type',
            'idpage' => 'ID Pagina'
        );
        return $columns;
    }

    function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->data;
        
    } 
            
   
    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'id':
            case 'type':
            case 'text':
            case 'idpage':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
}

