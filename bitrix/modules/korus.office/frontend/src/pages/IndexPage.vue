<script lang="ts" setup>
import { computed } from 'vue'
import type { Component } from 'vue'
import { storeToRefs } from 'pinia'
import { useQuasar } from 'quasar'
import { useRootStore } from 'stores/root'
import { useLegacy } from '@/composables/legacy'
import { useWidgets, type IPageWidget } from '@/composables/index-page'
import { useOldWidgets } from '@/composables/old/index-page'

const $q = useQuasar()
const { isAppLoading } = storeToRefs(useRootStore())
const { leftColumnWidgets, rightColumnWidgets, mobileColumnWidgets } = useLegacy().isLegacyMode.value ? useOldWidgets() : useWidgets()

const isMobile = computed(() => $q.screen.lt.sm)
const isTablet = computed(() => $q.screen.lt.lg)

const getComponentForRender = (widget: IPageWidget): Component => {
  if (isAppLoading.value) {
    return widget.componentSkeleton
  }

  return widget.component
}
const leftColumn = computed(() => {
  if (isAppLoading.value) {
    return leftColumnWidgets.value.filter(w => w.isSkeletonVisible)
  }

  return leftColumnWidgets.value.filter(w => w.isVisible)
})
const rightColumn = computed(() => {
  if (isAppLoading.value) {
    return rightColumnWidgets.value.filter(w => w.isSkeletonVisible)
  }

  return rightColumnWidgets.value.filter(w => w.isVisible)
})
</script>

<template>
  <q-page class="kt-page row items-start" :class="[isTablet ? 'q-col-gutter-x-md' : 'q-col-gutter-x-lg']">
    <div v-if="isMobile" class="col-12">
      <div class="row q-col-gutter-y-md">
        <div v-for="widget in mobileColumnWidgets" :key="widget.name" class="col-12">
          <component :is="getComponentForRender(widget)" />
        </div>
      </div>
    </div>

    <template v-else>
      <div class="col-xs-12 col-sm-5 col-lg-4 col-xxl-3 kt-page__left-column">
        <div class="row" :class="[isTablet ? 'q-col-gutter-y-md' : 'q-col-gutter-y-lg']">
          <div v-for="widget in leftColumn" :key="widget.name" class="col-12">
            <component :is="getComponentForRender(widget)" />
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-7 col-lg-8 col-xxl-9 kt-page__right-column">
        <div class="row" :class="[isTablet ? 'q-col-gutter-y-md' : 'q-col-gutter-y-lg']">
          <div v-for="widget in rightColumn" :key="widget.name" class="col-12">
            <component :is="getComponentForRender(widget)" />
          </div>
        </div>
      </div>
    </template>
  </q-page>
</template>

<style lang="scss">
.kt-page {
  &__left-column {
    order: 2;
    padding-top: var(--kt-ui-offset-xl, 24px);

    @media screen and (min-width: 768px) {
      order: revert;
      padding-top: 0;
    }
  }
}
</style>
