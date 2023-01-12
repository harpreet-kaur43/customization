<?php

/**
 *
 * @link              https://oxosolutions.com
 * @since             1.0.0
 * @package           Customization
 *
 * @wordpress-plugin
 * Plugin Name:       Customization
 * Plugin URI:        http://oxosolutions.com/products/wordpress-plugins/delete-management/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            SGS Sandhu
 * Author URI:        https://oxosolutions.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       customization
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

include('register.php');
//include('content-managment.php');

/*=====================================*/
/*== Modify the ACF WYSIWYF toolbar. ==*/
/*=====================================*/
add_filter('acf/fields/wysiwyg/toolbars' , 'customize_acf_wysiwyg_toolbar');
function customize_acf_wysiwyg_toolbar( $toolbars ) {
  if (($key = array_search('fontsizeselect' , $toolbars['Full'][2])) !== true) {
    array_push($toolbars['Full'][2], 'fontsizeselect');
  }
  return $toolbars;
}

/*add_action( 'change_job_status_to_active', 'active_status_function' );
function active_status_function() {
    $today = date('Ymd');
    $args = array(
        'post_type' => 'job',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'job_expiry_date',
                'value' => $today,
                'type' => 'DATE',
                'compare' => '>'
            )
        )
    );
    $query = new WP_Query($args);
    $posts = $query->posts;
    foreach($posts as $post) {
        update_post_meta($post->ID, 'status', 'Active');
    }
    /*$post = get_post(55054);
    $post->post_status = 'active';
    wp_update_post($post);
}*/

add_action( 'change_job_status', 'status_function' );
function status_function() {
    $today = date('Y-m-d');
    $args = array(
        'post_type' => 'job',
        'numberposts' => -1,
    );
    $posts = get_posts($args);
    foreach($posts as $post) {
        $meta = get_post_meta($post->ID, 'job_expiry_date');
        if(date('Y-m-d', strtotime($meta[0])) <= $today) {
            update_post_meta($post->ID, 'status', 'Inactive');
        }
        else {
            update_post_meta($post->ID, 'status', 'Active');
        }
    }
}

/*add_shortcode('test', 'test_shortcode');
function test_shortcode() {
    $today = date('Y-m-d');
    $args = array(
        'post_type' => 'job',
        'numberposts' => -1,
    );
    $posts = get_posts($args);
    foreach($posts as $post) {
        $meta = get_post_meta($post->ID, 'job_expiry_date');
        if(date('Y-m-d', strtotime($meta[0])) <= $today) {
            update_post_meta($post->ID, 'status', 'Inactive');
        }
        else {
            update_post_meta($post->ID, 'status', 'Active');
        }
    }
}*/

add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts' );
function wpb_adding_scripts() { 
    wp_register_script('populate-city', plugins_url('script.js', __FILE__), array('jquery'),time(), true);     
    wp_enqueue_script('populate-city');


    wp_localize_script( 'populate-city', 'pa_vars',
        array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'pa_nonce' => wp_create_nonce( 'pa_nonce' ),
        )
    );
}

