<template>
  <div class="kt-widget-layout-title">
    <div v-if="props.icon" class="kt-widget-layout-title__prefix">
      <kt-icon :name="props.icon"
               :color="props.iconTextColor"
               :background-color="props.iconColor"
               :icon-size="props.iconSize"
      />
    </div>

    <h2 v-if="props.title" class="kt-widget-layout-title__text" :class="titleClasses">
      {{ props.title }}
    </h2>

    <div v-if="$slots['title-suffix']" class="kt-widget-layout-title__suffix">
      <slot name="title-suffix"></slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { KtIcon } from 'components/old/icon'

interface IKtWidgetLayoutTitleProps {
  title?:         string;
  titleColor?:    string;
  icon?:          string;
  iconTextColor?: string;
  iconColor?:     string;
  iconSize?:      string;
}

const props = withDefaults(defineProps<IKtWidgetLayoutTitleProps>(), {
  iconTextColor: 'white',
  iconColor: 'primary'
})

const titleClasses = computed(() => ([
  props.titleColor ? `text-${props.titleColor}` : ''
]))
</script>

<style lang="scss">
.pgk {
  .kt-widget-layout-title {
    $width: 100%;

    width: $width;
    display: flex;
    align-items: center;

    &__text {
      $font-family: 'OpenSans', sans-serif;
      $font-size: 16px;
      $line-height: 22px;

      font-family: $font-family;
      font-size: $font-size;
      line-height: $line-height;

      @media screen and (min-width: $breakpoint-lg) {
        $font-size: 20px;
        $line-height: 27px;

        font-size: $font-size;
        line-height: $line-height;
      }
      @media screen and (max-width: $breakpoint-xs) {
        $font-size: 16px;
        $line-height: 20px;

        font-size: $font-size;
        line-height: $line-height;
      }
    }

    &__suffix {
      $margin-left: auto;

      margin-left: $margin-left;
      display: flex;
      align-items: center;
    }
  }

  .kt-widget-layout--light-theme .kt-widget-layout-title {
    $color: var(--q-app-white);

    color: $color;
  }

  .kt-widget-layout--dark-theme .kt-widget-layout-title {
    $color: var(--q-app-grey-4);

    color: $color;
  }
}
</style>
