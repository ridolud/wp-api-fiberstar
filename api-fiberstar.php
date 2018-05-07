<?php
if ( ! defined( 'ABSPATH' ) ) {exit;}
/*
Plugin Name: API Fiberstar
Plugin URI: #
Description: Get API from fiberstar.co.id
Author: ridolud
Version: dev-0.1
Author URI: #
*/
define('FIBERSTAR_PLUGIN_FILE_PATH',	__DIR__);
define('FIBERSTAR_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ));

include ( FIBERSTAR_PLUGIN_FILE_PATH . '/class-api-fiberstar.php');


// echo json_encode($fiberstar->getKota());
// echo "<br><br>";
// echo json_encode($fiberstar->getKecamatan('Kota Jakarta Timur'));
// echo "<br><br>";
// echo json_encode($fiberstar->getKelurahan('Kota Jakarta Timur', 'Bidara cina'));
// echo "<br><br>";
// echo json_encode($fiberstar->getJalan('Kota Jakarta Timur', 'Bidara cina', 'Jatinegara'));

// die();

function add_shortcode_converage_form($attrs, $content = null)
{
	$form = '<div class="fiberstar-form">'. do_shortcode( $content ) . '</div>';

	echo $form;

	$fiberstar_option = array(
  			'ajax_url' => admin_url( 'admin-ajax.php' ),
  			'form_option' => $attrs,
	);
    wp_register_script( 'fiberstar-form', FIBERSTAR_PLUGIN_DIR_URL . 'fiberstar_form.js', array('jquery'), true);
	wp_localize_script( 'fiberstar-form', 'fiberstar_option', $fiberstar_option );
	wp_enqueue_script( 'fiberstar-form' );

	
}
add_shortcode('fiberstar_converage', 'add_shortcode_converage_form');

function fiberstar_ajax()
{
	$id_kota = @$_POST['id_kota'];
	$id_kecamatan = @$_POST['id_kecamatan'];
	$id_kelurahan = @$_POST['id_kelurahan'];


	$fiberstar = new ApiFiberstar();

	if ($id_kota && $id_kecamatan && $id_kelurahan) {
		echo json_encode($fiberstar->getJalan($id_kota, $id_kecamatan, $id_kelurahan));
	}elseif ($id_kota && $id_kecamatan) {
		echo json_encode($fiberstar->getKelurahan($id_kota, $id_kecamatan));
	}elseif ($id_kota) {
		echo json_encode($fiberstar->getKecamatan($id_kota));
	}else{
		echo json_encode($fiberstar->getKota());
	}
	die();
}

add_action('wp_ajax_nopriv_fiberstar_get_data', 'fiberstar_ajax');
add_action('wp_ajax_fiberstar_get_data', 'fiberstar_ajax');