add_action('wp_ajax_pa_add_city', 'city_by_province');
add_action('wp_ajax_nopriv_pa_add_city', 'city_by_province');
function city_by_province( $selected_province ) { 
    if( !isset( $_POST['pa_nonce'] ) || !wp_verify_nonce( $_POST['pa_nonce'], 'pa_nonce' ) )
        die('Permission denied');
    $selected_city = '';
    $selected_province = $_POST['province'];
    $jobId = $_POST['jobId'];
    if($jobId != ''){
        $selected_city = get_field('job_city', $jobId);
    }
    $province_and_cities = array(
        "AB" => array(
                'airdrie' => 'Airdrie - AB',
                'calgary' => 'Calgary - AB',
                'canmore' => 'Canmore - AB',
                'edmonton' => 'Edmonton - AB',
                'grande-prairie' => 'Grande Prairie - AB',
                'leduc' => 'Leduc - AB',
                'lethbridge' => 'Lethbridge - AB',
                'medicine-hat' => 'Medicine Hat - AB',
                'okotoks' => 'Okotoks - AB',
                'others' => 'Others - AB',
                'red-deer' => 'Red Deer - AB',
                'st-albert' => 'St. Albert - AB',
            ),
        "BC" => array(
                'abbotsford' => 'Abbotsford - BC',
                'burnaby' => 'Burnaby - BC',
                'campbell-river' => 'Campbell River - BC',
                'chilliwack' => 'Chilliwack - BC',
                'coquitlam' => 'Coquitlam - BC',
                'delta -' => 'Delta - BC',
                'kamloops' => 'Kamloops - BC',
                'kelowna' => 'Kelowna - BC',
                'langley' => 'Langley - BC',
                'maple-ridge' => 'Maple-Ridge - BC',
                'mission' => 'Mission - BC',
                'nanaimo' => 'Nanaimo - BC',
                'new-westminster' => 'New Westminster - BC',
                'north-vancouver' => 'North-Vancouver - BC',
                'others' => 'Others - BC',
                'penticton' => 'Penticton-BC',
                'port-alberni' => 'Port Alberni-BC',
                'port-coquitlam' => 'Port Coquitlam - BC',
                'prince-george' => 'Prince George - BC',
                'richmond' => 'Richmond - BC',
                'squamish' => 'Squamish - BC',
                'surrey' => 'Surrey - BC',
                'vancouver' => 'Vancouver - BC',
                'vernon' => 'Vernon - BC',
                'victoria' => 'Victoria - BC',
                'west-vancouver' => 'West-Vancouver - BC',
                'whistler' => 'Whistler - BC',
                'williams' => 'Williams Lake - BC',
            ),
        "MB" => array(
                'brandon' => 'Brandon - MB',
                'others' => 'Others - MB',
                'steinbach' => 'Steinbach - MB',
                'thompson' => 'Thompson - MB',
                'winnipeg' => 'Winnipeg - MB',
            ),
        "NB" => array(
                'bathurst' => 'Bathurst - NB',
                'dieppe' => 'Dieppe - NB',
                'fredericton' => 'Fredericton - NB',
                'miramichi' => 'Miramichi - NB',
                'moncton' => 'Moncton - NB',
                'others' => 'Others - NB',
                'saint-john' => 'Saint John - NB',
            ),
        "NL" => array(
                'corner-brook' => 'Corner Brook - NL',
                'mount-pearl' => 'Mount Pearl - NL',
                'others' => 'Others - NL',
                'st-john' => 'St. John\'s - NL',
            ),
        "NT" => array(
                'others'=>'Others - NT',
                'yellowknife'=>'Yellowknife - NT',
            ),
        "NS" => array(
                'dartmouth' => 'Dartmouth - NS',
                'halifax' => 'Halifax - NS',
                'others' => 'Others - NS',
                'sydney' => 'Sydney - NS',
            ),
        "NU" => array(
                'iqaluit' => 'Iqaluit - NU',
                'others' => 'Others - NU',
            ),
        "ON" => array(
                'ajax' => 'Ajax - ON',
                'aurora' => 'Aurora - ON',
                'barrie' => 'Barrie - ON',
                'brampton' => 'Brampton - ON',
                'burlington' => 'Burlington - ON',
                'cambridge' => 'Cambridge - ON',
                'collingwood' => 'Collingwood - ON',
                'etobicoke' => 'Etobicoke - ON',
                'georgina' => 'Georgina - ON',
                'gta' => 'GTA Others - ON',
                'guelph' => 'Guelph - ON',
                'hamilton' => 'Hamilton - ON',
                'kingston' => 'Kingston - ON',
                'kitchener' => 'Kitchener - ON',
                'lakefield' => 'Lakefield - ON',
                'london' => 'London - ON',
                'maple' => 'Maple - ON',
                'markham' => 'Markham - ON',
                'milton' => 'Milton - ON',
                'mississauga' => 'Mississauga - ON',
                'newmarket' => 'Newmarket - ON',
                'niagara-falls' => 'Niagara Falls - ON',
                'north-york' => 'North York - ON',
                'oakville' => 'Oakville - ON',
                'oshawa' => 'Oshawa - ON',
                'others' => 'Others - ON',
                'ottawa' => 'Ottawa - ON',
                'peterborough' => 'Peterborough - ON',
                'pickering' => 'Pickering - ON',
                'richmond-hill' => 'Richmond Hill - ON',
                'scarborough' => 'Scarborough - ON',
                'st' => 'St. Catharines - ON',
                'sudbury' => 'Sudbury - ON',
                'thunder' => 'Thunder Bay - ON',
                'toronto' => 'Toronto - ON',
                'vaughan' => 'Vaughan - ON',
                'waterloo' => 'Waterloo - ON',
                'whitby' => 'Whitby - ON',
                'whitechurch-stouffville' => 'Whitechurch-Stouffville - ON',
                'windsor' => 'Windsor - ON',
                'woodbridge' => 'Woodbridge - ON',
            ),
        "PE" => array(
                'charlottetown' => 'Charlottetown - PE',
                'others' => 'Others - PE',
                'summerside' => 'Summerside - PE',
            ),
        "QC" => array(
                'amos' => 'Amos - QC',
                'baie-saint-paul' => 'Baie-Saint-Paul- QC',
                'blainville' => 'Blainville - QC',
                'brossard' => 'Brossard - QC',
                'chibougamau' => 'Chibougamau - QC',
                'chicoutimi' => 'Chicoutimi - QC',
                'cote-saint-luc' => 'Cote-Saint-Luc - QC',
                'dolbeau' => 'Dolbeau - QC',
                'drummondville' => 'Drummondville - QC',
                'gatineau' => 'Gatineau - QC',
                'granby' => 'Granby - QC',
                'la malbaie' => 'La Malbaie- QC',
                'la Sarre' => 'La Sarre - QC',
                'laval' => 'Laval - QC',
                'levis' => 'Levis - QC',
                'longueuil' => 'Longueuil - QC',
                'montreal' => 'Montreal - QC',
                'others' => 'Others - QC',
                'quebec city' => 'Quebec City - QC',
                'repentigny' => 'Repentigny - QC',
                'rivière-du-loup' => 'Rivière-du-Loup- QC',
                'rouyn-noranda' => 'Rouyn-Noranda - QC',
                'saguenay' => 'Saguenay - QC',
                'sherbrooke' => 'Sherbrooke - QC',
                'stoneham' => 'Stoneham - QC',
                'trois-rivieres' => 'Trois-Rivieres - QC',
            ),
        "SK" => array(
                'lloydminster' => 'Lloydminster - SK',
                'moose jaw' => 'Moose Jaw - SK',
                'others' => 'Others - SK',
                'prince albert' => 'Prince Albert - SK',
                'regina' => 'Regina - SK',
                'saskatoon' => 'Saskatoon - SK',
                'yorkton' => 'Yorkton - SK',   
            ),
        "YT" => array(
                'others' => 'Others - YT',
                'whitehorse' => 'Whitehorse - YT',
            ),
    );
     
    if (array_key_exists( $selected_province, $province_and_cities)) { 
        $arr_data = $province_and_cities[$selected_province];
        return wp_send_json(array('data'=>$arr_data,'selected_city'=>$selected_city)); 
    } else { 
        $arr_data = array();
        return wp_send_json(array('data'=>$arr_data,'selected_city'=>$selected_city));
    }
  die();
}

