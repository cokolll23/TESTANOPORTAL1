<template>
  <div class="kt-widget-layout" :class="layoutClasses" :style="layoutStyles">
    <header v-if="props.header" class="kt-widget-layout__header">
      <kt-widget-layout-title :title="props.title"
                              :title-color="props.titleColor"
                              :icon="props.icon"
                              :icon-text-color="props.iconTextColor"
                              :icon-color="props.iconColor"
                              :icon-size="props.iconSize"
      >
        <template v-if="$slots['title-suffix']" #title-suffix>
          <slot name="title-suffix"></slot>
        </template>
      </kt-widget-layout-title>

      <div v-if="$slots['header-bottom']" class="kt-widget-layout__header-bottom">
        <slot name="header-bottom"></slot>
      </div>
    </header>

    <q-separator v-if="props.separator"
                 :style="separatorStyles"
                 class="kt-widget-layout__separator"
    />

    <div class="kt-widget-layout__content">
      <slot></slot>
    </div>

    <footer v-if="$slots.footer" class="kt-widget-layout__footer" :class="footerClasses">
      <slot name="footer"></slot>
    </footer>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { KtWidgetLayoutTitle } from 'components/old/widget-layout-title'
import { ITheme } from '@/models'

interface IKtWidgetLayoutProps {
  header?:         boolean;
  title?:          string;
  titleColor?:     string;
  theme?:          ITheme;
  background?:     string;

  icon?:           string;
  iconTextColor?:  string;
  iconColor?:      string;
  iconSize?:       string;

  separator?:      boolean;
  separatorColor?: string;

  paddingTop?:     string;
  paddingRight?:   string;
  paddingBottom?:  string;
  paddingLeft?:    string;

  actionsAlign?:   'center' | 'left' | 'right' | 'between' | 'around' | 'evenly' | 'stretch'
}

const props = withDefaults(defineProps<IKtWidgetLayoutProps>(), {
  header:        true,
  theme:         'dark',
  background:    'white',

  separator:     true,

  paddingTop:    '20px',
  paddingRight:  '30px',
  paddingBottom: '20px',
  paddingLeft:   '30px',

  actionsAlign:  'left'
})

const layoutClasses = computed(() => ([
  `kt-widget-layout--${props.theme}-theme`
]))
const layoutStyles = computed(() => ({
  backgroundColor: props.background,

  paddingTop:      props.paddingTop,
  paddingRight:    props.paddingRight,
  paddingBottom:   props.paddingBottom,
  paddingLeft:     props.paddingLeft
}))
const separatorStyles = computed(() => ({
  'background-color': props.separatorColor
}))
const footerClasses = computed(() => ([
  `justify-${props.actionsAlign}`
]))
</script>

<style lang="scss">
.pgk {
  .kt-widget-layout {
    $widget: &;
    $font-size: 12px;
    $line-height: 18px;
    $border-radius: 10px;
    $box-shadow: none;

    display: flex;
    flex-direction: column;
    position: relative;
    font-size: $font-size;
    line-height: $line-height;
    border-radius: $border-radius;
    box-shadow: $box-shadow;
    overflow: hidden;

    @media screen and (min-width: $breakpoint-lg) {
      $font-size: 15px;
      $line-height: 20px;

      font-size: $font-size;
      line-height: $line-height;
    }

    &__header {
      $padding-bottom: 20px;

      flex-wrap: wrap;
      align-items: center;
      padding-bottom: $padding-bottom;

      .kt-widget-layout-title__prefix .kt-icon-wrapper {
        $margin-right: 12px;

        margin-right: $margin-right;
      }

      &-bottom {
        $width: 100%;
        $margin-top: 6px;

        width: $width;
        margin-top: $margin-top;
        display: block;
      }
    }

    &__separator{
      margin-bottom: 20px;
    }

    &__content {
      $margin-bottom: auto;

      display: flex;
      flex-wrap: wrap;
      margin-bottom: $margin-bottom;

      .q-table__card {
        $max-width: 100%;
        $width: 100%;
        $box-shadow: none;

        max-width: $max-width;
        width: $max-width;
        box-shadow: $box-shadow;
      }
    }

    &__footer {
      $margin: -10px 0 0 -10px;
      $padding: 30px 0 0 0;

      display: flex;
      flex-wrap: wrap;
      margin: $margin;
      padding: $padding;

      .q-btn {
        $margin: 10px 0 0 10px;
        $padding: 6px 12px;

        margin: $margin;
        padding: $padding;

        @media screen and (min-width: $breakpoint-lg) {
          $padding: 6px 18px;

          padding: $padding;
        }

        @media screen and (max-width: $breakpoint-sm) {
          $font-size: 9px;

          font-size: $font-size;
        }

        .q-icon:not(.q-btn-dropdown__arrow) {
          $margin-right: 6px;
          $font-size: 16px;

          margin-right: $margin-right;
          font-size: $font-size;
        }

        .q-icon.q-btn-dropdown__arrow {
          $margin-left: 6px;
          $font-size: 8px;

          margin-left: $margin-left;
          font-size: $font-size;
        }
      }
    }
  }
}
</style>
