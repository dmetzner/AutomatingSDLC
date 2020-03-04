// --------------------------------------------------------------------------------------------------
// JS & (S)CSS should always be placed in the assets directory.
// Grunt will tweak the code to our preferences and put it into the assets directory.
//
const ASSETS_DIRECTORY = 'assets'
const PUBLIC_DIRECTORY = 'public'


// -------------------------------------------------------------------------------------------------
// SASS to CSS task:
//
//   - loading all supported themes from the liip config
//   - creating an entry for every theme
//
const SASS_CONFIG = {}

const THEMES = loadThemesFromSymfonyParameters()

THEMES.forEach(function(theme) {
  addThemeConfig(SASS_CONFIG, theme)
})

function loadThemesFromSymfonyParameters()
{
  // Requiring all necessary Modules
  const yaml = require('js-yaml')
  const fs = require('fs')
  
  // load the yaml file
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

function addThemeConfig(SASS_CONFIG, theme)
{
  // all css files should be available for every theme
  const PUBLIC_CSS_BASE_FILE_PATH = PUBLIC_DIRECTORY + '/css/' + theme + '/base.css'
  const THEME_CONFIG = {}
  THEME_CONFIG[PUBLIC_CSS_BASE_FILE_PATH] = [ASSETS_DIRECTORY + '/css/themes/' + theme + '/' + theme + '.scss']
  SASS_CONFIG[theme] =
    {
      options: {
        loadPath : [ASSETS_DIRECTORY + '/css/base', ASSETS_DIRECTORY + '/css/themes/' + theme],
        style    : 'compressed',
        sourcemap: 'none'
      },
      files  : [
        THEME_CONFIG,
        {
          expand: true,
          cwd   : ASSETS_DIRECTORY + '/css/custom/',
          src   : ['**/*.scss'],
          dest  : PUBLIC_DIRECTORY + '/css/' + theme + '/',
          ext   : '.css',
          extDot: 'first'
        }
      ]
    }
}


// -------------------------------------------------------------------------------------------------
// Watch task:
//
//   When grunt detects any of the files specified have changed it will run the specified tasks
//
const WATCH_CONFIG =
{
  options: {
    nospawn: true
  },
  styles: {
    files: [ASSETS_DIRECTORY + '/css/**/*.scss'],
      tasks: ['sass'],
      options: {
      nospawn: true
    }
  },
  scripts: {
    files: [ASSETS_DIRECTORY + '/js/**/*.js'],
      tasks: ['concat', 'uglify'],
      options: {
      nospawn: true
    }
  }
}


// -------------------------------------------------------------------------------------------------
// Uglify task:
//
//   Creating minimized JS files for files that aren't already minimized using grunt uglify
//
const UGLIFY_CONFIG =
{
  options: {
    mangle: false
  },
  compiledFiles: {
    files: [
      {
        expand: true,
        src: [PUBLIC_DIRECTORY + '/js/**/*.js', '!' + PUBLIC_DIRECTORY + '/js/**/*.min.js'],
        dest: PUBLIC_DIRECTORY + '/js',
        rename: function (dst, src) {
          return src.replace('.js', '.min.js');
        }
      }
    ]
  }
}


// -------------------------------------------------------------------------------------------------
// Concat task:
//
//   - ToDo explain
//
const CONCAT_CONFIG =
{
  //options: {
  //  separator: ';',
  //    banner: '/*\n  Generated File by Grunt\n  Source path: assets/js\n*/\n'
  //},
  base: {
    src: [ASSETS_DIRECTORY + '/js/base/*.js'],
    dest: PUBLIC_DIRECTORY + '/js/base.js'
  },
  register: {
    src: ASSETS_DIRECTORY + '/js/register/*.js',
    dest: PUBLIC_DIRECTORY + '/js/register.js'
  },
}

// -------------------------------------------------------------------------------------------------
// Copy task:
//
//   - ToDo explain
//
const COPY_CONFIG =
{
  bootstrap: {
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
      cwd: PUBLIC_DIRECTORY + '/font_awesome_wrapper/fontawesome-free/webfonts/',
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
    src: 'node_modules/clipboard/dist/clipboard.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/clipboard.min.js'
  },
  bootstrap_js: {
    src: 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/bootstrap.min.js'
  },
  sweetalert_all: {
    src: 'node_modules/sweetalert2/dist/sweetalert2.all.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/sweetalert2.all.min.js'
  },
  
  popper_js: {   // not used
    src: 'node_modules/popper.js/dist/popper.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/popper.min.js'
  },
  jquery: {
    src: 'node_modules/jquery/dist/jquery.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/jquery.min.js'
  },
  textfill_js: {
    src: 'node_modules/textfilljs/dist/textfill.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/textfill.min.js'
  },
  jquery_ui: {
    src: 'node_modules/jquery-ui-dist/jquery-ui.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/jquery-ui.min.js'
  },
  jquery_contextmenu_js: {
    src: 'node_modules/jquery-contextmenu/dist/jquery.contextMenu.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/jquery.contextMenu.min.js'
  },
  jquery_contextmenu_css: {
    src: 'node_modules/jquery-contextmenu/dist/jquery.contextMenu.min.css',
    dest: PUBLIC_DIRECTORY + '/css/libraries/jquery.contextMenu.min.css'
  },
  jquery_ui_position: {
    src: 'node_modules/jquery-contextmenu/dist/jquery.ui.position.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/jquery.ui.position.min.js'
  },
  vis_js: { // @deprecated
    src: 'node_modules/vis/dist/vis.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/vis.min.js'
  },
  vis_css: { // @deprecated
    src: 'node_modules/vis/dist/vis.min.css',
    dest: PUBLIC_DIRECTORY + '/css/libraries/vis.min.css'
  },
  animatedModal_js: {
    src: 'node_modules/animatedmodal/animatedModal.min.js',
    dest: PUBLIC_DIRECTORY + '/js/libraries/animatedModal.min.js'
  },
  
  custom: {
    expand: true,
    cwd: ASSETS_DIRECTORY + '/js/custom',
    src: '**/*.js',
    dest: PUBLIC_DIRECTORY + '/js/'
  },
  
  analytics: {
    expand: true,
    cwd: ASSETS_DIRECTORY + '/js/analytics',
    src: '**/*.js',
    dest: PUBLIC_DIRECTORY + '/js/'
  },
}

// -------------------------------------------------------------------------------------------------
// Register all grunt tasks here:
//
module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    copy: COPY_CONFIG,
    concat: CONCAT_CONFIG,
    uglify: UGLIFY_CONFIG,
    sass: SASS_CONFIG,
    watch: WATCH_CONFIG
  })
  grunt.loadNpmTasks('grunt-contrib-copy')
  grunt.loadNpmTasks('grunt-contrib-concat')
  grunt.loadNpmTasks('grunt-contrib-uglify-es')
  grunt.loadNpmTasks('grunt-contrib-sass')
  grunt.registerTask('default', ['copy', 'concat', 'sass', 'uglify'])
}