add_filter('pre_get_posts','searchfilter');
function searchfilter($query) {
    if(!$query->is_main_query()) {
        if($query->is_search_page()) {
            if ( $query->is_search || $query->is_tax || ( $query->is_post_type_archive && $query->query['post_type'] == 'job' ) ) {
                $query->set('meta_key', 'status'); 
                $query->set('meta_value', 'Active'); 
            }
        }
    }
    /*echo "<pre>";
    print_r($query);
    echo "</pre>";*/
    return $query;
}

/********* Apply Online *******/
/*********Fill job id from URL *******/
add_filter('acf/load_value/name=job_id', 'get_job_id_from_url', 10, 3);
function get_job_id_from_url( $value ) {
    global $post;
    if ( $post->ID == 29183 && isset($_GET['job_id'])) {
        $job_id = $_GET['job_id'];
        $value = $job_id;
    }
  return $value;
}

/*********Fill user id If User logged in *******/
add_filter('acf/load_value/name=user_id', 'user_id_callback', 10, 3);
function user_id_callback( $value ) {
    global $post;
    global $current_user;
    if ( $post->ID == 29183 && $current_user->ID != 0) {
        $value = $current_user->ID;
    }
  return $value;
}
/*********Fill user type If User logged in *******/
add_filter('acf/load_value/name=user_type', 'user_type_callback', 10, 3);
function user_type_callback( $value ) {
    global $post;
    global $current_user;
    if ( $post->ID == 29183 && $current_user->ID != 0) {
        $value = "Registered";
    }
  return $value;
}

