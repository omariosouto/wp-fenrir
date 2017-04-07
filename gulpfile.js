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
const argv = require('yargs').argv;
const fs = require('fs');


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
	        presets: ['es2015-without-strict']
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

gulp.task('component', function(){
	const componentName = argv.c;
	const dir = `./components/${componentName}`;

	// Variables for init.php
	const initBase = './_templates/components/component/init.php';
	const destInit = `${dir}/init.php`;

	// Variables for the-component.php
	const theComponentBase = './_templates/components/component/the-component.php';
	const destTheComponent = `${dir}/the-component.php`;

	// Variables for index.js
	const jsBase = './_templates/components/component/index.js';
	const destJs = `${dir}/index.js`;

	// Variables for index.styl
	const stylusBase = './_templates/components/component/index.styl';
	const destStylus = `${dir}/index.styl`;


	if (!fs.existsSync(dir)){

		// Create Dir
		fs.mkdirSync(dir);
		fs.closeSync(fs.openSync(destInit, 'w'));

		// Read init.php
		fs.readFile(initBase, 'utf8', function (err,data) {
			if (err) {
				return console.log(err);
			}
			if (data) {
				var result = data.replace(/%COMPONENT_NAME%/g, componentName);
				// Crio o Arquivo init.php
				console.log(destInit);
				fs.writeFile(destInit, result, 'utf8', function (err) {
					if (err) return console.log(err);
					console.log("The file was saved!");
				});
				return result;
			}
		});

		// Read the-component.php
		fs.readFile(theComponentBase, 'utf8', function (err,data) {
			if (err) {
				return console.log(err);
			}
			if (data) {
				var result = data.replace(/%COMPONENT_NAME%/g, componentName.replace('-', '_'));
				// Crio o Arquivo init.php
				console.log(destTheComponent);
				fs.writeFile(destTheComponent, result, 'utf8', function (err) {
					if (err) return console.log(err);
					console.log("The file was saved!");
				});
				return result;
			}
		});

		// Read index.js
		fs.readFile(jsBase, 'utf8', function (err,data) {
			if (err) {
				return console.log(err);
			}
			if (data) {
				var result = data.replace(/%COMPONENT_NAME%/g, componentName);
				// Crio o Arquivo init.php
				console.log(destJs);
				fs.writeFile(destJs, result, 'utf8', function (err) {
					if (err) return console.log(err);
					console.log("The file was saved!");
				});
				return result;
			}
		});

		// Read index.styl
		fs.readFile(stylusBase, 'utf8', function (err,data) {
			if (err) {
				return console.log(err);
			}
			if (data) {
				var result = data.replace(/%COMPONENT_NAME%/g, componentName);
				// Crio o Arquivo init.php
				console.log(destStylus);
				fs.writeFile(destStylus, result, 'utf8', function (err) {
					if (err) return console.log(err);
					console.log("The file was saved!");
				});
				return result;
			}
		});



		console.log(color('[COMPONENTE CRIADO COM SUCESSO]', 'GREEN'));
	} else {
		console.log(color('[COMPONENTE JÁ EXISTE]', 'RED'));
	}
});

/* Linters
====================================*/
gulp.task('stylint', shell.task([
	'stylint components/ -c .stylintrc'
]));


gulp.task('default', function(cb) {
  runSequence(['distJS', 'distCSS', 'sprites'], cb)
});