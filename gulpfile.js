var gulp = require('gulp');
var browserSync = require('browser-sync');
var reload = browserSync.reload;

gulp.task('default', ['serve']);

gulp.task('serve', [], function () {
    browserSync({
        notify: false,
        server: {
            baseDir: '.'
        }
    });

    gulp.watch(['views/**/*.html'], reload);
    gulp.watch(['app/controllers/**/*.js'], reload);
    gulp.watch(['app/services/**/*.js'], reload);
});