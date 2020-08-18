<?php

/**
 * Plugin Name: Kerkdienst Gemist
 * Plugin URI: https://www.gkvhetlichtpunt.nl/
 * Description: Opnames van Vimeo weergeven op de site
 * Version: 2.0
 * Author: Mart de Graaf en Mark van der Laan
 * Author URI: http://markvdlaan.nl
 **/

add_action('admin_menu', 'show_menu');
$meldigen_id = "meldingen";

function show_menu() {

    //create new top-level menu
    add_menu_page('Kerkdienst Gemist', 'Kerkdienst Gemist', 'administrator', __FILE__, 'settings' , plugins_url('/images/Vimeo.png', __FILE__) );
    //add_submenu_page( 'kerkdienstgemist/kerkdienstgemist.php', 'Meldingen', 'Meldingen', 'manage_options', 'kerkdienstgemist_meldingen', 'showMeldingen' );

}

function settings(){
    if(isset($_POST['send'])){
        update_option('vimeo_username', $_POST['vimeo_username']);
        update_option('vimeo_user_id', $_POST['vimeo_user_id']);
        update_option('vimeo_client_id', $_POST['vimeo_client_id']);
        update_option('vimeo_client_secret', $_POST['vimeo_client_secret']);
        update_option('vimeo_access_token', $_POST['vimeo_access_token']);
    }
    echo '<div class="wrap">
        <form action="" method="POST">
        <table class="form-table">

        <tr valign="top">
        <th scope="row">Vimeo gebruikersnaam</th>
        <td><input type="text" name="vimeo_username" value="'.esc_attr( get_option('vimeo_username') ).'" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Vimeo userid</th>
        <td><input type="text" name="vimeo_user_id" value="'.esc_attr( get_option('vimeo_user_id') ).'" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Vimeo client id</th>
        <td><input type="text" name="vimeo_client_id" value="'.esc_attr( get_option('vimeo_client_id') ).'" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Vimeo client secret</th>
        <td><input type="text" name="vimeo_client_secret" value="'.esc_attr( get_option('vimeo_client_secret') ).'" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Vimeo access token</th>
        <td><input type="text" name="vimeo_access_token" value="'.esc_attr( get_option('vimeo_access_token') ).'" /></td>
        </tr>
        <tr>
        <th></th>
            <td><input name="send"type="submit" value="submit"/></td>
        </form>
    </table>
    
    <p>Gebruik <b>[kerkdienstgemist]</b> voor het plaatsen op een pagina.</p>
    
    <p>Zoek jouw Client id, secret en access token op via <a href="https://developer.vimeo.com">https://developer.vimeo.com</a>.</p>
    </div>';       
}

function showMeldingen(){
    include 'meldingenTable.php';
    
    $table = new Meldingen_Table();
    $table->getData();
    $table->prepare_items();
    
    echo '<div class="wrap">';
    
    echo '<h1>Meldingen <a href="//gkvtest/wp-admin/post-new.php" class="page-title-action">Nieuwe melding</a></h1>';
    $table->display();
    
    echo '</div>';
    
}


function getMelding($id){
    global $wpdb;
    
    $results = $wpdb->get_results("SELECT * FROM wp_kg_meldingen WHERE idpage=".$id);
    $melding = "";
    foreach($results as $result){
        $melding .= "<div class='orangebox'>".$result->text."</div>";
    }
    return $melding;
}

function showKerkdienstGemist( $atts ){
    wp_register_style('showKerkdienstGemist', plugins_url('stylesheet.css',__FILE__ ));
    wp_enqueue_style('showKerkdienstGemist');
    //wp_enqueue_script("jquery");
    include 'gemist.snippet.php';
}
add_shortcode( 'kerkdienstgemist', 'showKerkdienstGemist' );

