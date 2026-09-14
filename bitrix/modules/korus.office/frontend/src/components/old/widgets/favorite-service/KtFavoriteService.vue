<template>
  <kt-widget-layout title=""
                    :background="backgroundColor"
                    :separator="false"
                    theme="light"
                    padding-right="20px"
                    padding-left="20px"
                    actions-align="center"
                    class="kt-favorite-service"
  >
    <a v-for="button in props.buttons"
      :key="button.URL"
      :href="button.URL"
      class="kt-favorite-service__icon-wrapper"
      :style="{ 'background-color': props.color }">
      <q-img v-if="props.image" :src="props.image" class="kt-favorite-service__icon"/>
    </a>

    <h2 class="kt-favorite-service__title">{{ props.title }}</h2>

    <q-separator class="kt-favorite-service__separator" />

    <template #footer>
      <kt-button v-for="button in props.buttons"
                 :key="button.URL"
                 :label="button.LABEL"
                 :href="button.URL"
                 :icon="button.ICON"
                 :dropdown="button.OPTIONS"
                 theme="primary"
                 class="kt-service__btn"
      >
        <q-list v-if="button.OPTIONS">
          <q-item v-for="option in button.OPTIONS"
                  :key="option.LABEL"
                  :href="option.HREF"
                  clickable
                  v-close-popup
          >
            <q-item-section>
              <q-item-label>{{ option.LABEL }}</q-item-label>
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
  URL:      string;
  OPTIONS?: {
    LABEL: string;
    HREF: string;
  }[]
}

interface IKtFavoritesWidgetProps {
  title:   string;
  details: string;
  image:   string;
  color:   string;
  buttons: IServiceButton[];
}

const props = defineProps<IKtFavoritesWidgetProps>()

const { getPaletteColor, changeAlpha } = colors
const backgroundColor = 'var(--kt-service-widget-bg)'
</script>

<style lang="scss">
.pgk {
  .kt-favorite-service {
    $color: var(--q-white);

    color: $color;

    .kt-widget-layout__header {
      display: none;
    }

    .kt-widget-layout__content {
      $margin-bottom: 0;
      $padding-top: 0;
      $margin-top: 25px;

      flex-direction: column;
      flex-grow: 1;
      margin-bottom: $margin-bottom;
      padding-top: $padding-top;
      margin-top: $margin-top;
    }

    .kt-widget-layout__footer {
      $padding-top: 20px;

      padding-top: $padding-top;
    }

    &__icon-wrapper {
      $size: 70px;
      $margin: 0 auto;

      width: $size;
      height: $size;
      margin: $margin;
      position: relative;
      border-radius: 50%;
    }

    &__icon {
      $max-width: 50%;
      $top: 50%;
      $left: 50%;
      $transform: translate(-50%, -50%);

      max-width: $max-width;
      position: absolute;
      top: $top;
      left: $left;
      transform: $transform;
    }

    &__title {
      $width: 100%;
      $margin-top: 12px;
      $font-weight: 400;
      $font-size: 1rem;
      $line-height: 1.375rem;

      width: $width;
      margin-top: $margin-top;
      font-weight: $font-weight;
      font-size: $font-size;
      line-height: $line-height;
      text-align: center;
    }

    &__separator {
      $width: 100%;
      $margin-top: auto;
      $background-color: rgba($app-grey-15, 0.2);

      width: $width;
      margin-top: $margin-top;
      background-color: $background-color;
    }

    .q-img__image {
      height: auto;
      width: auto;
    }

    .kt-widget-layout__footer .q-icon:not(.q-btn-dropdown__arrow) {
      $font-size: 18px;

      font-size: $font-size;
    }
  }
}
</style>
