/* eslint-env node */

/*
 * This file runs in a Node context (it's NOT transpiled by Babel), so use only
 * the ES6 features that are supported by your Node version. https://node.green/
 */

// Configuration for your app
// https://v2.quasar.dev/quasar-cli-vite/quasar-config-js

const { configure } = require('quasar/wrappers')
const path = require('path')
const DotEnv = require('dotenv')

module.exports = configure(function (/* ctx */) {
  return {
    eslint: {
      // fix: true,
      // include = [],
      // exclude = [],
      // rawOptions = {},
      warnings: true,
      errors: true
    },

    // https://v2.quasar.dev/quasar-cli-vite/prefetch-feature
    // preFetch: true,

    // app boot file (/src/boot)
    // --> boot files are part of "main.js"
    // https://v2.quasar.dev/quasar-cli-vite/boot-files
    boot: [
      'i18n',
      'axios'
    ],

    // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#css
    css: [
      'app.scss'
    ],

    // https://github.com/quasarframework/quasar/tree/dev/extras
    extras: [
      // 'ionicons-v4',
      // 'mdi-v5',
      // 'fontawesome-v6',
      // 'eva-icons',
      // 'themify',
      // 'line-awesome',
      // 'roboto-font-latin-ext', // this or either 'roboto-font', NEVER both!

      'roboto-font', // optional, you are not bound to it
      'material-icons' // optional, you are not bound to it
    ],

    // Full list of options: https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#build
    build: {
      target: {
        browser: ['es2019', 'edge88', 'firefox78', 'chrome87', 'safari13.1'],
        node: 'node16'
      },

      vueRouterMode: 'history', // available values: 'hash', 'history'
      vueRouterBase: '/',
      // vueDevtools,
      // vueOptionsAPI: false,

      // rebuildCache: true, // rebuilds Vite/linter/etc cache on startup

      publicPath: '/bitrix/templates/k-team/assets/spa/korus.office/',
      // analyze: true,
      env: DotEnv.config().parsed,
      // rawDefine: {}
      // ignorePublicFolder: true,
      // minify: false,
      // polyfillModulePreload: true,
      distDir: process.env.DIST_DIR,

      // extendViteConf (viteConf) {},
      // viteVuePluginOptions: {},

      vitePlugins: [
        ['@intlify/vite-plugin-vue-i18n', {
          // if you want to use Vue I18n Legacy API, you need to set `compositionOnly: false`
          // compositionOnly: false,

          runtimeOnly: false,

          // you need to set i18n resource including paths !
          include: path.resolve(__dirname, './src/i18n/**')
        }]
      ],

      alias: {
        '@': path.resolve(__dirname, './src'),
        composables: path.resolve(__dirname, './src/composables')
      }
    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#devServer
    devServer: {
      // https: true
      open: true // opens browser window automatically
    },

    // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#framework
    framework: {
      config: {
        brand: {
          primary             : 'var(--kt-ui-color-primary, #121239)',   // rgb(18, 18, 57)
          secondary           : 'var(--kt-ui-color-secondary, #7949f4)', // rgb(121, 73, 244)
          info                : 'var(--kt-ui-color-info, #3385ff)',      // rgb(51, 133, 255)
          warning             : 'var(--kt-ui-color-warning, #ffa726)',   // rgb(255, 167, 38)
          positive            : 'var(--kt-ui-color-success, #7e9f26)',   // rgb(126, 159, 38)
          negative            : 'var(--kt-ui-color-danger, #c10015)',    // rgb(193, 0, 21)
          accent              : 'var(--kt-ui-color-accented, #9c27b0)',  // rgb(156, 39, 176)
          white               : 'var(--kt-ui-white, #ffffff)',           // rgb(255, 255, 255)

          'app-white'         : '#FFFFFF',  // rgb(255, 255, 255)

          'app-grey-1'        : '#181818',  // rgb(24, 24, 24)
          'app-grey-2'        : '#333333',  // rgb(51, 51, 51)
          'app-grey-3'        : '#505050',  // rgb(80, 80, 80)
          'app-grey-4'        : '#525C69',  // rgb(82, 92, 105)
          'app-grey-5'        : '#818B9A',  // rgb(129, 139, 154)
          'app-grey-6'        : '#868D96',  // rgb(134, 141, 150)
          'app-grey-7'        : '#8C8C8C',  // rgb(140, 140, 140)
          'app-grey-8'        : '#A5ADB3',  // rgb(165, 173, 179)
          'app-grey-9'        : '#C1C4C9',  // rgb(193, 196, 201)
          'app-grey-10'       : '#C4C4C4',  // rgb(196, 196, 196)
          'app-grey-11'       : '#C6CDD3',  // rgb(198, 205, 211)
          'app-grey-12'       : '#D9D9D9',  // rgb(217, 217, 217)
          'app-grey-13'       : '#E3E3E3',  // rgb(227, 227, 227)
          'app-grey-14'       : '#E7E7E7',  // rgb(231, 231, 231)
          'app-grey-15'       : '#E9E9E9',  // rgb(233, 233, 233)
          'app-grey-16'       : '#EEF2F4',  // rgb(238, 242, 244)
          'app-grey-17'       : '#F3F4F8',  // rgb(243, 244, 248)
          'app-grey-18'       : '#F5F7F9',  // rgb(245, 247, 249)

          'app-blue-1'        : '#3385FF',
          'app-blue-2'        : '#3D86F3',

          'app-green-1'       : '#7E9F26',
          'app-green-2'       : '#ADC855',
          'app-green-3'       : '#ABCB57',

          'app-orange-1'      : '#FFA726',
          'app-orange-2'      : '#FEAB29',
          'app-orange-3'      : '#FCB64D',

          'app-deep-orange-1' : '#EA5F23'
        }
      },

      // iconSet: 'material-icons', // Quasar icon set
      lang: 'ru', // Quasar language pack

      // For special cases outside of where the auto-import strategy can have an impact
      // (like functional components as one of the examples),
      // you can manually specify Quasar components/directives to be available everywhere:
      //
      // components: [],
      // directives: [],

      // Quasar plugins
      plugins: ['Notify', 'Dialog']
    },

    // animations: 'all', // --- includes all animations
    // https://v2.quasar.dev/options/animations
    animations: [],

    // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#sourcefiles
    // sourceFiles: {
    //   rootComponent: 'src/App.vue',
    //   router: 'src/router/index',
    //   store: 'src/store/index',
    //   registerServiceWorker: 'src-pwa/register-service-worker',
    //   serviceWorker: 'src-pwa/custom-service-worker',
    //   pwaManifestFile: 'src-pwa/manifest.json',
    //   electronMain: 'src-electron/electron-main',
    //   electronPreload: 'src-electron/electron-preload'
    // },

    // https://v2.quasar.dev/quasar-cli-vite/developing-ssr/configuring-ssr
    ssr: {
      // ssrPwaHtmlFilename: 'offline.html', // do NOT use index.html as name!
      // will mess up SSR

      // extendSSRWebserverConf (esbuildConf) {},
      // extendPackageJson (json) {},

      pwa: false,

      // manualStoreHydration: true,
      // manualPostHydrationTrigger: true,

      prodPort: 3000, // The default port that the production server should use
      // (gets superseded if process.env.PORT is specified at runtime)

      middlewares: [
        'render' // keep this as last one
      ]
    },

    // https://v2.quasar.dev/quasar-cli-vite/developing-pwa/configuring-pwa
    pwa: {
      workboxMode: 'generateSW', // or 'injectManifest'
      injectPwaMetaTags: true,
      swFilename: 'sw.js',
      manifestFilename: 'manifest.json',
      useCredentialsForManifestTag: false
      // extendGenerateSWOptions (cfg) {}
      // extendInjectManifestOptions (cfg) {},
      // extendManifestJson (json) {}
      // extendPWACustomSWConf (esbuildConf) {}
    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-vite/developing-cordova-apps/configuring-cordova
    cordova: {
      // noIosLegacyBuildFlag: true, // uncomment only if you know what you are doing
    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-vite/developing-capacitor-apps/configuring-capacitor
    capacitor: {
      hideSplashscreen: true
    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-vite/developing-electron-apps/configuring-electron
    electron: {
      // extendElectronMainConf (esbuildConf)
      // extendElectronPreloadConf (esbuildConf)

      inspectPort: 5858,

      bundler: 'packager', // 'packager' or 'builder'

      packager: {
        // https://github.com/electron-userland/electron-packager/blob/master/docs/api.md#options

        // OS X / Mac App Store
        // appBundleId: '',
        // appCategoryType: '',
        // osxSign: '',
        // protocol: 'myapp://path',

        // Windows only
        // win32metadata: { ... }
      },

      builder: {
        // https://www.electron.build/configuration/configuration

        appId: 'kt-app'
      }
    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-vite/developing-browser-extensions/configuring-bex
    bex: {
      contentScripts: [
        'my-content-script'
      ]

      // extendBexScriptsConf (esbuildConf) {}
      // extendBexManifestJson (json) {}
    }
  }
})
