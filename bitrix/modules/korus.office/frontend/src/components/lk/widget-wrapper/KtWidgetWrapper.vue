<template>
  <div class="kt-widget-wrapper">
    <slot></slot>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-widget-wrapper {
  padding: var(--kt-widget-wrapper-padding-mobile);
  border-radius: var(--kt-widget-wrapper-border-radius);
  background-color: var(--kt-widget-wrapper-bg);

  @media screen and (min-width: 992px) {
    padding: var(--kt-widget-wrapper-padding-desktop);
  }
}
</style>
