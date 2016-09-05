# Sitio Express 2.5

Este es el nuevo template de Sitio Express.

## Requisitos
Instalar node.js (https://nodejs.org/)


## Instalación / Compilación - Ejecutar en la consola de node.
```
npm install //(la primera vez)
gulp demo //(la primera vez y cada vez que se realicen cambios en los archivos /lib/)
gulp public // cuando este todo aprobado, esto arma la version comprimida, para usar con sitio express.


gulp.task('copy', function() {
    gulp.src(['underscore/underscore.js', 'jquery/jquery.js', 'angular/angular.js', 'angular-route/angular-route.js', 'ng-grid/ng-grid-2.0.8.debug.js'])
        .pipe(gulp.dest('build/libs/'))
    gulp.src(['ng-grid/ng-grid.css', 'jquery.ui/themes/base/jquery.ui.theme.css'])
        .pipe(gulp.dest('build/css/'))
    gulp.src('images/**/*')
        .pipe(gulp.dest('build/css/'))
});