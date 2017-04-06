const gulp = require('gulp');
const stylus = require('gulp-stylus');
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');
const babel = require('gulp-babel');
const watch = require('gulp-watch');
const plumber = require('gulp-plumber');
const rename = require("gulp-rename");
const svg_sprite     = require('gulp-svg-sprite');
const color          = require('gulp-color');
const runSequence = require('run-sequence');
const stylint = require('gulp-stylint');
const shell = require('gulp-shell');
const minify = require('gulp-minify');


// - Sprites Generator
// - Icons
const configSvg = {
    mode: {
        symbol: {
            dest: 'sprite',
            sprite: 'svg_sprite.svg',
            example: true
        }
    },
    svg: {
        xmlDeclaration: false,
        doctypeDeclaration: false
    }
};

gulp.task('sprites', function(){
    return watch('./icons/svg/**/*.svg', { ignoreInitial: true }, function () {
      gulp.src('./icons/svg/**/*.svg')
      .pipe(svg_sprite(configSvg))
      .pipe(gulp.dest('./icons'))
      //.pipe(browserSync.stream())
      console.log(color('[SVG-ICON _sprite generated]', 'GREEN'));
    });
});


// - Distribution CSS Files
gulp.task('distCSS', function () {
	return watch(['components/**/*.styl', '!components/**/*.min.css'], function () {
		

		gulp.src(['components/**/*.styl', '!components/**/*.min.css'], { base: "./" })
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(stylus({
	      compress: true
		}))
		.pipe(rename(function (path) {
			path.dirname += "/dist";
			path.extname = ".min.css"
		}))
		.pipe(sourcemaps.write('.'))
		//.pipe(shell.task([ 'stylint components/ -c .stylintrc' ]))
		.pipe(gulp.dest('.'))
		console.log(color('[CSS generated]', 'CYAN'));
	});
});

// - Distribution JavaScript Files
gulp.task('distJS', () => {
    return watch(['components/**/*.js', '!components/**/*.min.js'], function () {
    	gulp.src(['components/**/*.js', '!components/**/*.min.js'], { base: "./" })
	    .pipe(plumber())
	    .pipe(sourcemaps.init())
	    .pipe(babel({
	        presets: ['es2015']
	    }))
		.pipe(rename(function (path) {
			path.dirname += "/dist";
			path.extname = ".min.js"
		}))
	    .pipe(sourcemaps.write('.'))
	    .pipe(gulp.dest('.'));
	    console.log(color('[SVG-ICON _sprite generated]', 'YELLOW'));
    });
});

// - JavaScript Libs
gulp.task('distJSLibs', function () {
  return gulp.src([
      './node_modules/jquery/dist/jquery.min.js',
    ])
    .pipe(concat('libs.js'))
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(minify({
      ext:{
        src:'.js',
        min:'.min.js'
      },
      exclude: ['tasks'],
      ignoreFiles: ['.combo.js', '-min.js']
    }))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./libs/js'));

    console.log(color('[JSLibs _dist generated]', 'YELLOW'));
});


/* Linters
====================================*/
gulp.task('stylint', shell.task([
	'stylint components/ -c .stylintrc'
]));


gulp.task('default', function(cb) {
  runSequence('distJSLibs', ['distJS', 'distCSS', 'sprites'], cb)
});