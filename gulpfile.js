var gulp = require('gulp')
var browserify = require('browserify')
var jadeify = require('jadeify')
//var babelify = require('babelify')
var buffer = require('vinyl-buffer')
var source = require('vinyl-source-stream')

var replace = require('gulp-replace');
var gulpIgnore = require("gulp-ignore");

var stylus = require('gulp-stylus')
var concat = require('gulp-concat-css')
var nib = require('nib')
var autoprefixer = require('gulp-autoprefixer');

var minify = require('gulp-minify-css')
var uglify = require('gulp-uglify')

var rename = require("gulp-rename");
var jade = require('gulp-jade');

var util = require('gulp-util');

var dev = false,

BuildDistTemplate = 'Z:/ProgramacionAvanzada/misaplicaciones.com/9/templates_sitios_dinamicos/1/',
BuildDistCss = 'Z:/ProgramacionAvanzada/misaplicaciones.com/9/recursos/elementos/',
BuildDistColor = 'Z:/ProgramacionAvanzada/misaplicaciones.com/9/recursos/colores/',

cssSrc = [
  "./dev/componentes/_base/normalize.css",
  "./dev/componentes/_base/base.css",
  "./dev/componentes/_base/componentes.css",
  "./dev/componentes/grilla/grilla.css",
  "./dev/componentes/header_menu/headers.css",
  "./dev/componentes/header_menu/menues.css",
  "./dev/componentes/footer/footer.css",
  "./dev/componentes/elementos_widgets/separador.css",
  "./dev/componentes/elementos_widgets/redessociales.css",
  "./dev/componentes/elementos_widgets/boxes.css",
  "./dev/componentes/elementos_widgets/iconos.css",
  "./dev/componentes/elementos_widgets/productos_notas.css",
  "./dev/componentes/elementos_widgets/titulos.css",
  "./dev/componentes/elementos_widgets/galeria_old.css",
  "./dev/componentes/elementos_widgets/carrito.css",
  "./dev/componentes/hover/hover.css",
  "./dev/componentes/animate/animate.css",
  "./dev/componentes/_base/util.css",
  "./dev/componentes/_base/utilidades.css",
  "./dev/componentes/template/template.css",
  "./dev/componentes/plugins/owl.css",
  "./dev/componentes/_base/typo.css",
],
colorSrc = './dev/colores/color.styl',
templateSrc = './dev/template/index.jade';

gulp.task('default', ['css','template'])
gulp.task('buildDev', ['dev','css','template'])
gulp.task('buildDemo', ['demo','css', 'template'])


/*TEMPLATE RENDER*/

/*##################*/
/*#### COLOR ####*/
/*##################*/

gulp.task('color', function (paleta) {

  gulp.src(colorSrc)
    .pipe(stylus({ import: ['nib','/paletas/'+util.env.p], use: nib()}))

    .pipe(rename(function (path) {
      path.basename = util.env.p;
    }))
    .pipe(gulp.dest(BuildDistColor));
});




var colorSrcAll = './dev/colores/paletas/*.styl';

gulp.task('colorAll', function () {
  gulp.src(colorSrcAll)
    .pipe(gulpIgnore.exclude(renderColors));
    /*NOTA: uso el plugin de exlude solo para iterar por cada archivo*/
});

function renderColors(file) {
   var paleta = path.basename(file.path, '.styl');

   gulp.src(colorSrc)
     .pipe(stylus({ import: ['nib','/paletas/'+paleta], use: nib()}))
     .pipe(rename(function (path) {
       path.basename = paleta;
     }))
     .pipe(gulp.dest(BuildDistColor));

   return true;
}

/*##################*/
/*####  RENDER  ####*/
/*##################*/

gulp.task('demo', function () {
  dev = true;
  BuildDest = "./demo/";
  BuildDistTemplate = BuildDest;
  BuildDistCss = BuildDest;
  BuildDistColor = BuildDest;
});

gulp.task('dev', function () {
  BuildDest = "./dist/";
  BuildDistTemplate = BuildDest;
  BuildDistCss = BuildDest;
  BuildDistColor = BuildDest;
});


gulp.task('template', function() {
    gulp.src(templateSrc)
        .pipe(jade({
          locals: {
            dev: dev
          },
          pretty: true}))
        .pipe(rename("index.htm"))
        .pipe(gulp.dest(BuildDistTemplate));
});

gulp.task('css', function() {
  return gulp.src(cssSrc)
    //.pipe(stylus({ use: nib() }))
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 9'))
    .pipe(concat('se.css'))
    .pipe(replace('http://replace.com/', 'http://app.misaplicaciones.com/9/recursos/img/'))
    //.pipe(minify())
    .pipe(gulp.dest(BuildDistCss));
})

/*
            .pipe(plugins.autoprefixer(
                {
                    browsers: [
                        '> 1%',
                        'last 2 versions',
                        'firefox >= 4',
                        'safari 7',
                        'safari 8',
                        'IE 8',
                        'IE 9',
                        'IE 10',
                        'IE 11'
                    ],
                    cascade: false
                }
            ))*/
            /*.pipe(gulp.dest('build')).on('error', gutil.log)*/
            


var svgstore = require('gulp-svgstore');
var svgmin = require('gulp-svgmin');
var path = require('path');
 
gulp.task('svgstore', function () {
    return gulp
        .src('svg/svgs*.svg')
        .pipe(svgmin(function (file) {
            var prefix = path.basename(file.relative, path.extname(file.relative));
            return {
                plugins: [{
                    cleanupIDs: {
                        prefix: prefix + '-',
                        minify: false
                    }
                }]
            }
        }))
        .pipe(svgstore())
        .pipe(gulp.dest('svg/build'));
});



//gulp.task('demo', ['css', 'jade'])
/*
gulp.task('js', function() {
  return browserify({
    entries: './dev/app.js',
    transform: [ babelify, jadeify]
  })
  .bundle()
  .pipe(source('app.js'))
  .pipe(buffer())
  .pipe(uglify())
  .pipe(gulp.dest('./public/'))
})*/




gulp.task('copy', function() {
   gulp.src('./dist/se.css')
   .pipe(gulp.dest('./fonts'));
});