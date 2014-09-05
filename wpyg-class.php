<?php 
/** Plugin created by raghunath gurjar 
 * Create a new youtube post type
 * define the all options for new post type
 * @init
 * @register_post_type
 * @add_meta_boxes
 * */

add_action('init','add_wp_youtube_post_type');

function add_wp_youtube_post_type()
{
	$labels = array(
	   'name'=>__('WP Youtube Gallery'),
	   'singular_name'=>__('WP Youtube Videos'),
	   'edit_item'         => __( 'Edit Youtube Video' ),
	   'update_item'       => __( 'Update Youtube Video' ),
	   'add_new_item'      => __( 'Add New Youtube Video' ),);
	
	register_post_type('wp_youtube_gallery',
	array(
	 'labels'=>$labels,
	 'public'=>true,
	 'has_archive'=>false,
	 'supports'=>array('title','editor'),
	 'rewrite' =>true
	    )
	);
}


/*
 * Add New Meta Box Field For WP Youtube Gallery
 * define all meta boxes that will be publish on WP Youtube Gallery posts 
 * */

//define action for create new meta boxes
add_action( 'add_meta_boxes', 'add_wp_youtube_gallery_meta_box' );

/**
 * Adds the WP Youtube Gallery meta box
 */
function add_wp_youtube_gallery_meta_box()
{
 global $meta_box;
    add_meta_box($meta_box['id'], $meta_box['title'], 'show_wp_youtube_gallery_meta_box','wp_youtube_gallery', $meta_box['context'], $meta_box['priority']);
}

//Define meta box fields

  $prefix = 'wpyg_';
    $meta_box = array(
    'id' => 'youtube-meta-box',
    'title' => 'Extra Information',
    'page' => '',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
    array(
    'name' => 'Youtube Video ID: ',
    'desc' => 'Example: https://www.youtube.com/watch?v=EKyirtVHsK0 , Here video id is "<b>EKyirtVHsK0</b>"',
    'id' => $prefix . 'video-id',
    'type' => 'text',
    'std' => ''
    )
    )
    );


//Display WP Youtube Gallery Meta Box on youtube pages
function show_wp_youtube_gallery_meta_box()
{
global $meta_box, $post;
    wp_nonce_field( 'wp_youtube_gallery_box_field', 'wp_youtube_gallery_box_meta_box_once' );

    foreach ($meta_box['fields'] as $field) {
    // get current post meta data
   
    $meta = get_post_meta($post->ID, $field['id'], true);
    echo '<p>',
    '<label for="', $field['id'], '">', $field['name'], '</label>','';
    switch ($field['type']) {
    case 'text':
    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
    break;
    case 'textarea':
    echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
    break;
    case 'select':
    echo '<select name="', $field['id'], '" id="', $field['id'], '" >';
    $optionVal=explode(',',$field['options']);
    foreach($optionVal as $optVal):
    if($meta==$optVal){
    $valseleted =' selected="selected"';}else {
		 $valseleted ='';
		}
    echo '<option value="', $optVal, '" ',$valseleted,' id="', $field['id'], '">', $optVal, '</option>';
    endforeach;
    echo '</select>',$field['desc'];
    break;
    '</p>';
    }

    }
}

//Define action for save to "WP Youtube Gallery" Meta Box fields Value
add_action( 'save_post', 'save_wp_youtube_gallery_meta_box' );

