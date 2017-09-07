var gulp = require('gulp');
var bower = require('gulp-bower');
var uglify = require('gulp-uglify');
var plumber = require('gulp-plumber');
var filter = require('gulp-filter');
//var imagemin = require('gulp-imagemin');
//var compass = require('gulp-compass');
var watch = require('gulp-watch');
var sass = require('gulp-sass');
var elixir = require('laravel-elixir');



gulp.task('copy', function () {
    // foundation
    //gulp.src("vendor/bower/foundation/css/foundation.min.css")
    //    .pipe(gulp.dest("public/assets/vendor/foundation/css/"));
    gulp.src("vendor/bower/foundation/css/normalize.min.css")
        .pipe(gulp.dest("public/assets/vendor/foundation/css/"));
    gulp.src("vendor/bower/foundation/js/foundation.min.js")
        .pipe(gulp.dest("public/assets/vendor/foundation/js/"));
    gulp.src("vendor/bower/foundation/js/foundation/foundation.offcanvas.js")
        .pipe(gulp.dest("public/assets/vendor/foundation/js/"));
    gulp.src("vendor/bower/foundation/js/vendor/*")
        .pipe(gulp.dest("public/assets/vendor/foundation/js/vendor/"));

    // Fontawesome
    gulp.src("vendor/bower/font-awesome/css/font-awesome.min.css")
        .pipe(gulp.dest("public/assets/vendor/font-awesome/css/"));
    gulp.src("vendor/bower/font-awesome/fonts/*")
        .pipe(gulp.dest("public/assets/vendor/font-awesome/fonts/"));

    //slick
    gulp.src("vendor/bower/slick-carousel/slick/slick.css")
        .pipe(gulp.dest("public/assets/vendor/slick/"));
    gulp.src("vendor/bower/slick-carousel/slick/slick-theme.css")
        .pipe(gulp.dest("public/assets/vendor/slick/"));
    gulp.src("vendor/bower/slick-carousel/slick/slick.min.js")
        .pipe(gulp.dest("public/assets/vendor/slick/"));
    gulp.src("vendor/bower/slick-carousel/slick/ajax-loader.gif")
        .pipe(gulp.dest("public/assets/vendor/slick/"));
    gulp.src("vendor/bower/slick-carousel/slick/fonts/*")
        .pipe(gulp.dest("public/assets/vendor/slick/fonts/"));  

   //jquery
    gulp.src("vendor/bower/jquery/dist/jquery.min.js")
        .pipe(gulp.dest("public/assets/vendor/jquery/"));
    gulp.src("vendor/bower/jquery-ui/jquery-ui.min.js")
        .pipe(gulp.dest("public/assets/vendor/jquery-ui/"));
    gulp.src("vendor/bower/jquery-ui/themes/smoothness/jquery-ui.min.css")
        .pipe(gulp.dest("public/assets/vendor/jquery-ui/themes/smoothness/"));    

  // bootstrap
    gulp.src("vendor/bower/bootstrap/dist/css/bootstrap.min.css")
        .pipe(gulp.dest("public/assets/vendor/bootstrap/css"));    
    gulp.src("vendor/bower/bootstrap/dist/js/bootstrap.min.js")
        .pipe(gulp.dest("public/assets/vendor/bootstrap/js"));    

    /****
     * jquery plugins 
     ***/ 
    //lazyload
    gulp.src("vendor/bower/jquery_lazyload/jquery.lazyload.js")
        .pipe(gulp.dest("public/assets/vendor/jquery/plugin/"));
    //nicescroll
    gulp.src("vendor/bower/jquery.nicescroll/jquery.nicescroll.min.js")
            .pipe(gulp.dest("public/assets/vendor/nicescroll/"));
    //transit
    gulp.src("vendor/bower/jquery.transit/jquery.transit.js")
            .pipe(gulp.dest("public/assets/vendor/jquery-transit/"));
    

});

gulp.task('copyNativeShare', function () {
    //nativeShare.js
    gulp.src("resources/assets/vendor/nativeShare/*")
            .pipe(gulp.dest("public/assets/vendor/nativeShare/"));
    gulp.src("resources/assets/vendor/nativeShare/icon/*")
            .pipe(gulp.dest("public/assets/vendor/nativeShare/icon/"));
});

gulp.task('uglify', function() {
  return gulp.src('resources/assets/js/*.js')
          //.pipe(plumber())
          //.pipe(uglify())
          .pipe(gulp.dest('public/assets/js'));
});

/*
gulp.task('compass',function() {
  return gulp.src('resources/assets/sass/*.scss')
          .pipe(plumber({
            errorHandler: function(error) {
              console.log(error.message);
              this.emit('end');
            }
          }))
          .pipe(compass({
              config_file: 'config.rb',
              css: 'public/assets/css',
              sass: 'resources/assets/sass'
          }))
          .pipe(gulp.dest('public/assets/css'))
          .pipe(filter('public/assets/css/*.css'));
});
*/

gulp.task('sass', function(){
    return gulp.src('resources/assets/sass/*.scss')
            .pipe(sass({ includePaths : ['./vendor/bower/foundation/scss/'] }))
            .pipe(gulp.dest('public/assets/css'))
            .pipe(filter('public/assets/css/*.css'));
});

gulp.task('images', function() {
  gulp.src('resources/assets/img/*')
          //.pipe(imagemin())
          .pipe(gulp.dest('public/assets/img'));
});

gulp.task('pure-css', function() {
  gulp.src('resources/assets/css/*')
          .pipe(gulp.dest('public/assets/css'));
});

gulp.task('admin-css',function() {
    return gulp.src('resources/assets/admin/sass/*.scss')
          .pipe(sass())
          .pipe(gulp.dest('public/assets/admin/css'))
          .pipe(filter('public/assets/admin/css/*.css')); 
    /*
    return gulp.src('resources/assets/admin/sass/*.scss')
      .pipe(plumber({
        errorHandler: function(error) {
    console.log(error.message);
    this.emit('end');
        }
      }))
      .pipe(compass({
        config_file: 'admin.config.rb',
        css: 'public/assets/admin/css',
        sass: 'resources/assets/admin/sass/'
      }))
      .pipe(gulp.dest('public/assets/admin/css'));
    */
});

/* Watch Files For Changes */
gulp.task('watch', function() {
    gulp.watch('resources/assets/js/*.js',['uglify']);
    gulp.watch('resources/assets/sass/*', ['sass']);
    gulp.watch('resources/assets/img/*', ['images']);
    gulp.watch('resources/assets/vendor/nativeShare/*', ['copyNativeShare']);
    gulp.watch('resources/assets/admin/sass/*', ['admin-css']);
});


gulp.task('default', ['copy', 'copyNativeShare', 'uglify', 'sass', 'images', 'pure-css', 'admin-css', 'watch']);
