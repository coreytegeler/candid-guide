var argv = require('yargs').argv;
var gulpif = require('gulp-if');
var rename = require('gulp-rename');
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var cleanCSS = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var gutil = require('gulp-util');
var browsersync = require('browser-sync').create();

function compileSass()  {
	var sassOptions = {
		compress: argv.prod ? true : false
	};
	var apOptions = {
		browsers: ['> 0.5%', 'last 5 versions', 'Firefox ESR', 'not dead']
	};
	return gulp.src('./src/style.scss')
		.pipe(sourcemaps.init())
		.pipe(plumber())
		.pipe(sass(sassOptions))
		.pipe(autoprefixer(apOptions))
		.pipe(cleanCSS({compatibility: 'ie8'}))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('./'))
	.on('end', function() {
		log('Sass done');
		if (argv.prod) log('CSS minified');
	});
}

function watchFiles() {
	gulp.watch('./src/*.scss', compileSass);
}

gulp.task('watch', gulp.parallel(compileSass, watchFiles));
gulp.task('build', gulp.parallel(compileSass));

function log(message) {
	gutil.log(gutil.colors.bold.green(message));
}