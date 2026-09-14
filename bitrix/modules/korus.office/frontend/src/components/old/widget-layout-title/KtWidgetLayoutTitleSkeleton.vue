<template>
  <div class="kt-widget-title-skeleton">
    <div v-if="props.icon">
      <kt-icon-skeleton />
    </div>

    <q-skeleton type="text" :width="props.titleWidth" height="20px" />

    <div v-if="$slots['title-suffix']" class="kt-widget-title-skeleton__suffix">
      <slot name="title-suffix"></slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { KtIconSkeleton } from 'components/old/icon'

interface IProps {
  titleWidth?: string;
  icon?:       string;
}

const props = withDefaults(defineProps<IProps>(), {
  titleWidth: '50%'
})
</script>

<style lang="scss" scoped>
.pgk {
  .kt-widget-title-skeleton {
    $width: 100%;

    width: $width;
    display: flex;
    flex-wrap: wrap;
    align-items: center;

    &__suffix {
      @media screen and (min-width: $breakpoint-sm) {
        $margin-left: auto;

        margin-left: $margin-left;
      }

      @media screen and (max-width: $breakpoint-sm) {
        $width: 100%;
        $margin-top: 5px;

        width: $width;
        margin-top: $margin-top;
      }
    }
  }
}
</style>
