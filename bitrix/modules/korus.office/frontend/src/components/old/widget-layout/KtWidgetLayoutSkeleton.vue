<template>
  <div class="kt-widget-layout-skeleton" :class="layoutClasses" :style="layoutStyles">
    <header class="kt-widget-layout-skeleton__header">
      <kt-widget-layout-title-skeleton :title="props.title" title-width="40%" :icon="props.icon">
        <template v-if="$slots['title-suffix']" #title-suffix>
          <slot name="title-suffix"></slot>
        </template>
      </kt-widget-layout-title-skeleton>

      <div v-if="$slots['header-bottom']" class="kt-widget-layout-skeleton__header-bottom">
        <slot name="header-bottom"></slot>
      </div>
    </header>

    <q-separator v-if="props.separator"
                 :style="separatorStyles"
                 class="kt-widget-layout-skeleton__separator"
    />

    <div class="kt-widget-layout-skeleton__content">
      <slot></slot>
    </div>

    <footer v-if="$slots.footer" class="kt-widget-layout-skeleton__footer" :class="footerClasses">
      <slot name="footer"></slot>
    </footer>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { KtWidgetLayoutTitleSkeleton } from 'components/old/widget-layout-title'
import { ITheme } from '@/models'

interface IKtWidgetLayoutProps {
  title?:          string;
  titleColor?:     string;
  theme?:          ITheme;
  background?:     string;

  icon?:           string;

  separator?:      boolean;
  separatorColor?: string;

  paddingTop?:     string;
  paddingRight?:   string;
  paddingBottom?:  string;
  paddingLeft?:    string;

  actionsAlign?:   'center' | 'left' | 'right' | 'between' | 'around' | 'evenly' | 'stretch'
}

const props = withDefaults(defineProps<IKtWidgetLayoutProps>(), {
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
  `kt-widget-layout-skeleton--${props.theme}-theme`
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
  .kt-widget-layout-skeleton {
    $widget: &;
    $border-radius: 10px;

    display: flex;
    flex-direction: column;
    position: relative;
    border-radius: $border-radius;
    overflow: hidden;

    &__header {
      $padding-bottom: 20px;

      flex-wrap: wrap;
      align-items: center;
      padding-bottom: $padding-bottom;

      .kt-icon-skeleton-wrapper {
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

    &__content {
      $margin-bottom: auto;
      $padding-top: 20px;
      $padding-top: 20px;

      display: flex;
      flex-wrap: wrap;
      margin-bottom: $margin-bottom;
      padding-top: $padding-top;

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

      .q-skeleton--type-QBtn {
        $margin: 10px 0 0 10px;

        margin: $margin;
      }
    }
  }
}
</style>
