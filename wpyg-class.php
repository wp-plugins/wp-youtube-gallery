<?php 
/** Plugin created by raghunath gurjar 
 * Create a new youtube post type
 * define the all options for new post type
 * @init
 * @register_post_type
 * @add_meta_boxes
 * */

add_action('init','add_wp_youtube_post_type');
if(!function_exists('add_wp_youtube_post_type')){ 
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
 if(!function_exists('add_wp_youtube_gallery_meta_box')){ 
function add_wp_youtube_gallery_meta_box()
{
 global $meta_box;
    add_meta_box($meta_box['id'], $meta_box['title'], 'show_wp_youtube_gallery_meta_box','wp_youtube_gallery', $meta_box['context'], $meta_box['priority']);
}
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
if(!function_exists('show_wp_youtube_gallery_meta_box')){ 
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
}

//Define action for save to "WP Youtube Gallery" Meta Box fields Value
add_action( 'save_post', 'save_wp_youtube_gallery_meta_box' );
if(!function_exists('save_wp_youtube_gallery_meta_box')){ 
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
}

/* 
 * Register New "WP Youtube Gallery" Texonimoies
 * @register_taxonomy()
 * 
 * */

// define init action and call create_wp_youtube_gallery_taxonomies when it fires
add_action( 'init', 'create_wp_youtube_gallery_taxonomies', 0 );
if(!function_exists('create_wp_youtube_gallery_taxonomies')){ 
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
		'rewrite'           => array( 'slug' => 'wp_youtube_gallery_tax', 'with_front' => true,'hierarchical' => false),
	);

	register_taxonomy( 'wp_youtube_gallery_taxonomy', array( 'wp_youtube_gallery' ), $args );
}
}
/* End Youtube Taxonomies */

/** add css to wp_head */
add_action('wp_enqueue_scripts','add_wpyg_style');
if(!function_exists('add_wpyg_style')){ 
function add_wpyg_style()
{
wp_enqueue_script( 'jquery' ); // wordpress jQuery
wp_register_style( 'wpyg_style', plugins_url( 'css/wpyg.css',__FILE__ ) );
wp_enqueue_style( 'wpyg_style' );
wp_register_style( 'wpyg_colorbox_style', plugins_url( 'css/colorbox.css',__FILE__ ) );
wp_enqueue_style( 'wpyg_colorbox_style' );
wp_register_script( 'wpyg_colorbox_script', plugins_url( 'js/jquery.colorbox.js',__FILE__ ) );
wp_enqueue_script( 'wpyg_colorbox_script' );
	}
}
/** Add js to head section */
add_action('wp_head','wpyg_load_inline_script');

if(!function_exists('wpyg_load_inline_script')):

function wpyg_load_inline_script()
{
$js='<script>jQuery(document).ready(function(){
				jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
			});</script>';
echo $js;		
	}
endif;


/*
 * Function for get all youtube pages on list page
 * @WP_Query()
 * 
 * */
if(!function_exists('get_wp_youtube_gallery')){ 
function get_wp_youtube_gallery($attr)
{
/* Get Plugin Options */	
$pluginOptions=get_wpyg_admin_options();	

/*Is plugin active*/
$isEnable=$pluginOptions['wpyg_active'];


/*Is lightbox active*/
if(isset($pluginOptions['wpyg_lightbox']))
$isLightboxEnable=$pluginOptions['wpyg_lightbox'];

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



if(isset($attr['catid']) && $attr['catid']!='')
{
	$terms=$attr['catid'];
	$field='id';
	
	}else
{
	$terms=$attr['category_slug'];
	$field='slug';
	}

// Per row	
if(isset($attr['per_row']) && $attr['per_row']!='')
{
	$perrow=$attr['per_row'];
}
	
// Total	
if(isset($attr['total_videos']) && $attr['total_videos']!='')
{
	$numberOfVideo=$attr['total_videos'];
}

// Iframe Height	
if(isset($attr['height']) && $attr['height']!='')
{
$iframeHeight=$attr['height'];
}
	
// Iframe Width	
if(isset($attr['width']) && ''!=$attr['width'])
{
	$iframeWidth=$attr['width'];
}
	
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
            'field' => $field,
            'terms' => $terms
        )
    )
);
$wpyg_query = new WP_Query( $args );

$wpyg_content='';
if($wpyg_query->have_posts()):
$wpyg_content .="<div class='wp_youtube_gallery_block'><table class='wp_youtube_gallery'><tr>";
$ij=1;
$totalposts=$wpyg_query->found_posts;

$widthAvg=((100-($perrow*2))/$perrow);

if(isset($isLightboxEnable) && $isLightboxEnable==1):
while ( $wpyg_query->have_posts() ) : $wpyg_query->the_post();

       $videoId=stripslashes(get_post_meta(get_the_ID(),'wpyg_video-id',true));
         $wpyg_content .='
                    	
                            <td class="youtubetext" style="width:'.$widthAvg.'%;">
                            		<h3>'.get_the_title().'</h3>
                                    <div class="youtubevideo" >
                                    <a href="http://www.youtube.com/embed/'.$videoId.'?rel=0&wmode=transparent" class="youtube cboxElement" title="<b>'.get_the_title().'</b>: '.strip_tags(get_the_content()).'"><img src="http://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg" /></a>
                                    </div>
                                    
                            </td>
                        ';
        if($perrow==$ij)     
        {
			$wpyg_content .='</tr><tr>';
			$ij=0;
			//$ij--; 
			}   
      
       $ij++;           
	endwhile;
else :
while ( $wpyg_query->have_posts() ) : $wpyg_query->the_post();

       $videoId=stripslashes(get_post_meta(get_the_ID(),'wpyg_video-id',true));
	   
	  $wpyg_content .='
                    	
                            <td class="youtube" style="width:'.$widthAvg.'%;">
                            		<h3>'.get_the_title().'</h3>
                                    <div class="youtubevideo" ><iframe width="'.$iframeWidth.'" height="'.$iframeHeight.'" src="//www.youtube.com/embed/'.$videoId.'" frameborder="0" allowfullscreen></iframe>
                                    <div class="content">'.get_the_content().'</p>
                                    </div>
                                    
                            </td>
                        ';

         
        if($perrow==$ij)     
        {
			$wpyg_content .='</tr><tr>';
			$ij=0;
			//$ij--; 
			}   
      
       $ij++; 
                 
	endwhile;
endif;

	$wpyg_content .="</tr></table></div>";
	wp_reset_query();
	return $wpyg_content;
	endif;
}
}
	
/* ADD NEW SHORT CODE FOR PUBLISH ALL youtube post ON LIST PAGE */
add_shortcode('wp_youtube_gallery','get_wp_youtube_gallery'); 
// use [wp_youtube_gallery catid="ENTER CATEGORY ID"] shortcode


// get all options value for "WP Youtube gallery"
if(!function_exists('get_wpyg_admin_options')){ 
	function get_wpyg_admin_options() {
		global $wpdb;
		$wpygOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'wpyg_%'");
								
		foreach ($wpygOptions as $option) {
			$wpygOptions[$option->option_name] =  $option->option_value;
		}
	
		return $wpygOptions;	
	}
}