/*************Apllication Title from Apply Online***************************/
add_action('save_post', 'set_application_title', 12 );
function set_application_title( $post_id ) {
    
    if ( $post_id == null || empty($_POST) )
        return;
    $post_type = get_post_type($post_id);
    if ( $post_type !='application' )  
        return; 

    if ( wp_is_post_revision( $post_id ) )
        $post_id = wp_is_post_revision( $post_id );

    global $post;  
    if ( empty( $post ) )
        $post = get_post($post_id);

    if ($_POST['acf']['field_61c2c06913fa9']!='') { // field_61c2c06913fa9 = job_id
        global $wpdb;
        $title = $_POST['acf']['field_61c2f1725bfaa'].$_POST['acf']['field_61c2dec11c169'].'-' . $_POST['acf']['field_61c2c06913fa9'];
        $where = array( 'ID' => $post_id );
        $wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
    }
}

add_action('wp_ajax_clean_shortcode_parameters', 'clean_shortcode_parameters' );
add_action('wp_ajax_nopriv_clean_shortcode_parameters', 'clean_shortcode_parameters' );
function clean_shortcode_parameters( $atts ) {
        
    foreach ($atts as $parameter_key => $parameter_value) {

        $parameter =  $parameter_value;

        if( $parameter_key == 'meta_query' ) {
            $parameter = str_replace("<%","[", $parameter );
            $parameter = str_replace("%>","]", $parameter );

            $parameter = do_shortcode( $parameter );
        } else{
            $parameter = str_replace("<%","[", $parameter );
            $parameter = str_replace("%>","]", $parameter );

            $parameter = do_shortcode( $parameter );

            $parameter = str_replace("{{","[", $parameter );
            $parameter = str_replace("}}","]", $parameter );

            $parameter = do_shortcode( $parameter );
        }

        $atts[$parameter_key] = $parameter;
    }

    return $atts;
}

add_shortcode("location","location_callback");
function location_callback($atts, $content = null){
    global $post;
    $atts =  shortcode_atts(
        array(
            'post_id'           => $post->ID,
            'field'             => false,
            'element'           => false, //cityName,stateName,stateCode,country,countryCode
            'class'             => '',
            'style'             => ''
        ), $atts , 'location' );

    $atts = clean_shortcode_parameters( $atts );
    $output = "";
 
    if( !empty( $atts['post_id'] ) ) {
        $field = get_field( $atts['field'], $atts['post_id'] );
    }
    if( empty( $field) ) {
        $output .= get_post_meta( $atts['post_id'], $atts['field'], true );
        return $output; 
    }

    $field_class = 'field_'.$field['name'];

    if( empty( $field_class ) ) {
        $field_class = $field['key'];
    }

    $field_id = $field['wrapper']['id'];

    if( empty( $field_id ) ) {
        $field_id = $field['key'];
    }

    $field_classes = array(
        'field',
        $atts['class'],
        $field_class,
        $field['wrapper']['class'],
        'field_type_' . $field['type'],
     );

    $field_classes = implode( ' ', $field_classes );

    if( $atts['style'] == "div" ) {
        $output .= '<div id="' . $field_id . '" class="' . $field_classes . '">';
    }

    /*echo "<pre>";
    print_r($field);
    echo "</pre>";*/

    if($atts['element'] == "cityName"){
        $output .= $field["cityName"];
    }
    if($atts['element'] == "stateName"){
        $output .= $field["stateName"];
    }
    if($atts['element'] == "stateCode"){
        $output .= $field["stateCode"];
    }
    if($atts['element'] == "countryName"){
        $output .= $field["countryName"];
    }
    if($atts['element'] == "countryCode"){
        $output .= $field["countryCode"];
    }

    if( $atts['style'] == "div" ) {
        $output .= '</div>';
    }

    return $output;
}

