# Authentication

This web app provides basic registration / login functionality for websites which use PHP. The user supplies their email address and a password. `authentication` creates a session if login is successful. On registration an email is sent to the user for verification. If a user forgets their password or wants to change it for any reason they can use the forgot password functionality which sends them an email with a link to reset their password.

The `authentication` directory needs to be placed in the root directory of the website requiring the functionality. `authentication` uses PHP sessions which result in a session cookie being sent to users.

`authentication` uses an SQLite database file for storing data. Depending on your server it may be necessary to change the permissions of the SQLite database file and the directory it is in (sqlite/) so that it can be written to by the server during interactions with the user's browser. In many cases changing the owner to `www-data` works.

At least one page, typically the home page, of the website needing password protection, then needs to include some html and PHP to enable users of the site to be able to register and login. Every page that needs password protection needs a few lines of PHP code at the top of the file.

An example of an `index.php` page which enables the `authentication` functionality, if it is in the same directory as the authentication directory, is shown below:


        <?php
        /*
         * project-root/index.php
         *
         * This is not part of the authentication package
         *
         * This shows how to use authentication
         *
         */
        session_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="UTF-8">
            <title>My Website</title>
          </head>
          <body>
        <?php
        if (empty($_SESSION['user'])){
          echo "<p><a href='./authentication/login/'>Login</a> or ";
          echo "<a href='./authentication/register/'>Register</a></p>";
        } else {
          echo "<p>";
          echo "Hallo ".$_SESSION['user'];
          echo " | <a href='./authentication/logout/'>Logout</a>";
          echo " | <a href='./authentication/close-account/'>Close Account</a>";
          echo "</p>";
        }
        ?>
            <h1>My Website</h1>
            <p>
                This page shows how to use authentication.
            </p>
            <p>
                Put this file in the root directory with authentication/.
            </p>
          </body>
        </html>


Any page that needs to be password protected should have the following PHP code in it before other content.

        <?php
        /*
         * any password protected page
         *
         * This is not part of the authentication package
         *
         * This shows how to use authentication
         *
         */
        <?php
        session_start();
        /*
         * If the user is not logged in redirect them to
         * website's home page.
         *
         * You could change Location to any path.
         */
        if(empty($_SESSION['user'])){
          header('Location: ../../'); // should be path to home page
          exit();
        }
        ?>


## Session variables

When a user logs in 2 PHP session variables are created. These are $_SESSION['user'] which contains the user's email anddress and $_SESSION['uid'] which contains a unique integer for that user. No other user will have that integer and exactly the same integer will be generated for that user whenever they log in. Therefore $_SESSION['uid'] can be used to save data relating to a specific user so that the data can be accessed whenever that user logs in again in the future.

## Configuration

Place the `authentication` directory in the root directory of your website.

Copy code from this README to your `index.php` file in the root of your project. This will provide links to the login / register pages of authentication when a user is not logged in and other authentication related pages such as logout when they are logged in.

Also, copy PHP code from this README to the top of any page you want password protected.

You will need to change the path for validation and forgot password email links to point to. These are found in authentication/auth-functions.php

## Demonstration

This repository is hosted at github/stevespages/authentication

A website protected with the authentication package is at https://stevespages.org.uk/authenticate

You can naviate there and register and then login and test other aspects of the functionality.

That site is called `authenticate` in order to distinguish it from the `authentication` directory. `Authenticate` is the site requiring protection and `authentication` is the functionality that provides the protection. Having said that, as outlined above, some code is required in the `authenticate` site in order to configure / integrate the functionality from `authentication`.g
