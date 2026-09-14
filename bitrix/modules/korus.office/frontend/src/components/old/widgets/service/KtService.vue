<template>
  <kt-widget-layout :title="props.title"
                    :separator-color="separatorColor"
                    :background="backgroundColor"
                    theme="light"
                    :icon="props.image"
                    :icon-color="props.color"
                    padding-right="20px"
                    padding-left="20px"
                    class="kt-service"
  >
    <template #title-suffix v-if="props.titleSuffix">{{ props.titleSuffix }}</template>

    <template #default>
      <div class="kt-service__content" v-html="props.details"></div>
    </template>

    <template #footer>
      <kt-button v-for="button in props.buttons"
                 :key="button.URL"
                 :label="button.LABEL"
                 :href="button.URL"
                 :icon="button.ICON"
                 :dropdown="button.OPTIONS"
                 theme="primary"
                 class="kt-service__btn"
                 target="_blank"
      >
        <q-list v-if="button.OPTIONS">
          <q-item v-for="option in button.OPTIONS"
                  :key="option.LABEL"
                  :href="option.HREF"
                  :target="option.TARGET"
                  clickable
                  v-close-popup
          >
            <q-item-section>
              <q-item-label>{{ option.LABEL.toUpperCase() }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </kt-button>
    </template>
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { colors } from 'quasar'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtButton } from 'components/old/button'

interface IServiceButton {
  LABEL:    string;
  ICON:     string;
  URL?:      string;
  OPTIONS?: {
    LABEL: string;
    HREF: string;
    TARGET: string;
  }[]
}

interface IProps {
  title:   string;
  image:   string;
  details: string;
  color:   string;
  buttons: IServiceButton[];
  titleSuffix: string;
}

const props = defineProps<IProps>()

const { getPaletteColor, changeAlpha } = colors
const separatorColor  = changeAlpha(getPaletteColor('app-grey-15'), 0.2)
const backgroundColor = 'var(--kt-service-widget-bg, rgba(18, 18, 57, 0.7))'
</script>

<style lang="scss">
.pgk {
  .kt-service {
    &__content {
      $width: 100%;
      $maxHeight: 140px;

      width: $width;
      max-height: $maxHeight;
      padding-right: 10px;
      overflow-y: auto;
      scrollbar-color: #A5ADB3 #F5F5F5;
      scrollbar-width: thin;

      &::-webkit-scrollbar {
        width: 0.3em;
      }
      &::-webkit-scrollbar-track {
        background-color: #F5F5F5;
        box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
      }
      &::-webkit-scrollbar-thumb {
        background-color: darkgrey;
        outline: 1px solid slategrey;
        border-radius: 4px;
      }
    }

    .kt-button {
      @media screen and (min-width: $breakpoint-sm) and (max-width: $breakpoint-lg) {
        $font-size: 9px;
        $line-height: 12px;

        font-size: $font-size;
        line-height: $line-height;
      }
    }
  }
}
</style>
