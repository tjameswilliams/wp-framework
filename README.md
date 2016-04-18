# wp-framework

This WordPress theme framework is meant to serve as a starting point for any new custom WordPress theme project. 
AngularJS and a few key core Angular libraries are included for quick startup. It is suggested to use NPM to manage
all JavaScript dependencies so that libraries are easy to find and uniformly included.

### The Bones:
* **AngularJS** is included to be used as the rich UI backbone for more dynamic Admin and Front End features.
* **Angular Material** is included to provide a responsive UI framework and many helpful UI tools.
* **NPM** all included 3rd party JavaScript and CSS files are managed through NPM to allow rapid and easy dependency management.
* **Gulp** for rapid SCSS and JavaScript minification as well as a sleek BrowserSync workflow Gulp is leveraged to optimize as many repetitive tasks as possible!

## Getting Started

* Download a fresh WordPress install https://wordpress.org/download/
* Create your WordPress database on your development machine.
* Open a terminal window and run these commands changing the variables to your setup preferences 
```bash
$ cd /{{your theme}}/wp-content/themes
$ git clone https://github.com/tjameswilliams/wp-framework.git
$ cd wp-framework && npm install && gulp init_theme --option db='{{your database name}}' --option user='{{your database user}}' --option pass='{{your database password}}' --option host='{{your database host}}' --option apache_hostname='{{your virtual host name}}'
$ gulp watch
```
Your new wordpress site should now launch, log into the dashboard and change the theme to the framework and you are ready to code!

## Managing  JavaScript and CSS dependencies
To manage 3rd party module dependencies that you either include through NPM or download and install in the project manually
you should include in the dependencies.json file found in the root theme directory. This file is where gulp pulls dependency
locations from to minify into a single file for WordPress to include in functions.php.

Example dependencies file:
```JSON
{
  "js":[
    "node_modules/jquery/dist/jquery.js",
    "node_modules/angular/angular.min.js",
    "node_modules/angular-ui-tinymce/src/tinymce.js",
    "node_modules/ng-file-upload/dist/ng-file-upload-all.min.js",
    "node_modules/angular-ui-sortable/src/sortable.js",
    "node_modules/angular-route/angular-route.min.js",
    "node_modules/angular-aria/angular-aria.min.js",
    "node_modules/angular-animate/angular-animate.min.js",
    "node_modules/angular-messages/angular-messages.min.js",
    "node_modules/angular-material/angular-material.min.js",
    "src/js/*.js"
  ],
  "css":[
    "node_modules/angular-material/angular-material.min.css",
    "src/**/*.css"
  ],
  "scss":[
    "src/**/*.scss"
  ],
  "admin":{
    "js":[
      "node_modules/tinymce/tinymce.min.js",
      "admin.js"
    ]
  }
}
```

## Environment '.env'

The environment file created by gulp is one of the key features of the WordPress framework
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
