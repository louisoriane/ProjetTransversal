module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		/*
		concat: {
			prod: {
				files: {
					'assets/css/build.scss': 'assets/css/*.scss'
				}
			}
			dist: {
				src: ['assets/css/*.scss'],
				dest: 'assets/css/build.scss',
			}
		},*/
		watch: {
			css: {
				files: 'assets/css/style.scss',
				tasks: ['sass'],
				options: {
					livereload: true,
				},
			},
		},
        sass: {
            prod: {
				files: {
					'assets/css/style.css': ['assets/css/style.scss']
				}
            }
        },
		uglify: {
			prod: {
				files: {
					'assets/js/script.min.js': ['assets/js/*.js']
				}
			}
		}
	});
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', [
		'watch',
		'sass',
		'uglify'
	]);
};