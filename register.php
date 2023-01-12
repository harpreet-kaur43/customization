<?php
add_shortcode('register_custom','register_custom_callback');
function register_custom_callback($atts, $content = null){
    // Attributes
        $atts =  shortcode_atts(
            array(
                'echo'                      => false,
                'captcha'                   => "true",
                'form_id'                   => 'aione_registration_form',
                'show_firstname'            => 'yes',
                'show_lastname'             => 'yes',
                'label_firstname'           => __( 'First Name' ),
                'label_lastname'            => __( 'Last Name' ),
                'label_username'            => __( 'Username' ),
                'label_email'               => __( 'Email Address' ),
                'label_password'            => __( 'Password' ),
                'label_password_again'      => __( 'Password Again' ),
                'label_submit'              => __( 'Register' ),
                'placeholder_firstname'     => __( 'Enter Your First Name' ),
                'placeholder_laststname'    => __( 'Enter Your Last Name' ),
                'placeholder_username'      => __( 'Enter Your Username' ),
                'placeholder_email'         => __( 'Enter Your Email Address' ),
                'role'                      => get_option('default_role'),
                'field_groups'              => 0,
            ), $atts , 'register_custom' );

        $args = array(

            'echo'                      => $atts['echo'],
            'captcha'                   => $atts['captcha'],
            'form_id'                   => $atts['form_id'],
            'show_firstname'            => $atts['show_firstname'],
            'show_lastname'             => $atts['show_lastname'],
            'label_firstname'           => $atts['label_firstname'],
            'label_lastname'            => $atts['label_lastname'],
            'label_username'            => $atts['label_username'],
            'label_email'               => $atts['label_email'],
            'label_password'            => $atts['label_password'],
            'label_password_again'      => $atts['label_password_again'],
            'label_submit'              => $atts['label_submit'],
            'placeholder_firstname'     => $atts['placeholder_firstname'],
            'placeholder_laststname'    => $atts['placeholder_laststname'],
            'placeholder_username'      => $atts['placeholder_username'],
            'placeholder_email'         => $atts['placeholder_email'],
            'role'                      => $atts['role'],
            'field_groups'              => $atts['field_groups'],

         );
        
        

        $output = "";

        // only show the registration form to non-logged-in members
        if( !is_user_logged_in() ) {

            global $aione_load_css;

            // set this to true so the CSS is loaded
            $aione_load_css = true;

            // check to make sure user registration is enabled
            $registration_enabled = get_option('users_can_register' );
            
            // only show the registration form if allowed
            if( $registration_enabled) {

                $errors = array( );
                
                // load from post
                if( isset($_POST['action']) && $_POST['action'] == 'add_new') {

                    $user_login     = $_POST["aione_user_login"];
                    $user_email     = $_POST["aione_user_email"];
                    $user_pass      = $_POST["aione_user_pass"];
                    $pass_confirm   = $_POST["aione_user_pass_confirm"];
                    $user_first     = $_POST["aione_user_fname"];
                    $user_last      = $_POST["aione_user_lname"];

                    
                    if( $atts['captcha'] == "true" ) {

                        if ( class_exists( 'ReallySimpleCaptcha' ) )  {

                            $captcha_value  = $_POST['captcha_value'];
                            $prefix         = $_POST['captcha_prefix'];

                            $captcha_instance_check = new ReallySimpleCaptcha( );
                            $is_captcha_correct     = $captcha_instance_check->check( $prefix, $captcha_value );
                            
                            if( !$is_captcha_correct ) {

                                $errors[] = 'Wrong Captcha value';

                            }

                        }

                    }
                    
                    // this is required for username checks
                    if( $user_email == '') {

                        //empty email
                        $errors[] = 'Email address field can not be empty.';

                    } else {

                        if( !is_email( $user_email ) ) {

                            //invalid email
                            $errors[] = 'Email address you have entered is invalid. Enter a valid email address.';

                        }

                        if( email_exists( $user_email ) ) {

                            //Email address already registered
                            $errors[] = 'Email already registered. You can reset your password instead.';

                        }

                    }
                    
                    if( $user_login == '' ) {

                            // empty username
                        $errors[] = 'Username cannot be empty. Please enter a username';

                    } else {
                        
                        $pattern = '/^[a-z0-9]+$/';
                        
                        if( !preg_match($pattern, $user_login) ) {
                            $errors[] = 'The username you have entered is invalid. Please enter at least 6 alphanumeric characters in lowercase. Special characters and white spaces are not allowed.'; 
                        } else{ 
                            if( !validate_username($user_login)) {
                                    // invalid username
                                $errors[] = 'Username you have entered is invalid. ';
                            }
                        }


                        if( username_exists($user_login)) {
                                // Username already registered
                            $errors[] = 'Username already taken. Try something else.';
                        }               
                    }

                    if( $user_pass == '') {
                            //Empty password
                        $errors[] = 'Please enter a password';
                    }

                    if( $user_pass != $pass_confirm) {
                            // passwords do not match
                        $errors[] = 'Passwords do not match';
                    }
                    if( $user_first == '') {
                            //Empty password
                        $errors[] = 'First name can not be empty. Humans do have names.';
                    }

                    if( !empty($user_first) && !preg_match('/^[a-zA-Z\s]+$/', $user_first) ) {
                            //Invalid Mobile
                        $errors[] = 'Invalid first name. Numbers not allowed.';
                    }
                    
                    if( !empty($user_last) && !preg_match('/^[a-zA-Z\s]+$/', $user_last) ) {
                            //Invalid Mobile
                        $errors[] = 'Invalid last name. Numbers not allowed.';
                    }
                    
                        // only create the user in if there are no errors
                    if( empty($errors)) {
                        global $wp_roles;
                        $roles = wp_roles()->get_names( );
                        if( array_key_exists($atts['role'],$roles) ) {
                            $user_role = $atts['role'];
                        } else {
                            $user_role = get_option('default_role' );
                        }
                        
                        $new_user_id = wp_insert_user(array(
                            'user_login'        => $user_login,
                            'user_pass'         => $user_pass,
                            'user_email'        => $user_email,
                            'first_name'        => $user_first,
                            'last_name'         => $user_last,
                            'user_registered'       => date('Y-m-d H:i:s'),
                            'role'          => $user_role
                        )
                     );
                        if( is_int($new_user_id)) {
                            if( isset($_POST['acf']) ) {
                                $custom_fields = $_POST['acf'];
                                foreach($custom_fields as $custom_field_key => $custom_field ) {
                                    update_field($custom_field_key , $custom_field, "user_".$new_user_id );
                                }
                                
                            }

                            // $blogs = array('2','3','4','5','6','7');
                            // foreach ($blogs as $blog_id) {
                            //     add_user_to_blog( $blog_id, $new_user_id, $user_role );
                            // }
                            
                            apply_filters('activate_registration_filter', $new_user_id );

                            $success_messages .= apply_filters( 'custom_text_after_registration_filter', $success_text );
                            if($success_messages == ''){                                
                                $output .= 'Thank you for registering.';
                            } else {
                                $output .= $success_messages;
                            }

                            $message  = "Hi ".$user_first." ".$user_last.", " . "\r\n"; 
                            $message  .= sprintf(__('Thank you for registering to the Jobs Portal. Your username is %s', 'user-register-email'), $user_login) . "\r\n"; 
                            
                            $subject = "Registration on Jobs Portal";

                            wp_mail($user_email, $subject, $message);

                            $admin_message  = "Hi Administrator" . "\r\n"; 
                            $admin_message  .= sprintf(__('New User registered on Jobs Portal.', 'user-register-email')) . "\r\n"; 
                            $admin_message  .= sprintf(__('User Name: %s', 'user-register-email'),$user_login) . "\r\n"; 
                            $admin_message  .= sprintf(__('Email: %s', 'user-register-email'),$user_email) . "\r\n"; 
                            
                            $admin_subject = "Registration on Jobs Portal";

                            wp_mail('amritdeepkaur@gmail.com', $admin_subject, $admin_message);
                            
                            
                        } else {
                            $errors[] = 'Some error occurred. Please contact Administrator.';
                        }
                    } else {
                        foreach($errors as $error ) {
                            $output .=  '<div style="color:#cc0000;text-align:center;padding:10px">'.$error.'</div>';
                        }
                            //$output .= $this->aione_show_errors($errors );
                        $output .= registration_custom_form($args );
                    }
                } else {
                    $output .= registration_custom_form($args );
                }
            } else {
                $output .= __('User registration is not enabled!' );
            }
        } else {

            $output .= __('You are already logged in!' );
        }

        return $output;
}

