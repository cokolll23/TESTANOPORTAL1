<template>
  <div class="kt-tabs-skeleton-wrapper">
    <div class="kt-tabs-skeleton" :class="`kt-tabs-skeleton--${props.theme}-theme`">
      <q-skeleton v-for="tab in props.count"
                  :key="tab"
                  type="rect"
                  width="100px"
                  height="28px"
                  class="kt-tab-skeleton"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ITheme } from '@/models'

interface IProps {
  theme?: ITheme;
  count?: number;
}

const props = withDefaults(defineProps<IProps>(), {
  theme: 'dark',
  count: 2
})
</script>

<style lang="scss">
.pgk {
  .kt-tabs-skeleton {
    $margin-bottom: 20px;
    $padding: 10px;
    $border-radius: 100px;

    display: flex;
    align-items: center;
    margin-bottom: $margin-bottom;
    padding: $padding;
    border-radius: $border-radius;

    &-wrapper {
      $max-width: 100%;

      max-width: $max-width;
      overflow-x: auto;
      overflow-y: hidden;
    }
  }

  .kt-tabs-skeleton--light-theme {
    $border-grey: rgba($app-grey-15, 0.5);

    border: 1px solid $border-grey;
  }

  .kt-tabs-skeleton--dark-theme {
    $border-grey: var(--q-app-grey-15);

    border: 1px solid $border-grey;
  }

  .kt-tab-skeleton {
    $min-height: 28px;
    $padding: 0 18px;
    $border-radius: 100px;

    min-height: $min-height;
    padding: $padding;
    border-radius: $border-radius;
  }

  .kt-tab-skeleton + .kt-tab-skeleton {
    $margin-left: 10px;

    margin-left: $margin-left;
  }
}
</style>
