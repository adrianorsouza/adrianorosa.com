"use strict";

module.exports = function( grunt ) {

  // Load all tasks
  require("matchdep").filterDev("grunt-*").forEach(grunt.loadNpmTasks);

  // Project configuration.
  grunt.initConfig({

   pkg: grunt.file.readJSON('package.json'),

   banner:'/*!\n' +
   ' * URI        : <%= pkg.clientsite %>\n' +
   ' * project    : <%= pkg.description %>\n' +
   ' * version    : <%= pkg.version %>\n' +
   ' * author     : <%= pkg.author %> (<%= pkg.homepage %>)\n' +
   ' * last build : <%= grunt.template.today("dd/mm/yyyy") %> <%= grunt.template.today("hh:MM:ss") %>\n' +
   ' */',

   clean: {
      build: {
         src: ["<%= pkg.build %>/assets/js/*"]
      },
      css: {
         src: ["<%= pkg.build %>/assets/css/*", '<%= pkg.dist %>/css/*'],
      },
      fonts: {
         src: ["<%= pkg.build %>/assets/fonts/*"],
      }
   },

   // ----------------------------
   // Copy vendor files files
   // ----------------------------
   copy: {
      jquery: {
         options: {
            process: function (content, srcpath) {
               return content.replace("//# sourceMappingURL=jquery.min.map","");
            }
         },
         src: '<%= pkg.vendor %>/jquery/dist/jquery.min.js',
         dest: '<%= pkg.build %>/assets/js/jquery.min.js'

      },

      components: {
         expand: true,
         flatten: true,
         src:  [
         '<%= pkg.vendor %>/respond/dest/respond.min.js',
         '<%= pkg.vendor %>/html5shiv/dist/html5shiv.min.js',
         '<%= pkg.src %>/js/ie10-viewport.js'],

         dest: '<%= pkg.build %>/assets/js/'
      },

      includes: {
         cwd: '<%= pkg.src %>/includes/',
         expand: true,
         src:  ['**'],
         dest: '<%= pkg.build %>/assets/includes/'
      },

      fonts: {
         cwd: '<%= pkg.src %>/fonts/',
         expand: true,
         src: ['**'],
         dest: '<%= pkg.build %>/assets/fonts/'

      }
   },

   // ----------------------------
   // Minify scripts
   // ----------------------------
   uglify: {
      main: {
         options: {
            preserveComments: 'none'
         },
         src: [
         '<%= pkg.vendor %>/bootstrap/js/collapse.js',
         '<%= pkg.src %>/js/application-script.js',
         ],
         dest: '<%= pkg.build %>/assets/js/main.js'
      },
   },

   // ----------------------------
   // Compile styles
   // ----------------------------
   less: {
      app: {
         options: {
            strictMath: true,
            compress: false
         },
         files: {
            '<%= pkg.src %>/dist/css/bootstrap.css': '<%= pkg.src %>/less/bootstrap.less'
         }
      }
   },

   // ----------------------------
   // CSS combine
   // ----------------------------
   cssmin: {
      options: {
       keepSpecialComments: 0,
       banner: '<%= banner %>'
    },
    combine: {
      files: {
         '<%= pkg.build %>/assets/css/application-style.css': ['<%= pkg.dist %>/css/**.css', '<%= pkg.src %>/css/**.css']
         }
      }
   },

   // ----------------------------
    // Watch files
    // ----------------------------
    watch: {

      allcss: {
         files: ['<%= pkg.src %>/css/*.css', '<%= pkg.src %>/less/**'],
         tasks: ['less', 'css']
      },

      less: {
         files: ['<%= pkg.src %>/less/**'],
         tasks: ['less', 'less:font', 'css']
      },

      css: {
         files: ['<%= pkg.src %>/css/*.css'],
         tasks: 'css'
      },

      js: {
         files: ['<%= pkg.src %>/js/*.js'],
         tasks: 'js'
      }
   }

});

  // default task
  grunt.registerTask( 'default', [ 'watch' ] );

  // CSS task.
  grunt.registerTask('css', ['cssmin']);

  // JS task.
  grunt.registerTask('js', ['uglify']);
  grunt.registerTask('cpcom', ['copy:components']);

  grunt.registerTask('build', ['clean', 'copy', 'js', 'less', 'css']);

};
