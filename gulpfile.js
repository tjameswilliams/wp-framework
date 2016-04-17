'use strict';

var gulp = require('gulp');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var cleanCSS = require('gulp-clean-css');
var sass = require('gulp-sass');
var fs = require('fs');
var merge = require('merge-stream');

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
  gulp.watch(['src/**/*.scss','src/**/*.css'], ['minify_css']);
  gulp.watch('src/js/*.js', ['minify_js']);
});

// init a theme using this pattern "gulp init_theme --option db='wp_theme_fw' --option user='wp_theme_fw' --option pass='password' --option host='localhost'"
gulp.task('init_theme', function() {
  
  // --> create the storage directory
  var storageDir = __dirname+'/../../../storage';
  if (!fs.existsSync(storageDir)){
    fs.mkdirSync(storageDir);
    // --> create the deny access .htaccess file
    fs.writeFile(storageDir+'/.htaccess','Deny from all',function(err) {
      if(err) throw err;
      fs.writeFile(storageDir+'/.env','ENVIRONMENT="local"',function(err) {
        if(err) throw err;
        console.log('Storage directory created!');
      });
    });
  } else {
    console.log('Storage directory already exists');
  }
  
  // --> setup the wp_config file
  var configFile = __dirname+'/../../../wp-config.php';
  if(!fs.existsSync(configFile)) {
    
    var https = require('https');
    
    var opts = {
      'db':'',
      'user':'',
      'pass':'',
      'host':'localhost',
      'unique_keys':''
    };
  
    // get options and assign them to variables
    process.argv.forEach(function(opt) {
      Object.keys(opts).forEach(function(optRef) {
        if( opt.indexOf(optRef+'=') !== -1 ) {
          opts[optRef] = opt.replace(optRef+'=','');
        }
      });
    });
    
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
            console.log('Config file created!');
          });
        });
      });
    });
    
  } else {
    console.log('wp-config.php already exists');
  }
  
});

gulp.task('default', ['minify_js','minify_css']);