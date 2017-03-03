module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    qunit: {
      all: ['web/assets/js/tests/**/*.html']
    },
    watch: {
      files: ['web/assets/js/**/*.js', 'web/assets/js/tests/**/*'],
      tasks: ['qunit']
    }
  });

  grunt.loadNpmTasks('grunt-contrib-qunit');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', ['qunit', 'watch']);
};
