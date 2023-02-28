<?php
/*
Plugin Name: My Email Piping Plugin
Description: A plugin to handle email piping and post creation from emails.
Version: 1.0.0
Author: Muhammed Dilshad
*/

// Plugin code goes here

// Define the email address to receive incoming posts
$email_address = 'pipetest@wplocatepress.com';

// Define the function to fetch emails and create posts
function email_piping() {
    global $email_address;
    
    // Check if the email address matches
    if ($_POST['recipient'] === $email_address) {
        
        // Fetch the email content
        $email_content = file_get_contents('php://input');
        
        // Extract the post information from the email using regular expressions
        preg_match('/Subject: (.*?)\n/', $email_content, $matches);
        $post_title = $matches[1];
        preg_match('/Content-Type: text\/plain; charset=(.*?)\n\n(.*)/s', $email_content, $matches);
        $post_content = $matches[2];
        preg_match('/Tags: (.*?)\n/', $email_content, $matches);
        $post_tags = $matches[1];
        preg_match('/Categories: (.*?)\n/', $email_content, $matches);
        $post_categories = $matches[1];
        
        // Create a new post in WordPress
        $post = array(
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_status' => 'publish',
            'post_category' => array($post_categories),
            'tags_input' => $post_tags
        );
        
        wp_insert_post($post);
    }
}

// Hook the function to the 'wp' action
add_action('wp', 'email_piping');

// Configure the email settings
add_action( 'phpmailer_init', function( $phpmailer ) {
    // Set the IMAP settings
    $phpmailer->Host = 'wplocatepress.com';
    $phpmailer->Port = 993;
    $phpmailer->Username = 'pipetest@wplocatepress.com';
    $phpmailer->Password = '*1j?4(+l2#:x';
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->Mailer = 'imap';
    $phpmailer->SMTPAuth = true;

    // Set the SMTP settings
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->SMTPAutoTLS = false;
    $phpmailer->SMTPAuth = true;
    $phpmailer->Host = 'wplocatepress.com';
    $phpmailer->Port = 465;
    $phpmailer->Username = 'pipetest@wplocatepress.com';
    $phpmailer->Password = '*1j?4(+l2#:x';
    $phpmailer->Mailer = 'smtp';
} );

