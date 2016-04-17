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

gulp.task('default', ['minify_js','minify_css']);