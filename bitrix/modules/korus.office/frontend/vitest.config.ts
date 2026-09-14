/// <reference types="vitest" />

import path from 'path'
import { defineConfig } from 'vite'
import Vue from '@vitejs/plugin-vue'
import { quasar, transformAssetUrls } from '@quasar/vite-plugin'

export default defineConfig({
  plugins: [
    Vue({
      template: { transformAssetUrls }
    }),
    quasar({
      sassVariables: true
    })
  ],
  test: {
    globals: true,
    environment: 'jsdom'
  },
  resolve: {
    alias: {
      '@':         path.resolve(__dirname, './src'),
      src:         path.resolve(__dirname, './src'),
      components:  path.resolve(__dirname, './src/components'),
      composables: path.resolve(__dirname, './src/composables'),
      services:    path.resolve(__dirname, './src/services'),
      stores:      path.resolve(__dirname, './src/stores'),
      assets:      path.resolve(__dirname, './src/assets')
    }
  }
})
