const gulp = require('gulp');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const sass = require('gulp-sass')(require('sass'));
const rename = require('gulp-rename');

gulp.task('sass', function() {
    gulp.src('./test-theme/wp-content/themes/test-theme/assets/sass/style.scss')
        .pipe(sass())
        .pipe(gulp.dest('./test-theme/wp-content/themes/test-theme/assets/css'))
        .pipe(gulp.src('./test-theme/wp-content/themes/test-theme/assets/sass/style.scss'))
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .pipe(rename('style.min.css'))
        .pipe(gulp.dest('./test-theme/wp-content/themes/test-theme/assets/css'));
});

gulp.task('js', function() {
    gulp.src('./test-theme/wp-content/themes/test-theme/assets/js/main.js')
        .pipe(concat('main.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./test-theme/wp-content/themes/test-theme/assets/js/build'));
});
