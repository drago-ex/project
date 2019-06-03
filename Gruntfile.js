module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            options: {
                sourceMap: true
            },
            my_target: {
                files: {
                    'www/js/main.js': [
                        'vendor/nette/forms/src/assets/netteForms.js',
                    ]
                }
            }
        }
    });

    // grunt task names
    grunt.loadNpmTasks('grunt-contrib-uglify');
};
