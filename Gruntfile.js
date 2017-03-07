module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    phpunit: {
      classes: {
        dir: 'tests'
      },
      options: {
        configuration: 'phpunit.xml.dist',
        failOnFailures: true,
        excludeGroup: 'functionalTesting'
      }
    },
    qunit: {
      all: ['web/assets/js/tests/**/*.html']
    },
    watch: {
      phpunit: {
        files: ['tests/**/*'],
        tasks: ['phpunit']
      },
      qunit: {
        files: ['web/assets/js/**/*.js', 'web/assets/js/tests/**/*'],
        tasks: ['qunit']
      }
    }
  });

  grunt.loadNpmTasks('grunt-phpunit');
  grunt.loadNpmTasks('grunt-contrib-qunit');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', ['phpunit', 'qunit', 'watch']);
};
