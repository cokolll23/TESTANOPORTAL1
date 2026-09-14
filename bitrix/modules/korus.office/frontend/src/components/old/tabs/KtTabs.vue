<template>
  <div class="kt-tabs-wrapper">
    <q-tabs :model-value="props.modelValue"
            inline-label
            mobile-arrows
            class="kt-tabs"
            :class="layoutClasses"
            @update:model-value="$emit('update:modelValue', $event)"
    >
      <q-tab v-for="tab in props.tabs"
             :key="tab.name"
             :name="tab.name"
             :label="tab.label"
             class="kt-tab"
             content-class="kt-tab__content"
      >
        <q-badge v-if="typeof tab.count !== 'undefined'" color="warning" rounded class="kt-tab__badge">
          {{ tab.count }}
        </q-badge>
      </q-tab>
    </q-tabs>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { ITheme, IKtTab } from '@/models'

interface IKtTabsProps {
  modelValue: string;
  tabs:       Array<IKtTab>;
  theme?:     ITheme;
}

const props = withDefaults(defineProps<IKtTabsProps>(), {
  theme: 'dark'
})
defineEmits(['update:modelValue'])

const layoutClasses = computed(() => ([
  `kt-tabs--${props.theme}-theme`
]))
</script>

<style lang="scss">
.pgk {
  .kt-tabs {
    $margin-bottom: 20px;
    $padding: 10px;
    $border-radius: 100px;

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

  .kt-tabs--light-theme {
    $border-grey: rgba($app-grey-15, 0.5);

    border: 1px solid $border-grey;
  }

  .kt-tabs--dark-theme {
    $border-grey: var(--q-app-grey-15);

    border: 1px solid $border-grey;
  }

  .kt-tab {
    $min-height: 28px;
    $padding: 0 18px;
    $border-radius: 100px;

    min-height: $min-height;
    padding: $padding;
    border-radius: $border-radius;
  }

  .kt-tab + .kt-tab {
    $margin-left: 10px;

    margin-left: $margin-left;
  }

  .kt-tabs--light-theme .kt-tab {
    $color: var(--q-white);

    color: $color;
  }

  .kt-tabs--light-theme .kt-tab.q-tab--active .kt-tab__content .q-tab__label {
    $color: var(--q-primary);

    color: $color;
  }

  .kt-tabs--dark-theme .kt-tab.q-tab--active .kt-tab__content .q-tab__label {
    $color: var(--q-white);

    color: $color;
  }

  .kt-tabs--dark-theme .kt-tab {
    $color: var(--q-primary);

    color: $color;
  }

  .kt-tab {
    .q-tab__indicator {
      $height: 100%;
      $border-radius: 100px;

      height: $height;
      border-radius: $border-radius;
    }

    &__content {
      $z-index: 10;

      z-index: $z-index;
    }

    &__content .q-tab__label {
      $font-family: 'OpenSans', sans-serif;
      $font-weight: 700;
      $font-size: 9px;
      $line-height: 12px;

      font-size: $font-size;
      line-height: $line-height;
      font-family: $font-family;
      font-weight: $font-weight;

      @media screen and (min-width: $breakpoint-lg) {
        $font-size: 12px;
        $line-height: 16px;

        font-size: $font-size;
        line-height: $line-height;
      }
    }

    &__badge {
      $margin-left: 5px;
      $padding: 4px 7px;

      margin-left: $margin-left;
      padding: $padding;
    }
  }
}
</style>
