<?php

/* 

   NOTICE: Use with caution. Code under development. 

   DESCRIPTION: Send notification email to admin, when someone has logged in, a post has been updated, etc.
   See list of actions to get an idea on what triggers the notification emails.

   USAGE: Copy and paste inside functions.php 

   AUTHOR: Kostas•X | https://github.com/kostasx/

*/

class WPNS {

    private $action_triggered;
    private $wpns_version = '0.0.2';

    function __construct(){

        add_action( 'wp_login', array( $this, 'wp_login'), 10, 2 );
        add_action( 'wp_logout', array( $this, 'wp_logout') );
        add_action( 'create_category', array( $this, 'create_category' ) );     // CATEGORY CREATION
        add_action( 'delete_category', array( $this, 'delete_category' ) );     // CATEGORY DELETION
        add_action( 'delete_attachment', array( $this, 'delete_attachment' ) ); // MEDIA DELETION
        add_action( 'post_updated', array( $this, 'post_updated' ) );           // POST UPDATED 
        add_action( 'deleted_post', array( $this, 'deleted_post' ) );           // POST DELETED
        add_action( 'delete_user', array( $this, 'delete_user' ) );             // USER DELETED

        // UNDEFINED ACTIONS
        add_action( 'trash_post', array( $this, 'notify_admin') );
        // add_action( 'delete_post', array( $this, 'delete_post') );
        add_action( 'edit_post', array( $this, 'notify_admin') );
        add_action( 'edit_category', array( $this, 'notify_admin') );
        add_action( 'edit_attachment', array( $this, 'notify_admin') );
        add_action( 'publish_page', array( $this, 'notify_admin') );
        add_action( 'publish_post', array( $this, 'notify_admin') );
        add_action( 'save_post', array( $this, 'notify_admin') );
        add_action( 'updated_postmeta', array( $this, 'notify_admin') );
        add_action( 'switch_theme', array( $this, 'notify_admin') );
        add_action( 'lostpassword_post', array( $this, 'notify_admin') );
        add_action( 'password_reset', array( $this, 'notify_admin') );
        add_action( 'profile_update', array( $this, 'notify_admin') );
        add_action( 'user_register', array( $this, 'notify_admin') );
        add_action( 'activated_plugin', array( $this, 'notify_admin') );

    }

    public function delete_user(){
        $this->action_triggered = 'USER DELETED     [action: delete_user]';
        $this->notify_admin();
    }

    public function wp_login(){
        $this->action_triggered = 'USER LOGGED IN     [action: wp_login]';
        $this->notify_admin();
    }

    public function wp_logout(){
        $this->action_triggered = 'USER LOGGED OUT     [action: wp_logout]';
        $this->notify_admin();
    }

    public function create_category(){
        $this->action_triggered = 'CATEGORY CREATED     [action: create_category]';
        $this->notify_admin();
    }

    public function delete_category(){
        $this->action_triggered = 'CATEGORY DELETED     [action: delete_category]';
        $this->notify_admin();
    }

    public function delete_attachment(){
        $this->action_triggered = 'MEDIA DELETED     [action: delete_attachment]';
        $this->notify_admin();
    }

    public function post_updated(){
        $this->action_triggered = 'POST CREATED/UPDATED/DELETED     [action: post_updated]';
        $this->notify_admin();
    }

    public function deleted_post(){
        $this->action_triggered = 'POST TRASHED     [action: deleted_post]';
        $this->notify_admin();
    }

    public function set_content_type( $content_type ){    return 'text/html';  }

    public function notify_admin( $user_login, $user ){
        global $current_user;
        get_currentuserinfo();

        $body  = '<html lang="en"><head><meta content="text/html; charset=utf-8" http-equiv="Content-Type" /><title>WP NOTIFICATION SYSTEM</title>';
        $body .= '<style type="text/css">a:hover { text-decoration: none !important; } h3 { font-weight:normal; padding:0; font-size:16px; line-height:20px; color:#000000; margin: 6px 0 0; } td { padding: 10px; }</style></head>';
        $body .= '<body bgcolor="#e8e4ce" style="text-align: left; padding: 20px; margin: 0; background: #e8e4ce;">
        <table cellspacing="0" border="0" align="center" bgcolor="#e8e4ce" style="background: #e8e4ce;" width="100%" cellpadding="0"><table cellspacing="0" border="0" cellpadding="10" width="600">';

        $body .= "<tr><td>SITE: <span style='font-weight:bold;'>" . get_bloginfo("name") . "</span></td></tr>";
        $body .= "<tr><td>DATE: <span style='font-weight:bold;'>" . date("m/d/Y h:i:s a", time()) . "</span></td></tr>";
        $body .= "<tr><td>ACTION: <span style='font-weight:bold;'>" . $this->action_triggered . "</span></td></tr>";
        $body .= "<tr><td>USER: <span style='font-weight:bold;'>" . $user_login ."</span></td></tr>";
        $body .= "<tr><td>IP: <span style='font-weight:bold;'>" . $_SERVER["REMOTE_ADDR"] . "</span></td></tr>"; 
        $body .= "<tr><td>USER AGENT: <span style='font-weight:bold;'>" . $_SERVER["HTTP_USER_AGENT"] . "</span></td></tr>";
        $body .= "<tr><td>DOC ROOT: <span style='font-weight:bold;'>" . $_SERVER["DOCUMENT_ROOT"] . "</span></td></tr>"; 
        $body .= "<tr><td>QUERY: <span style='font-weight:bold;'>" . $_SERVER["QUERY_STRING"] . "</span></td></tr>"; 

        $body .= '</table></table></body></html>';

        add_filter( 'wp_mail_content_type', array( $this, 'set_content_type' ) );
        wp_mail( 'youremail@here', 'WPNS: ' . get_bloginfo("name"), $body, $headers );

    }

}

$wpns = new WPNS();
