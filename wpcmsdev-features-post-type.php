<?php
/*
Plugin Name: wpCMSdev Features Post Type
Plugin URI:  http://wpcmsdev.com/plugins/features-post-type/
Description: Registers a "Features" custom post type.
Author:      wpCMSdev
Author URI:  http://wpcmsdev.com
Version:     1.0
Text Domain: wpcmsdev-features-post-type
Domain Path: /languages
License:     GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Copyright (C) 2014  wpCMSdev

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


/**
 * Registers the "feature" post type.
 */
function wpcmsdev_features_post_type_register() {

	$labels = array(
		'name'               => __( 'Features',                    'wpcmsdev-features-post-type' ),
		'singular_name'      => __( 'Feature',                     'wpcmsdev-features-post-type' ),
		'all_items'          => __( 'All Features',                'wpcmsdev-features-post-type' ),
		'add_new'            => _x( 'Add New', 'feature',          'wpcmsdev-features-post-type' ),
		'add_new_item'       => __( 'Add New Feature',             'wpcmsdev-features-post-type' ),
		'edit_item'          => __( 'Edit Feature',                'wpcmsdev-features-post-type' ),
		'new_item'           => __( 'New Feature',                 'wpcmsdev-features-post-type' ),
		'view_item'          => __( 'View Feature',                'wpcmsdev-features-post-type' ),
		'search_items'       => __( 'Search Features',             'wpcmsdev-features-post-type' ),
		'not_found'          => __( 'No features found.',          'wpcmsdev-features-post-type' ),
		'not_found_in_trash' => __( 'No features found in Trash.', 'wpcmsdev-features-post-type' ),
	);

	$args = array(
		'labels'        => $labels,
		'menu_icon'     => 'dashicons-editor-ul',
		'menu_position' => 5,
		'public'        => false,
		'show_ui'       => true,
		'supports'      => array(
			'author',
			'custom-fields',
			'excerpt',
			'editor',
			'page-attributes',
			'revisions',
			'title',
		),
	);

	$args = apply_filters( 'wpcmsdev_features_post_type_args', $args );

	register_post_type( 'feature', $args );

}
add_action( 'init', 'wpcmsdev_features_post_type_register' );


/**
 * Loads the translation files.
 */
function wpcmsdev_features_load_translations() {

	load_plugin_textdomain( 'wpcmsdev-features-post-type', false, dirname( plugin_basename( __FILE__ ) ) ) . '/languages/';
}
add_action( 'plugins_loaded', 'wpcmsdev_features_load_translations' );


/**
 * Initializes additional functionality when used with a theme that declares support for the plugin.
 */
function wpmcsdev_features_additional_functionality_init() {

	if ( current_theme_supports( 'wpcmsdev-features-post-type' ) ) {
		add_action( 'admin_enqueue_scripts',              'wpcmsdev_features_manage_posts_css' );
		add_action( 'manage_feature_posts_custom_column', 'wpcmsdev_features_manage_posts_columm_content' );
		add_filter( 'cmb2_meta_boxes',                    'wpcmsdev_features_meta_box' );
		add_filter( 'manage_edit-feature_columns',        'wpcmsdev_features_manage_posts_columns' );
	}
}
add_action( 'after_setup_theme', 'wpmcsdev_features_additional_functionality_init', 11 );


/**
 * Registers custom columns for the Manage Features admin page.
 */
function wpcmsdev_features_manage_posts_columns( $columns ) {

	$column_order = array( 'order' => __( 'Order', 'wpcmsdev-features-post-type' ) );

	$columns = array_slice( $columns, 0, 2, true ) + $column_order + array_slice( $columns, 2, null, true );

	return $columns;
}


/**
 * Outputs the custom column content for the Manage Features admin page.
 */
function wpcmsdev_features_manage_posts_columm_content( $column ) {

	global $post;

	switch( $column ) {

		case 'order':
			$order = $post->menu_order;
			if ( 0 === $order ) {
				echo '<span class="default-value">' . $order . '</span>';
			} else {
				echo $order;
			}
			break;
	}
}


/**
 * Outputs the custom columns CSS used on the Manage Features admin page.
 */
function wpcmsdev_features_manage_posts_css() {

	global $pagenow, $typenow;
	if ( ! ( 'edit.php' == $pagenow && 'feature' == $typenow ) ) {
		return;
	}

?>
<style>
	.edit-php .posts .column-order {
		width: 10%;
	}
	.edit-php .posts .column-order .default-value {
		color: #bbb;
	}
</style>
<?php
}


/**
 * Creates the Feature Settings meta box and fields.
 */
function wpcmsdev_features_meta_box( $meta_boxes ) {

	$meta_boxes['feature-settings'] = array(
		'id'           => 'feature-settings',
		'title'        => __( 'Feature Settings', 'wpcmsdev-features-post-type' ),
		'object_types' => array( 'feature' ),
		'fields'       => array(
			array(
				'name' => __( 'Icon', 'wpcmsdev-features-post-type' ),
				'desc' => sprintf( __( 'See the <a href="%s" target="_blank">Font Awesome Cheatsheet</a> for a complete list of the available icons. Do not include the <code>fa-</code> prefix.', 'wpcmsdev-features-post-type' ), 'http://fortawesome.github.io/Font-Awesome/cheatsheet/' ),
				'id'   => 'icon_id',
				'type' => 'text',
			),
		),
	);

	return $meta_boxes;
}