add_shortcode('get_featured_image', 'get_featured_image_shortcode');
function get_featured_image_shortcode($atts, $content = null){
    global $post;
    $atts = shortcode_atts( array(
        'post_id' => $post->ID,
        'size' => 'full',
        'class' => '',
        'id' => '',
        'placeholder' => 'true',
        'placeholder_image' => plugin_dir_url( __FILE__ ) . 'images/user.svg',
        'return' => 'image',// image/url/width/height/alt
    ), $atts, 'get_featured_image' );

    $atts = clean_shortcode_parameters( $atts );

    $output = "";
    $id_attribute = "";
    $original_post_id = $atts['post_id'];

    if( !empty( $atts['id'] ) ) {
        $id_attribute = 'id="'.$atts['id'].'"';
    }

    $crossposts = get_post_meta($original_post_id,'crossposts',true);
    if(!empty($crossposts)){
        foreach( $crossposts as $crosspost ) {
            if($crosspost['blog_id'] == '1'){
                $post_id = $crosspost['cross_post_id'];
                switch_to_blog(1);
                    $featured_image_id = get_post_meta($post_id,'featured_image', true);
                    if($featured_image_id == null) {
                        $featured_image_id = get_post_meta($post_id,'_thumbnail_id', true);
                    }
                    $featured_image = get_post($featured_image_id, ARRAY_A);
                    if ( 'attachment' == $featured_image['post_type'] ) {
                        $featured_image_url = $featured_image['guid'];
                    }
                restore_current_blog();
                if($featured_image_url != null) {
                    $output .= '<img class="'.$atts['class'].'" '.$id_attribute.' src="'.$featured_image_url.'" alt="" />';
                }
                else {
                    $output .= '<img class="'.$atts['class'].'" '.$id_attribute.' src="https://jobsportal.ca/wp-content/uploads/2022/03/jobsource-jobbank-16-icon.png" alt="" />';
                }
            }
        }
    }

    return $output;
}

