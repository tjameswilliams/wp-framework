'use strict';

var gulp = require('gulp');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');
var fs = require('fs');
var merge = require('merge-stream');
var browserSync = require('browser-sync').create();
var reload      = browserSync.reload;

gulp.task('minify_js', function() {
  var jsDeps = JSON.parse(fs.readFileSync('dependencies.json')).js;
  gulp.src(jsDeps)
    .pipe(concat('all.js'))
    //.pipe(uglify())
    .pipe(rename({ extname: '.min.js' }))
    .pipe(gulp.dest('compiled'))
});

gulp.task('minify_css', function() {
  var deps = JSON.parse(fs.readFileSync('dependencies.json'));
  
  var cssStream = gulp.src(deps.css)
    .pipe(cleanCSS({compatibility: 'ie8'}));
  
  var scssStream = gulp.src(deps.scss)
    .pipe(sass().on('error', sass.logError));
    
  
  var mergedStreams = merge(cssStream,scssStream)
    .pipe(concat('all.css'))
    .pipe(rename({ extname: '.min.css' }))
    .pipe(gulp.dest('compiled/'))
  
    return mergedStreams;
});

gulp.task('watch', function () {
  
  if( fs.existsSync('env.dev.json') ) {
    var devEnv = JSON.parse(fs.readFileSync('env.dev.json'));
    browserSync.init(devEnv);
    gulp.watch([
      __dirname+"/compiled/all.min.css",
      __dirname+"/compiled/all.min.js",
      __dirname+"/*.php",
      __dirname+"/**/*.php"
    ]).on("change", reload);
  }
  
  gulp.watch([
    __dirname+"/src/**/*.scss",
    __dirname+'/src/**/*.css'
  ], ['minify_css']);
  
  gulp.watch(__dirname+'/src/js/*.js', ['minify_js']);
  
  
});

// init a theme using this pattern "gulp init_theme --option db='wp_theme_fw' --option user='wp_theme_fw' --option pass='password' --option host='localhost' --option apache_hostname='local.wp_theme_framework.com'"
gulp.task('init_theme', function() {
  
  
  var opts = {
    'db':'',
    'user':'',
    'pass':'',
    'host':'localhost',
    'unique_keys':'',
    'apache_hostname':''
  };

  // get options and assign them to variables
  process.argv.forEach(function(opt) {
    Object.keys(opts).forEach(function(optRef) {
      if( opt.indexOf(optRef+'=') !== -1 ) {
        opts[optRef] = opt.replace(optRef+'=','');
      }
    });
  });
  
  if( opts.apache_hostname != '' ) {
    fs.readFile(__dirname+'/node_templates/env.dev.tpl', 'utf8', function (err,envTpl) {
      envTpl = envTpl.replace(/{{apache_hostname}}/gi,opts.apache_hostname);
      fs.writeFile('env.dev.json',envTpl,function(err) {
        if(err) throw err;
        console.log('BrowserSync options file created! @ env.dev.json');
      });
    });
  }
  
  // --> create the storage directory
  var storageDir = __dirname+'/../../../storage';
  if (!fs.existsSync(storageDir)){
    fs.mkdirSync(storageDir);
    // --> create the deny access .htaccess file
    fs.writeFile(storageDir+'/.htaccess','Deny from all',function(err) {
      if(err) throw err;
      fs.writeFile(storageDir+'/.env','ENVIRONMENT="local"',function(err) {
        if(err) throw err;
        console.log('Storage directory created! @ /storage');
      });
    });
  } else {
    console.log('Storage directory already exists');
  }
  
  // --> setup the wp_config file
  var configFile = __dirname+'/../../../wp-config.php';
  if(!fs.existsSync(configFile)) {
    var https = require('https');
    
    fs.readFile(__dirname+'/node_templates/wp-config.tpl', 'utf8', function (err,configTpl) {
      if (err) throw err;
      // --> get fresh keys from WP api.
      https.get('https://api.wordpress.org/secret-key/1.1/salt', function(response) {
        var body = '';
        response.on('data', function(d) {
          body += d;
        });
        response.on('end', function() {
          opts.unique_keys = body;
          
          // --> loop through variables and replace them in the template then write them to the config file
          Object.keys(opts).forEach(function(optRef) {
            configTpl = configTpl.replace('{{'+optRef+'}}',opts[optRef]);
          });
          
          // --> write the config file
          fs.writeFile(configFile,configTpl,function(err) {
            if(err) throw err;
            console.log('Config file created! @ wp-config.php');
          });
        });
      });
    });
    
  } else {
    console.log('wp-config.php already exists');
  }
  
});

gulp.task('default', ['minify_js','minify_css']);