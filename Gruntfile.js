yaml = require('js-yaml')
fs = require('fs')

// --------------------------------------------------------------------------------------------------
// JS & (S)CSS should always be placed in the assets directory.
// Grunt will tweak the code to our preferences and put it into the assets directory.
//
const ASSETS_DIRECTORY = 'assets'
const PUBLIC_DIRECTORY = 'public'

// -------------------------------------------------------------------------------------------------
// Defining the configuration of our scss to css compile tasks
//
//   - loading all supported themes from the liip config
//   - creating an entry for every theme + admin
//
const SCSS_CONFIG = {}

//
// Retrieving all supported themes from the symfony liip config
//


const THEMES = loadThemesFromSymfonyParameters()

function loadThemesFromSymfonyParameters()
{
  try {
    const liip_config = yaml.safeLoad(
      fs.readFileSync('config/packages/liip_theme.yaml', 'utf8')
    );
    const themes = liip_config['parameters']['themes'];
    if (!Array.isArray(themes) || !themes.length) {
      console.error('Themes array is empty!');
    }
    return themes
  } catch (e) {
    console.error('Themes could not be loaded!\n'+ e);
    return undefined
  }
}



for (let index = 0; index < THEMES.length; index++) {
  let theme = THEMES[index]
  let baseCssPath = PUBLIC_DIRECTORY + '/css/' + theme + '/base.css'
  let baseFileConfig = {}
  baseFileConfig[baseCssPath] = [ ASSETS_DIRECTORY + '/css/themes/' + theme + '/' + theme + '.scss' ]
  SCSS_CONFIG[theme] =
  {
    options: {
      loadPath: [ ASSETS_DIRECTORY + '/css/base', ASSETS_DIRECTORY + '/css/themes/' + theme ],
      style: 'compressed',
      sourcemap: 'none'
    },
    files: [
      baseFileConfig,
      // copy plugins
      {
        expand: true,
        cwd: ASSETS_DIRECTORY + '/css/plugins/',
        src: ['*'],
        dest: PUBLIC_DIRECTORY + '/css/plugins/',
        extDot: 'first'
      },
      // every css/custom file gets a separate file
      {
        expand: true,
        cwd: ASSETS_DIRECTORY + '/css/custom/',
        src: ['**/*.scss'],
        dest: PUBLIC_DIRECTORY + '/css/' + theme + '/',
        ext: '.css',
        extDot: 'first'
      }
    ]
  }
}

let adminCssPath = PUBLIC_DIRECTORY + '/css/admin/admin.css'
let adminFileConfig = {}
adminFileConfig[adminCssPath] = [ ASSETS_DIRECTORY + '/css/plugins/*' ]

SCSS_CONFIG['admin'] = {
  files: [
    adminFileConfig,
    {
      expand: true,
      cwd: ASSETS_DIRECTORY + '/css/admin/',
      src: ['**/*.scss'],
      dest: PUBLIC_DIRECTORY + '/css/admin/',
      ext: '.css',
      extDot: 'first'
    }
  ]
}


// -------------------------------------------------------------------------------------------------
// Defining JavaScript paths:
//
let jsBaseSrc = [ ASSETS_DIRECTORY + '/js/base/*.js', ASSETS_DIRECTORY + '/js/globalPlugins/*.js' ]
let jsRegisterSrc = [ ASSETS_DIRECTORY + '/js/register/*.js' ]
let jsCustomSrc = ASSETS_DIRECTORY + '/js/custom'
let jsAnalyticsSrc = ASSETS_DIRECTORY + '/js/analytics'
let jsLocalPluginSrc = ASSETS_DIRECTORY + '/js/localPlugins'