add_action('acf/save_post', 'register_guest_user');
function register_guest_user($post_id){
    if ( $post_id == null || empty($_POST) )
        return;
    $post_type = get_post_type($post_id);
    if ( $post_type !='application' )  
        return; 

    if ( wp_is_post_revision( $post_id ) )
        $post_id = wp_is_post_revision( $post_id );

    $user_id = get_field('user_id',$post_id);

    if($user_id > 0){
        return;
    }

    $job_seeker_first_name = get_field('job_seeker_first_name',$post_id);
    $job_seeker_last_name = get_field('job_seeker_last_name',$post_id);
    $job_seeker_email = get_field('job_seeker_email',$post_id);
    $job_seeker_mobile_number = get_field('job_seeker_mobile_number',$post_id);
    $job_seeker_highest_education = get_field('job_seeker_highest_education',$post_id);
    $job_seeker_most_recent_job_title = get_field('job_seeker_most_recent_job_title',$post_id);
    $job_seeker_skills = get_field('job_seeker_skills',$post_id);
    $job_seeker_street_address = get_field('job_seeker_street_address',$post_id);
    $job_seeker_city_selector = get_field('job_seeker_city_selector',$post_id);
    $job_seeker_postal_code = get_field('job_seeker_postal_code',$post_id);

    $errors = array( );

    if( !is_email( $job_seeker_email ) ) {
        //invalid email
        $errors[] = 'Email address you have entered is invalid. Enter a valid email address.';
    }
    if( email_exists( $job_seeker_email ) ) {
        //Email address already registered
        $errors[] = 'Email already registered. You can reset your password instead.';
    }
    if( !empty($job_seeker_first_name) && !preg_match('/^[a-zA-Z\s]+$/', $job_seeker_first_name) ) {
        //Invalid first name
        $errors[] = 'Invalid first name. Numbers not allowed.';
    }
    if( !empty($job_seeker_last_name) && !preg_match('/^[a-zA-Z\s]+$/', $job_seeker_last_name) ) {
        //Invalid last name
        $errors[] = 'Invalid last name. Numbers not allowed.';
    }
    if( empty($errors)) {
        $user_role = 'job_seeker';
        $password = wp_generate_password();
        $new_user_id = wp_insert_user(array(
            'user_login'        => $job_seeker_email,
            'user_pass'         => $password,
            'user_email'        => $job_seeker_email,
            'first_name'        => $job_seeker_first_name,
            'last_name'         => $job_seeker_last_name,
            'user_registered'       => date('Y-m-d H:i:s'),
            'role'          => $user_role
        )
        );

        if( is_int($new_user_id)) {
            update_field('user_id', $new_user_id, $post_id);
            update_field( 'job_seeker_mobile_number', $job_seeker_mobile_number, 'user_'.$new_user_id );
            update_field( 'job_seeker_highest_education', $job_seeker_highest_education, 'user_'.$new_user_id );
            update_field( 'job_seeker_most_recent_job_title', $job_seeker_most_recent_job_title, 'user_'.$new_user_id );
            update_field( 'job_seeker_skills', $job_seeker_skills, 'user_'.$new_user_id );
            update_field( 'job_seeker_street_address', $job_seeker_street_address, 'user_'.$new_user_id );
            update_field( 'job_seeker_city_selector', $job_seeker_city_selector, 'user_'.$new_user_id );
            update_field( 'job_seeker_postal_code', $job_seeker_postal_code, 'user_'.$new_user_id );
            $key = md5($password);
            add_user_meta( $new_user_id, 'activation_key', $key, true );
            add_user_meta( $new_user_id, 'user_status', "Pending", true );
            add_user_meta( $new_user_id, 'user_temp_pass', $password, true );
            $current_blog_details = get_blog_details( array( 'blog_id' => get_current_blog_id() ) );
            $site_title = $current_blog_details->blogname;
            $headers = array( );
            $headers[] = "Content-Type: text/html";
            $headers[] = "charset=UTF-8";
            $subject = sprintf( __('Email Varification for %s account'), $site_title );
            $message = '';
            $message .= '<p>Hi ' . $job_seeker_first_name . ', </p>';
            $message .= '<p>Please click the below link to activate your account.</p>';
            $message .= '<a href="' . network_site_url( "activate/?akey=$key&uid=$new_user_id" ).' ">Click Here to Activate your Account</a>';
            $message .= '<br>';

            $notification_sent = wp_mail( $job_seeker_email, $subject, $message, $headers );

            
            if( $notification_sent ) {
                return true;
            } else {
                return false;
            }
        }
    }

}
add_shortcode("activate","activate_callback");
function activate_callback($atts){
    $atts =  shortcode_atts(
        array(
            'akey'           => '',
            'uid'           => '',
        ), $atts , 'activate' );

    $atts = clean_shortcode_parameters( $atts );
    $output = "";

    $key = $atts['akey'];
    $uid = $atts['uid'];

    $user_key = get_user_meta( $uid, 'activation_key', true );
    $user_pass = get_user_meta( $uid, 'user_temp_pass', true );
    $user_status = get_user_meta( $uid, 'user_status', true );
    $user = get_user_by('id', $uid);
    /*echo "<pre>";
    print_r($user_key);
    print_r("============");
    print_r($key);
    print_r("============");
    print_r($user_pass);
     print_r("============");
    print_r($user_status);
    echo "</pre>";*/
    if($user_key == $key){
        update_user_meta( $uid, 'user_status', "Active" ); 
        $output .= '<div class="aione-message success">
                      <ul class="aione-messages">
                        <li>Account activate successfully.</li>
                      </ul>
                    </div>';
        $current_blog_details = get_blog_details( array( 'blog_id' => get_current_blog_id() ) );
        $site_title = $current_blog_details->blogname;
        $headers = array( );
        $headers[] = "Content-Type: text/html";
        $headers[] = "charset=UTF-8";
        $subject = sprintf( __('Account Detail for %s'), $site_title );
        $message = '';
        $message .= '<p>Hi ' . $user->first_name . ', </p>';
        $message .= '<p>Username : '.$user->user_login.'</p>';
        $message .= '<p>Password : '.$user_pass.'</p>';        
        $message .= '<br>';

        $notification_sent = wp_mail( $user->user_email, $subject, $message, $headers );
        
        $admin_email = get_option('admin_email');

        $subject_admin = sprintf( __('New User Registered on %s'), $site_title );
        $message_admin = '';
        $message_admin .= '<p>Hi</p>';
        $message_admin .= '<p>New user with email '.$user->user_login.' is registered.</p>';
        $message_admin .= '<br>';

        $notification_admin_sent = wp_mail( $admin_email, $subject_admin, $message_admin, $headers );
                   
    } else {
        $output .= '<div class="aione-message error">
                      <ul class="aione-messages">
                        <li>Something went wrong.</li>
                      </ul>
                    </div>';
    }

    return $output;
}

