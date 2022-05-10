module.exports = function (grunt) {
    grunt.initConfig({
        concat_sourcemap: {
            options: {
                sourcesContent: false
            },
            all: {
                files: {
                    'static/backend/scripts/all.js': grunt.file.readJSON('backend/assets/js.json'),
                    'static/backend/styles/all.css': grunt.file.readJSON('backend/assets/css.json'),
                    
                    //'static/frontend/scripts/all.js': grunt.file.readJSON('frontend/assets/js.json'),
                    //'static/frontend/styles/all.css': grunt.file.readJSON('frontend/assets/css.json')
                }
            }
        },
        copy: {
            main: {
                files: [
                    {
                        expand: true,
                        cwd: '.',
                        src: 'vendor/bower/bootstrap-sass-official/assets/fonts/bootstrap/*',
                        dest: 'static/backend'
                    },
                    {
                        expand: true,
                        cwd: '.',
                        src: 'vendor/bower/font-awesome/fonts/*',
                        dest: 'static/backend'
                    },
                    {
                        expand: true,
                        cwd: 'backend/assets/assets/css',
                        src: 'fonts/*',
                        dest: 'static/backend/styles'
                    },
                    /*{
                        expand: true,
                        cwd: '.',
                        src: 'vendor/bower/bootstrap-sass-official/assets/fonts/bootstrap/*',
                        dest: 'static/frontend'
                    },
                    {
                        expand: true,
                        cwd: '.',
                        src: 'vendor/bower/font-awesome/fonts/*',
                        dest: 'static/frontend'
                    },
                    {
                        expand: true,
                        cwd: 'backend/assets/assets/css',
                        src: 'fonts/*',
                        dest: 'static/frontend/styles'
                    }*/
                ]
            }
        },
        uglify: {
            all: {
                options: {
                    sourceMap: false,
                    sourceMapIncludeSources: false,
                    drop_console: true
                },
                files: {
                    'static/backend/scripts/all.js': 'static/backend/scripts/all.js',
                    //'static/frontend/scripts/all.js': 'static/frontend/scripts/all.js'
                }
            }
        },
        cssmin: {
            dist: {
                options: {
                    keepSpecialComments: 0,
                    sourceMap: false
                },
                files: {
                    'static/backend/styles/all.css': [
                        '.tmp/styles/backend/{,*/}*.css',
                        'static/backend/styles/all.css'
                    ],
                    //'static/frontend/styles/all.css': [
                    //    '.tmp/styles/frontend/{,*/}*.css',
                    //    'static/frontend/styles/all.css'
                    //]
                }
            }
        },
        imagemin: {
            dist: {
                files: [//{
                        //expand: true,
                        //cwd: 'static/backend/images',
                        //src: '{,*/}*.{png,jpg,jpeg,gif}',
                        //dest: 'static/backend/images'
                    //},
                    {
                        expand: true,
                        cwd: 'static/static-images',
                        src: '{,*/}*.{png,jpg,jpeg,gif}',
                        dest: 'static/static-images'
                    },
                    //{
                    //    expand: true,
                    //    cwd: 'static/img',
                    //    src: '{,*/}*.{png,jpg,jpeg,gif}',
                    //    dest: 'static/frontend/img'
                    //}
                ]
            }
        },
        svgmin: {
            dist: {
                files: [//{
                     //   expand: true,
                     //   cwd: 'static/backend/images',
                     //   src: '{,*/}*.{svg}',
                     //   dest: 'static/backend/images'
                    //},
                    {
                        expand: true,
                        cwd: 'static/static-images',
                        src: '{,*/}*.{svg}',
                        dest: 'static/static-images'
                    },
                    //{
                    //    expand: true,
                    //    cwd: 'static/static-images',
                    //    src: '{,*/}*.{svg}',
                    //    dest: 'static/frontend/img'
                    //}
                ]
            }
        },
        // Compiles Sass to CSS and generates necessary files if requested
        compass: {
            options: {
                
                //cssDir: '.tmp/styles',
                importPath: './vendor/bower',
                httpImagesPath: '/images',
                httpGeneratedImagesPath: '/images/generated',
                httpFontsPath: '/styles/fonts',
                relativeAssets: false,
                assetCacheBuster: false,
                raw: 'Sass::Script::Number.precision = 10\n'
            },
            backend: {
                options: {
                    cssDir: '.tmp/styles/backend',
                    sassDir: 'backend/assets/styles',
                    generatedImagesDir: '.tmp/images/generated',
                    imagesDir: 'static/backend/images',
                    javascriptsDir: 'static/backend/scripts',
                    fontsDir: 'static/backend/styles/fonts',
                    generatedImagesDir: 'static/backend/images/generated'
                }
            },
            /*frontend: {
                options: {
                    cssDir: '.tmp/styles/frontend',
                    sassDir: 'frontend/assets/styles',
                    generatedImagesDir: '.tmp/images/generated',
                    imagesDir: 'static/frontend/images',
                    javascriptsDir: 'static/frontend/scripts',
                    fontsDir: 'static/frontend/styles/fonts',
                    generatedImagesDir: 'static/frontend/images/generated'
                }
            }*/
        },
        clean: {
            dist: {
                files: [
                    {
                        dot: true,
                        src: [
                            '.tmp',
                            'static/backend/styles',
                            'static/backend/scripts'
                        ]
                    },
                    /*{
                        dot: true,
                        src: [
                            '.tmp',
                            'static/frontend/styles',
                            'static/frontend/scripts'
                        ]
                    }*/
                ]
            },
            final: {
                files: [
                    {
                        dot: true,
                        src: [
                            'static/backend/scripts/{,*/}*.map',
                            'static/backend/styles/{,*/}*.map'
                        ]
                    },
                    //{
                        //dot: true,
                        //src: [
                        //    'static/frontend/scripts/{,*/}*.map',
                        //    'static/frontend/styles/{,*/}*.map'
                      //  ]
                    //}
                ]
            }
        },
        // Run some tasks in parallel to speed up the build process
        concurrent: {
            dist: [
                'compass:backend',
                //'compass:frontend',
                //'imagemin',
                //'svgmin'
            ]
        },
        // Add vendor prefixed styles
        postcss: {
            options: {
                processors: [
                    require('autoprefixer')({browsers: ['last 1 version']})
                ]
            },
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: 'static/backend/styles/',
                        src: '{,*/}*.css',
                        dest: 'static/backend/styles/'
                    },
                    //{
                    //    expand: true,
                    //    cwd: 'static/frontend/styles/',
                    //    src: '{,*/}*.css',
                    //    dest: 'static/frontend/styles/'
                    //}
                ]
            }
        }

    });

    // Plugin loading
    grunt.loadNpmTasks('grunt-concat-sourcemap');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-svgmin');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-concurrent');
    //grunt.loadNpmTasks('autoprefixer');
    grunt.loadNpmTasks('grunt-postcss');

    // Task definition
    grunt.registerTask('build', ['clean:dist', 'concurrent:dist', 'copy', 'concat_sourcemap', 'cssmin', 'postcss', 'uglify', 'clean:final']);
};