function save_wp_youtube_gallery_meta_box($post_id) {
	global $meta_box;
	// Check if our nonce is set.
	 if ( ! isset( $_POST['wp_youtube_gallery_box_meta_box_once'] ) ) {
			return;
		}
		
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	return $post_id;
	}

	// check permissions
	if ('wp_youtube_gallery' == $_POST['post_type']) 
	{
		if (!current_user_can('edit_page', $post_id))
		return $post_id;
	} 
	elseif(!current_user_can('edit_post', $post_id)){
	return $post_id;
	}
	
	foreach ($meta_box['fields'] as $field) 
	{
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old){
		 update_post_meta($post_id, $field['id'], $new);
		} 
		elseif ('' == $new && $old) {
		delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

/* 
 * Register New "WP Youtube Gallery" Texonimoies
 * @register_taxonomy()
 * 
 * */

// define init action and call create_wp_youtube_gallery_taxonomies when it fires
add_action( 'init', 'create_wp_youtube_gallery_taxonomies', 0 );

function create_wp_youtube_gallery_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Category' ),
		'all_items'         => __( 'All Category' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Category Name' ),
		'menu_name'         => __( 'Categories' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'wp_youtube_gallery_tax', 'with_front' => true,'hierarchical' => true),
	);

	register_taxonomy( 'wp_youtube_gallery_taxonomy', array( 'wp_youtube_gallery' ), $args );
}

/* End Youtube Taxonomies */


/*
 * Function for get all youtube pages on list page
 * @WP_Query()
 * 
 * */
function get_wp_youtube_gallery($attr)
{
/* Get Plugin Options */	
$pluginOptions=get_wpyg_admin_options();	

/*Is plugin active*/
$isEnable=$pluginOptions['wpyg_active'];

/*Show Number Of Videos*/
$numberOfVideo=stripslashes($pluginOptions['wpyg_number_posts']);
if($numberOfVideo==''){$numberOfVideo='9';}

/*Youtube Video Iframe width*/
$iframeWidth=stripslashes($pluginOptions['wpyg_iframe_w']);
if($iframeWidth==''){$iframeWidth='250';}

/*Youtube Video Iframe height*/
$iframeHeight=stripslashes($pluginOptions['wpyg_iframe_h']);
if($iframeHeight==''){$iframeHeight='200';}

/*Number Of Video Per Row*/
$perrow=stripslashes($pluginOptions['wpyg_per_row_posts']);
if($perrow==''){$perrow='3';}


$catid=$attr['catid'];

wp_reset_query();
$args = array(
    'post_type' => 'wp_youtube_gallery',
    'post_status' => 'publish',
    'posts_per_page' => $numberOfVideo,
    'show_ui'            => true,
	'show_in_menu'       => true,
	'menu_position' =>5,
    'tax_query' => array(
        array(
            'taxonomy' => 'wp_youtube_gallery_taxonomy',
            'field' => 'id',
            'terms' => $catid
        )
    )
);
$wpyg_query = new WP_Query( $args );

$wpyg_content='';
if($wpyg_query->have_posts()):
$wpyg_content .="<div class='wp_youtube_gallery_block'><table class='wp_youtube_gallery'><tr>";
$ij=1;
while ( $wpyg_query->have_posts() ) : $wpyg_query->the_post();
	   $wpyg_content .='
                    	
                            <td class="youtube">
                            		<h3>'.get_the_title().'</h3>
                                    <div class="youtubevideo clearfix"><iframe width="'.$iframeWidth.'" height="'.$iframeHeight.'" src="//www.youtube.com/embed/'.strip_tags(get_post_meta(get_the_ID(),'yt_video-id',true)).'" frameborder="0" allowfullscreen></iframe></div>
                                    <p class="contet">'.get_the_content().'</p>
                            </td>
                        ';
        if($perrow==$ij)     
        {
			$wpyg_content .='</tr><tr>';
			$ij=1;
			}   
     $ij++;     
                 
	endwhile;
	$wpyg_content .="</tr></table></div>";
	wp_reset_query();
	return $wpyg_content;
	endif;
}
	
/* ADD NEW SHORT CODE FOR PUBLISH ALL youtube post ON LIST PAGE */
add_shortcode('wp_youtube_gallery','get_wp_youtube_gallery'); 
// use [wp_youtube_gallery catid="ENTER CATEGORY ID"] shortcode


// get all options value for "Custom Share Buttons with Floating Sidebar"
	function get_wpyg_admin_options() {
		global $wpdb;
		$wpygOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'wpyg_%'");
								
		foreach ($wpygOptions as $option) {
			$wpygOptions[$option->option_name] =  $option->option_value;
		}
	
		return $wpygOptions;	
	}