add_shortcode('status', 'get_post_status_shortcode');
function get_post_status_shortcode($atts) {
    global $post;
    $atts =  shortcode_atts(
        array(
            'post_id' => $post->ID,
        ), $atts , 'status' );

    $atts = clean_shortcode_parameters( $atts );
    $output = "";

    $post_data = get_post($atts['post_id']);
    $output .= $post_data->post_status;

    return $output;
} 

add_shortcode('get_author', 'get_post_author_shortcode');
function get_post_author_shortcode($atts) {
    global $post;
    $atts =  shortcode_atts(
        array(
            'post_id' => $post->ID,
        ), $atts, 'get_author' );

    $atts = clean_shortcode_parameters( $atts );
    $output = "";

    $post_data = get_post($atts['post_id']);
    $output .= $post_data->post_author;

    return $output;
} 

add_shortcode('crossposts_links', 'get_crossposts_links_shortcode');
function get_crossposts_links_shortcode($atts) {
    global $post;
    $atts =  shortcode_atts(
        array(
            'post_id' => $post->ID,
        ), $atts , 'crossposts_links' );

    $atts = clean_shortcode_parameters( $atts );
    $current_blog_id = get_current_blog_id();
    $links = array();
    $output = "";

    $crossposts = get_post_meta($atts['post_id'],'crossposts',true);
    foreach($crossposts as $key => $value){
        if($value['blog_id'] !=$current_blog_id){
            $cross_post_id = $value['cross_post_id'];
            switch_to_blog( $value['blog_id']);
            $post_link = do_shortcode('[link post_id='.$cross_post_id.']');
            $protocols = array('https://','http://', 'http://www.', 'www.');
            $home_url =  str_replace($protocols, '', get_bloginfo('wpurl'));
            $links[$home_url]=$post_link;
            restore_current_blog();
        }
    }
    if(!empty($links)){
        $output .= '<div class="ar crossposts-links">';
        foreach ($links as $home_url => $link) {
            $output .= '<div class="ac l33 m33 s100">';
                $output .= '<h6 class="rainbow-text">';
                    $output .= '<a href="'.$link.'" target="_blank" rel="noopener">'.$home_url.'</a>';
                $output .= '</h6>';
            $output .= '</div>';
        }
        $output .= '</div>';
    }    
    echo $output;
}

  require_once "dompdf/autoload.inc.php";
   use Dompdf\Dompdf; 
   use Dompdf\Options;
add_shortcode('generate_pdf', 'generate_pdf_shortcode');
function generate_pdf_shortcode(){
    /*$html = file_get_contents("https://jobsportal.ca/job/ethnic-food-cook/");
    print_r($html);*/

    ?> 
   <form method="post" id="hidden_form">
    <input type="submit" value="click me">
    <input type="hidden" name="me_post_pdf" value="submitted">
    <input type="hidden" id="hidden_form_input" name="html">
    <a href="#" id="submit_link" class="button"> Download</a>
</form>
    <?php
}
add_action('init', 'html_to_pdf');
function html_to_pdf(){    
     if (isset($_POST['me_post_pdf'])){
        include 'dompdf/autoload.inc.php';

        global $wp;
        $current_url = home_url(add_query_arg(array(),$wp->request));
        
        //$html = file_get_contents($current_url);
        $html = urldecode($_POST['html']);
        /*$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://jobsportal.ca/job/ethnic-food-cook/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $html = curl_exec($ch);
        curl_close($ch);*/
        
        $options = new Options();
        $options->set('A4','potrait');
        $options->set('enable_css_float',true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isJavascriptEnabled', TRUE);
        $options->set('isRemoteEnabled',true);  


        $dompdf = new DOMPDF($options);
        $dompdf->load_html($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        //$dompdf->stream('title.pdf',array('Attachment'=>0));
        $dompdf->stream('title.pdf');
        exit;
    }
} 