\<template>
  <component :is="props.dropdown ? QBtnDropdown : QBtn"
             :type="props.type"
             :outline="props.outline"
             :href="props.href"
             :target="props.target"
             :icon="icon"
             :dropdown-icon="props.dropdown ? 'kt:dropdown' : ''"
             :label="props.label"
             :disable="props.disable"
             :loading="props.loading"
             :round="props.round"
             :size="props.size"
             rounded
             unelevated
             class="kt-button"
             :class="`kt-button--${props.theme}-theme`"
             @click="$emit('click', $event)"
  >
    <slot></slot>
  </component>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { QBtn, QBtnDropdown, colors } from 'quasar'

interface IKtButtonProps {
  type?:       'button' | 'a' | 'submit' | 'reset';
  theme?:      'default' | 'primary' | 'info' | 'warn' | 'danger' | 'text';
  dropdown?:   boolean;
  outline?:    boolean;
  icon?:       string;
  href?:       string;
  target?:     string;
  label?:      string;
  color?:      string;
  colorHover?: string;
  disable?:    boolean;
  loading?:    boolean;
  round?:      boolean;
  size?:       string;
}

const props = withDefaults(defineProps<IKtButtonProps>(), {
  type: 'button',
  theme: 'default',
  dropdown: false,
  outline: false,
  color: 'app-grey-5',
  colorHover: 'secondary',
  disable: false,
  loading: false,
  round: false
})
defineEmits(['click'])

const icon = computed(() => (typeof props.icon === 'string' && props.icon !== '') ? props.icon : undefined)

const textBtnColor = computed(() => colors.getPaletteColor(props.color))
const textBtnColorHover = computed(() => colors.getPaletteColor(props.colorHover))
</script>

<style lang="scss">
.pgk {
  .kt-button {
    $min-height: 28px;
    $padding: 6px 18px;
    $font-size: 10px;
    $line-height: 14px;
    $font-weight: 700;

    min-height: $min-height;
    padding: $padding;
    font-size: $font-size;
    line-height: $line-height;
    font-weight: $font-weight;

    @media screen and (min-width: $breakpoint-xl) {
      $font-size: 12px;
      $line-height: 16px;

      font-size: $font-size;
      line-height: $line-height;
    }

    &.q-btn--round {
      $padding: 0;

      padding: $padding;
    }

    .kt-button + .kt-button {
      $margin-left: 10px;

      margin-left: $margin-left;
    }

    &.q-btn-dropdown .q-btn-dropdown__arrow {
      $font-size: 8px;

      font-size: $font-size;
    }

    &--default-theme {
      $color: var(--q-app-grey-4);

      color: $color;

      &::before {
        $color: var(--q-app-grey-8);

        color: $color;
      }
    }

    &--primary-theme {
      $color: var(--q-white);
      $background-color: var(--q-primary);

      color: $color;
      background-color: $background-color;

      &::before {
        $color: var(--q-primary);

        color: $color;
      }
      &:hover {
        color: var(--q-white);
      }
    }

    &--info-theme {
      $color: var(--q-app-blue-2);
      $background-color: transparent;

      color: $color;
      background-color: $background-color;

      &::before {
        color: $color;
      }
    }

    &--warn-theme {
      $color: var(--q-warning);
      $background-color: transparent;

      color: $color;
      background-color: $background-color;

      &::before {
        color: $color;
      }
    }

    &--danger-theme {
      $color: var(--q-secondary);
      $background-color: transparent;

      color: $color;
      background-color: $background-color;

      &::before {
        color: $color;
      }
    }

    &--text-theme {
      $min-height: auto;
      $padding: 0;
      $font-size: 12px;
      $font-weight: 400;
      $line-height: 19px;
      $border: none;
      $color: v-bind('textBtnColor');
      $background-color: transparent;
      $transition: color 0.3s ease-in-out;

      min-height: $min-height;
      padding: $padding;
      text-transform: initial;
      font-size: $font-size;
      font-weight: $font-weight;
      line-height: $line-height;
      border: $border;
      color: $color;
      background-color: $background-color;
      transition: $transition;
      cursor: pointer;

      @media screen and (min-width: $breakpoint-lg) {
        $font-size: 15px;
        $line-height: 20px;

        font-size: $font-size;
        line-height: $line-height;
      }

      &:focus,
      &:hover,
      body.desktop &:focus > .q-focus-helper,
      body.desktop &:hover > .q-focus-helper {
        $color: v-bind('textBtnColorHover');
        $background-color: unset;

        color: $color;
        background-color: $background-color;
      }
    }
  }
}
</style>
