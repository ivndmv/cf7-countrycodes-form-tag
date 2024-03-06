<?php
/*
Plugin Name:  Country codes form tag for Contact Form 7
Description:  This plugin creates the Contact Form 7 form tag [countrycodes] which creates form tag with predefined country phone codes
Version:      1.0
Author:       Ivan Dimitrov
*/
// add_action('init', 'cf7_countrycodes_form_tag_plugin');
add_action( 'wpcf7_init', 'custom_add_form_tag_countrycodes' );
function custom_add_form_tag_countrycodes() {
	wpcf7_add_form_tag(
	'countrycodes',
	'custom_countrycodes_form_tag_handler',
	array( 'name-attr' => true )
	);
}
function custom_countrycodes_form_tag_handler( $tag ) {
	$atts = array(
		'type' => 'select',
		'name' => $tag->name,
		'class' => 'cf7-countrycodes-form-tag'
	);
	$select = sprintf(
		'<select %s />',
		wpcf7_format_atts( $atts )
	);
	//include countries
	$countries_json  = file_get_contents(plugins_url('countries-json/countries.json', __FILE__));
	$countries_array = json_decode($countries_json);

	//make the BG item to be the first  one

	// $first_item = $countries_array[32];
	// unset($countries_array[32]);
	// array_unshift($countries_array, $first_item);

	$countries_array = apply_filters('custom_countries_array', $countries_array);

	//populate options
	foreach ($countries_array as $country) {
		$select .= '<option value="'.$country->dial_code.'">' . $country->code . ' ' . $country->dial_code . '</option>';
	}
	$select .= '</select>';
	return $select;
}
add_action( 'wp_enqueue_scripts', 'add_select2' );
function add_select2() {
	wp_enqueue_style( 'select2-css', plugins_url('select2/css/select2.min.css', __FILE__), array(), '1.1', 'all' );
	wp_enqueue_script( 'select2-js', plugins_url('select2/js/select2.min.js', __FILE__), array( 'jquery' ), 1.1, true );
}
add_action('wp_footer', 'custom_countrycodes_form_tag_js');
function custom_countrycodes_form_tag_js() {
	?><script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('select.cf7-countrycodes-form-tag').select2();
		});
	</script>
<?php }
// /**
//  * Activate the plugin.
//  */
// register_activation_hook( __FILE__, 'cf7_countrycodes_form_tag_activate' );
// function cf7_countrycodes_form_tag_activate() { 
// 	// Trigger our function that registers the custom post type plugin.
// 	// Clear the permalinks after the post type has been registered.
// 	flush_rewrite_rules(); 
// }
// /**
//  * Deactivation hook.
//  */
// register_deactivation_hook( __FILE__, 'cf7_countrycodes_form_tag_deactivate' );
// function cf7_countrycodes_form_tag_deactivate() {
// 	// Clear the permalinks to remove our post type's rules from the database.
// 	flush_rewrite_rules();
// }
?>