// -------------------------------------------------------------------------------------------------
// Register all grunt tasks here:
//
module.exports = function (grunt) {
  require('jit-grunt')(grunt)
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    copy: {
      bootstrap_vendor: {
        expand: true,
        cwd: 'node_modules/bootstrap/',
        src: '**',
        dest: PUBLIC_DIRECTORY + '/bootstrap_vendor/'
      },
      font_awesome: {
        expand: true,
        cwd: 'node_modules/@fortawesome/',
        src: '**',
        dest: PUBLIC_DIRECTORY + '/font_awesome_wrapper/'
      },
      font_awesome_webfonts: {
        expand: true,
        cwd: PUBLIC_DIRECTORY + '/font_awesome_wrapper/fontawesome-free/webfonts',
        src: '**',
        dest: PUBLIC_DIRECTORY + '/webfonts/'
      },
      fonts: {
        expand: true,
        cwd: ASSETS_DIRECTORY + '/css/fonts',
        src: '**',
        dest: PUBLIC_DIRECTORY + '/css/fonts/'
      },
      images: {
        expand: true,
        cwd: ASSETS_DIRECTORY + '/images',
        src: '**',
        dest: PUBLIC_DIRECTORY + '/images/'
      },
      catblocks: {
        expand: true,
        cwd: ASSETS_DIRECTORY + '/catblocks',
        src: '**',
        dest: PUBLIC_DIRECTORY + '/catblocks/'
      },
      clipboard_js: {
        expand: true,
        cwd: 'node_modules/clipboard/dist/',
        src: 'clipboard.min.js',
        dest: PUBLIC_DIRECTORY + '/js/localPlugins/'
      },
      bootstrap_js: {
        src: 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        dest: PUBLIC_DIRECTORY + '/compiled/bootstrap/bootstrap.min.js'
      },
      popper_js: {
        src: 'node_modules/popper.js/dist/popper.js',
        dest: PUBLIC_DIRECTORY + '/compiled/popper/popper.js'
      },
      jquery_ui_js: {
        src: 'node_modules/popper.js/dist/popper.js',
        dest: PUBLIC_DIRECTORY + '/compiled/popper/popper.js'
      }
    },
    concat: {
      options: {
        separator: ';',
        banner: '/*\n  Generated File by Grunt\n  Sourcepath: assets/js\n*/\n'
      },
      base: {
        src: jsBaseSrc,
        dest: PUBLIC_DIRECTORY + '/compiled/js/<%= pkg.baseJSName %>.js'
      },
      register: {
        src: jsRegisterSrc,
        dest: PUBLIC_DIRECTORY + '/compiled/js/<%= pkg.registerJSName %>.js'
      },
      localPlugins: {
        expand: true,
        cwd: jsLocalPluginSrc,
        src: '**/*.js',
        dest: PUBLIC_DIRECTORY + '/compiled/js/'
      },
      custom: {
        expand: true,
        cwd: jsCustomSrc,
        src: '**/*.js',
        dest: PUBLIC_DIRECTORY + '/compiled/js/'
      },
      analytics: {
        expand: true,
        cwd: jsAnalyticsSrc,
        src: '**/*.js',
        dest: PUBLIC_DIRECTORY + '/compiled/js/'
      },
      jquery: {
        expand: true,
        cwd: 'node_modules/jquery/dist',
        src: 'jquery.min.js',
        dest: PUBLIC_DIRECTORY + '/compiled/bootstrap/'
      },
      css: {
        expand: true,
        cwd: ''
      }
    },
    uglify: {
      options: {
        mangle: false
      },
      compiledFiles: {
        files: [
          {
            expand: true,
            cwd: PUBLIC_DIRECTORY + '/compiled/js',
            src: '**/*.js',
            dest: PUBLIC_DIRECTORY + '/compiled/min'
          }
        ]
      }
    },
    sass: SCSS_CONFIG,
    watch: {
      options: {
        nospawn: true
      },
      styles: {
        files: [PUBLIC_DIRECTORY + '/css/**/*.scss'],
        tasks: ['sass'],
        options: {
          nospawn: true
        }
      },
      scripts: {
        files: [PUBLIC_DIRECTORY + '/js/**/*.js'],
        tasks: ['concat', 'uglify'],
        options: {
          nospawn: true
        }
      }
    }
  })
  grunt.loadNpmTasks('grunt-contrib-copy')
  grunt.loadNpmTasks('grunt-contrib-concat')
  grunt.loadNpmTasks('grunt-contrib-uglify-es')
  grunt.loadNpmTasks('grunt-contrib-sass')
  grunt.registerTask('default', ['copy', 'concat', 'sass', 'uglify'])
}