function registration_custom_form($args){
    if( $args['captcha'] == "true" ) {
            if (class_exists('ReallySimpleCaptcha'))  {
                
                $captcha_instance = new ReallySimpleCaptcha( );
                $captcha_instance->cleanup($minutes = 30 );
                
                $captcha_instance->chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';  
                $captcha_instance->bg = array( 255, 255, 255 );
                $captcha_instance->fg = array( 21, 141, 197 );
                $captcha_instance->img_size = array( 205, 40 );
                $captcha_instance->base = array( 20, 30 );
                $captcha_instance->font_size = 22;
                $captcha_instance->char_length = 6;
                $captcha_instance->font_char_width = 28;
            //$upload_dir = wp_upload_dir( );
            //$captcha_instance->tmp_dir = $upload_dir['basedir'].'/captcha/';
                
            }   
        }
        
        $html_before_fields = '';
        $html_before_fields .= apply_filters( 'custom_text_before_registration_form_filter', $before_text );
        $html_before_fields .= '
        
        <form id="'.$args['form_id'].'" class="aione-registration-form aione-form form acf-form" action="'.get_permalink().'" method="post">
        <div class="postbox acf_postbox no_box">';

        if( $args['show_firstname'] == 'yes' ) {

            $html_before_fields .= '<div class="aione-form-field field field-type-text">
            <div class="label"><label for="aione_user_fname">'.$args['label_firstname'].'<span class="required">*</span></label></div>
            <div class="acf-input-wrap"><input name="aione_user_fname" id="aione_user_fname" class="textbox large" type="text" placeholder="'.$args['placeholder_firstname'].'" value="" minlength="2" maxlength="200" required /></div>
            </div>';
        }

        if( $args['show_lastname'] == 'yes' ) {

            $html_before_fields .= '<div class="aione-form-field field field-type-text">
            <div class="label"><label for="aione_user_lname">'.$args['label_lastname'].'</label></div>
            <div class="acf-input-wrap"><input name="aione_user_lname" id="aione_user_lname" class="textbox large" type="text" placeholder="'.$args['placeholder_laststname'].'" value=""/></div>
            </div>';
        }

        
        $html_before_fields .= '<div class="aione-form-field field field-type-text">
        <div class="label"><label for="aione_user_login">'.$args['label_username'].'<span class="required">*</span></label></div>
        <div class="acf-input-wrap"><input name="aione_user_login" id="aione_user_login" class="textbox large required" type="text" placeholder="'.$args['placeholder_username'].'" value="" minlength="6" maxlength="50" required/></div>
        </div>
        <div class="aione-form-field field field-type-text">
        <div class="label"><label for="aione_user_email">'.$args['label_email'].'<span class="required">*</span></label></div>
        <div class="acf-input-wrap"><input name="aione_user_email" id="aione_user_email" class="textbox large required" type="email" placeholder="'.$args['placeholder_email'].'" value="" required /></div>
        </div>

        <div class="aione-form-field field field-type-text">
        <div class="label"><label for="password">'.$args['label_password'].'<span class="required">*</span></label></div>
        <div class="acf-input-wrap"><input name="aione_user_pass" id="password" class="textbox large required" type="password" minlength="6" required /></div>
        </div>

        <div class="aione-form-field field field-type-text">
        <div class="label"><label for="password_again">'.$args['label_password_again'].'<span class="required">*</span></label></div>
        <div class="acf-input-wrap"><input name="aione_user_pass_confirm" id="password_again" class="textbox large required" type="password" minlength="6" required/></div>
        </div>

        ';
        if( $args['captcha'] == "true" ) {
            if (class_exists('ReallySimpleCaptcha'))  { 
                $word = $captcha_instance->generate_random_word( );
                $prefix = mt_rand( );
                $image_name = $captcha_instance->generate_image( $prefix, $word );
                //$captcha_image_url =  $upload_dir['baseurl'].'/captcha/'.$image_name;
                //$captcha_image_url = plugins_url( );
                //$captcha_image_url =  plugin_dir_url(dirname(__FILE__))."library/really-simple-captcha/tmp/".$image_name;
                $captcha_image_url =  plugin_dir_url(dirname(__FILE__))."aione-app-builder/tmp/".$image_name;

                //$blog_template = intval($_GET['template'] );
                
                $html_before_fields .= '<div class="aione-form-field field field-type-text">
                <div class="label"><label for="register_form_captcha_value">Captcha<span class="required">*</span></label></div>
                <div class="register_form_captcha_image">
                <img src="'.$captcha_image_url.'" />
                </div> 
                <div class="acf-input-wrap"><input name="captcha_value" id="register_form_captcha_value" type="text" placeholder="Enter Captcha Here" value="" class="textbox large required" >
                <input name="captcha_prefix" type="hidden" value="'.$prefix.'" >
                </div>
                
                </div>
                ';
            }
        }
        $html_before_fields .= '<div class="clear"></div>';
        $html_after_fields = '<div class="aione-form-field field">
        <input type="hidden" name="action" value="add_new">
        <input type="submit" value="'.$args['label_submit'].'">
        </div>
        <script>
        jQuery("#'.$args['form_id'].'").validate( );
        </script>

        <style>
        .error{
            color:#cc0000;
        }
        .aione-registration-form p.label{
            margin-bottom:0;
        }
        .aione-registration-form .aione-form-field{
            margin-bottom:20px;
        }
        </style>
        ';
        $html_after_fields .= apply_filters( 'custom_text_after_registration_form_filter', $after_text );
        $field_groups = $args['field_groups'];
        if( $field_groups == "" ) {
            $field_groups = 0;
        }
        if( !is_array($field_groups) ) {
            $field_groups = array($field_groups );
        }
        
        $options = array(
            'post_id'               => 'new_post',
            'form'                  => false,
            'field_groups'          => $field_groups,
            'post_title'            => false,
            'post_content'          => false,
            'html_before_fields'    => $html_before_fields,
            'html_after_fields'     => $html_after_fields,
            'instruction_placement' => 'field',
            'submit_value'          => 'Submit',
            'updated_message'       => 'Registered Successfully',
         );
        
        ob_start( );
        acf_form($options );
        $output .= ob_get_contents( );
        ob_end_clean( );
        return $output;
}
?>