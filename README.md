# wp-framework

This WordPress theme framework is meant to serve as a starting point for any new custom WordPress theme project. 
AngularJS and a few key core Angular libraries are included for quick startup. It is suggested to use NPM to manage
all JavaScript dependencies so that libraries are easy to find and uniformly included.

### The Bones:
* **AngularJS** is included to be used as the rich UI backbone for more dynamic Admin and Front End features.
* **Bootstrap** is included to provide a responsive UI framework and many helpful UI tools.
* **NPM** all included 3rd party JavaScript and CSS files are managed through NPM to allow rapid and easy dependency management.

## Getting Started

* Download a fresh WordPress install https://wordpress.org/download/
* Install the new WordPress instance on your development server
* Create a new folder '/storage' in the root WordPress directory (optional but recommended)
* Add an apache '.htaccess' file with the statement 'Deny from all' (optional but recommended)
* Add a new blank text file '.env' to the '/storage' directory and add the 'ENVIRONMENT="local"' environment variable (optional but recommended)

Now select the 'framework' theme from the WordPress theme selection screen and you are ready to get coding!

## Environment '.env'

The environment file mentioned in the 'Getting Started' section is one of the key features of the WordPress framework
theme. This allows you to set environment based variables so you can diverge your processes based on what environment
you are in. Most of the time in the development environment you don't want to send out test emails process transactions
and other sensitive operations.

### How to use '.env'
```PHP
if( isset($_ENV['ENVIRONMENT']) && $_ENV['ENVIRONMENT'] == 'local' )
{
  // --> Do something on your development environment
}
else
{
  // --> Do something else on live.
}
```

Also note that if your development environment is set as live, or you have chosen not to use the environment functionality
a '/cache' file will be created to store your minified scripts. The 'functions.php' file manages all caching functionality
you can forceably re-create all cache files by including the URL parameter `dumpcache=true`.

You can extend your .env file to store any values you would like to access per environment, for instance you could
specify the default 'to' address for internal emails by adding it to your environment file.

```
ENVIRONMENT="local"
ADMIN_EMAIL="myemail@google.com"
```

Then from your mail script:

```PHP
wp_mail( $to = $_ENV['ADMIN_EMAIL'], $subject = 'Contact Request', $body = 'New contact form submitted:  ...' );
```

## Managing Your WP Project with GitHub

To easily manage your WordPress build through GitHub follow these easy guidlines:

* First setup your development environment as instructed in 'Getting Started'
* Once you are ready to deploy to a public sever environment use FTP to transfer your entire project to the document root of your server environment.
* Update your '.env' variables to be appropriate for the environment.
* Create a new repository for just your framework theme file https://help.github.com/articles/adding-an-existing-project-to-github-using-the-command-line/
* Delete the theme file from your public server environment
* SSH in to your server and clone your project theme file from the newly created GitHub Project you created: https://git-scm.com/book/en/v2/Git-Basics-Getting-a-Git-Repository

Now you can use GitHub to manage changes to your custom theme build! Note: since you are only managing your custom theme files
through your Git project, files outside of your theme can be managed through the WordPress Admin interface allowing
you to make changes to your theme without having conflicts with plugins, uploads and other dynamic content that WordPress
is meant to manage for you.

*Happy Hacking!*

-- Tim Williams
