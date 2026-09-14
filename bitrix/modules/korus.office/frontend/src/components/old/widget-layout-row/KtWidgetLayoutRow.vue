<template>
  <div class="kt-widget-layout-row" :class="layoutClasses">
    <kt-widget-layout-text v-if="props.label"
                           :color="props.labelColor"
                           class="kt-widget-layout-row__label"
    >
      {{ props.label }}
    </kt-widget-layout-text>

    <div class="kt-widget-layout-row__content" :class="props.classList">
      <slot>{{ props.text }}</slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'

interface IKtPersonalInfoRowProps {
  label?:      string;
  labelColor?: string;
  text?:       string;
  href?:       string;
  yAligment?:  'start' | 'center' | 'end';
  classList?: string;
}

const props = withDefaults(defineProps<IKtPersonalInfoRowProps>(), {
  labelColor: 'app-grey-5',
  yAligment: 'start'
})

const layoutClasses = computed(() => ([
  `items-${props.yAligment}`
]))
</script>

<style lang="scss">
.pgk {
  .kt-widget-layout-row {
    $width: 100%;
    $margin-top: -5px;

    width: $width;
    display: flex;
    flex-wrap: wrap;
    margin-top: $margin-top;

    &:not(.q-mb-none) {
      $margin-bottom: 10px;

      margin-bottom: $margin-bottom;
    }

    &__label {
      $margin-top: 5px;

      margin-top: $margin-top;
    }

    &__content {
      $margin-top: 5px;
      $color: var(--q-primary);

      margin-top: $margin-top;
      color: $color;
      flex: 1 1 50%;
      flex-grow: 1.4;

      .kt-link--primary-theme{
        $color: var(--q-secondary);
        color: $color;
      }

      .layout-text-clamp{
        display:inline-block;
      }
      .q-icon{
        vertical-align: baseline;
      }

      @media screen and (max-width: $breakpoint-xs) {
        flex-grow: initial;
      }
    }

    @media screen and (max-width: $breakpoint-xs) {
      justify-content: space-between;
    }
  }
}
</style>
