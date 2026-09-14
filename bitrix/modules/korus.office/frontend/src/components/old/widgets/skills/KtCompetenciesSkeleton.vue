<template>
  <kt-widget-layout-skeleton :title="$t('competencies.title')"
                             padding-bottom="30px"
                             class="kt-competencies-skeleton"
  >
    <div class="kt-competencies-skeleton__items">
      <q-skeleton v-for="skill in 3"
                  :key="skill"
                  type="QBtn"
                  width="30%"
                  class="kt-competencies-skeleton__item"
      />
    </div>
  </kt-widget-layout-skeleton>
</template>

<script lang="ts" setup>
import { KtWidgetLayoutSkeleton } from 'components/old/widget-layout'
</script>

<style lang="scss">
.pgk {
  .kt-competencies-skeleton {
    $margin-top: 10px;
    $margin-left: 10px;

    &__items {
      flex-grow: 1;
      margin-top: $margin-top * -1;
      margin-left: $margin-left * -1;
    }

    &__item {
      display: inline-flex;
      margin-top: $margin-top;
      margin-left: $margin-left;
    }
  }
}
</style>
