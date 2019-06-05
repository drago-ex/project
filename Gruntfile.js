module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            css: {
                src: ['node_modules/bootstrap/dist/css/bootstrap.css'],
                dest: 'assets/combined/css/style.css'
            },
            js: {
                src: ['vendor/nette/forms/src/assets/netteForms.js'],
                dest: 'assets/combined/js/app.js'
            }
        },
        uglify: {
            js: {
                src: 'assets/combined/js/app.js',
                dest: 'www/js/app.min.js'
            }
        },
        cssmin: {
            css: {
                src: 'assets/combined/css/style.css',
                dest: 'www/css/style.min.css'
            }
        },
        processhtml: {
            dist: {
                files: {
                    'app/module/web/presenter/templates/@layout.latte': [
                        'app/module/web/presenter/templates/@dev.latte'
                    ]
                }
            }
        },
    });
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-processhtml');
    grunt.registerTask('grunt-run', [
        'concat', 'uglify', 'cssmin', 'processhtml'
    ]);
};
