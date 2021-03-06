<?php

return array(
    'csnuser' => array(
        /**
         * Login Redirect Route
         *
         * Upon successful login the user will be redirected to the entered route
         *
         * Default value: 'user'
         * Accepted values: A valid route name within your application
         *
         */
        'login_redirect_route' => 'user-index',
    
        /**
         * Logout Redirect Route
         *
         * Upon logging out the user will be redirected to the enterd route
         *
         * Default value: 'user'
         * Accepted values: A valid route name within your application
         */
        'logout_redirect_route' => 'user-index',
        
        /**
         * Sender email dadress
         *
         * The automatically generated emails to the users have this
         * defined email address
         *
         * Default value: 'no-reply@example.com'
         * Accepted values:  A valid email address
         */
        'sender_email_adress' => 'jovemvirgem21@gmail.com',
        
        /**
         * Visibility of navigation menu
         *
         * If set to false the navigation menu does not appear
         *
         * Default value: true
         * Accepted values: true/false
         */
        'nav_menu' => true,
        
        /**
         * Set captcha number of characters
         *
         * Default value: 3
         * Accepted values: int
         */
         'captcha_char_num' => 3,
        
        /**
         * Visibility of exception details
         *
         * If set to false the exception details on errors do not appear
         *
         * Default value: true
         * Accepted values: true/false
         * Production reccommended value: false
         */
        'display_exceptions' => true,
    ),
);
