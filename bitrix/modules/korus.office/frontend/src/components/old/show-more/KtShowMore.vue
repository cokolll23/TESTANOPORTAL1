<template>
  <div class="kt-show-more" :class="layoutClasses">
    <q-expansion-item expand-icon-toggle
                      :model-value="isExpanded"
                      @update:model-value="$emit('update:modelValue', $event)"
    >
      <template #header><slot name="visible"></slot></template>
      <template #default><slot></slot></template>
    </q-expansion-item>

    <template v-if="props.showToggleButton">
      <q-separator class="kt-show-more__separator" />

      <footer class="kt-show-more__footer">
        <button type="button"
                tabindex="0"
                class="kt-show-more__btn"
                @click="toggle"
        >
          {{ expandStateText }} <q-icon name="kt:dropdown" />
        </button>
      </footer>
    </template>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useExpand } from '@/composables/useExpand'

interface IKtShowMoreProps {
  defaultOpened?:    boolean;
  showToggleButton?: boolean;
  expandText?:       string;
  expandedText?:     string;
}

const props = withDefaults(defineProps<IKtShowMoreProps>(), {
  defaultOpened: false,
  showToggleButton: true,
  expandText: 'Показать больше',
  expandedText: 'Свернуть'
})
defineEmits(['update:modelValue'])

const { isExpanded, toggle, expandStateText } = useExpand({
  defaultValue: props.defaultOpened,
  expandText: props.expandText,
  expandedText: props.expandedText
})
const layoutClasses = computed(() => ([
  isExpanded.value ? 'is-expanded' : ''
]))
</script>

<style lang="scss">
.pgk {
  .kt-show-more {
    $width: 100%;

    width: $width;

    .q-expansion-item {
      $padding: 0 0 20px 0;

      padding: $padding;
    }

    .q-item {
      $min-height: initial;
      $padding: 0;

      flex-wrap: wrap;
      min-height: $min-height;
      padding: $padding;

      &__section--side {
        display: none;
      }
    }

    &__separator {
      $width: 100%;
      $margin: 5px 0;
      $background-color: var(--q-app-grey-15);

      width: $width;
      margin: $margin;
      background-color: $background-color;
    }

    &__footer {
      $width: 100%;
      $padding: 10px 0 0 0;

      width: $width;
      padding: $padding;
    }

    &__btn {
      $padding: 0;
      $text-transform: initial;
      $border: none;
      $color: var(--q-secondary);
      $background-color: transparent;
      $transition: color 0.3s ease-in-out;

      padding: $padding;
      text-transform: $text-transform;
      border: $border;
      color: $color;
      background-color: $background-color;
      transition: $transition;
      cursor: pointer;

      &:hover {
        $color: var(--q-primary);

        color: $color;
      }

      body.desktop &:hover > .q-focus-helper,
      body.desktop &:focus > .q-focus-helper {
        $background-color: transparent;

        background-color: $background-color;
      }

      .q-icon {
        $margin-left: 6px;
        $font-size: 8px;
        $transform-origin: 50% 50%;
        $transition: transform 0.3s ease-out;

        margin-left: $margin-left;
        font-size: $font-size;
        transform-origin: $transform-origin;
        transition: $transition;

        .is-expanded & {
          $transform: rotate(180deg);

          transform: $transform;
        }
      }
    }
  }
}
</style>
