/*
 |------------------------------------------------------------------------------
 | Grunt Setup
 |------------------------------------------------------------------------------
 |
 | Define Grunt settings and tasks.
 |
 */
module.exports = function (grunt) {
    "use strict";

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            dist: {
                files: {
                    'public/js/fields.js': [
                        'resources/components/bower/selectize/dist/js/standalone/selectize.min.js',
                        'resources/scripts/fields.js',
                        'resources/scripts/attachment.js',
                        'resources/scripts/gallery.js',
                        'resources/scripts/relation.js',
                        'resources/scripts/repeater.js'
                    ]
                }
            }
        },
        stylus: {
            options: {
                'include css': true
            },
            dist: {
                files: {
                    'public/css/fields.css': 'resources/styles/fields.styl'
                }
            }
        },
        postcss: {
            options: {
                //map: true,
                processors: [
                    require('autoprefixer')({browsers: 'last 2 versions'}),
                    require('cssnano')()
                ]
            },
            dist: {
                src: ['*.css', 'public/css/*.css']
            }
        },
        watch: {
            scripts: {
                files: ['resources/scripts/**/*.js'],
                tasks: ['newer:uglify'],
                options: {
                    spawn: false
                }
            },
            styles: {
                files: ['resources/styles/**/*.styl'],
                tasks: ['newer:stylus', 'newer:postcss'],
                options: {
                    spawn: false
                }
            }
        }
    });

    // Load the plugins
    grunt.loadNpmTasks('grunt-contrib-stylus');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-newer');
    grunt.loadNpmTasks('grunt-postcss');
};
