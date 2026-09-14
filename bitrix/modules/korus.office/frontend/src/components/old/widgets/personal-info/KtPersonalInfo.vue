<template>
  <kt-widget-layout :title="FULL_NAME"
                    title-color="primary"
                    :separator-color="separatorColor"
                    theme="dark"
                    class="kt-personal-info"
  >
    <template #title-suffix>
      <div class="row items-center" id="lk-pagetitle-menu"></div>
    </template>
    <template #header-bottom>
      <kt-widget-layout-text :text="WORK_POSITION" color="app-grey-5" />
    </template>

    <kt-personal-info-read-mode v-if="mode === 'read'"
                                @change-mode="toggleMode"
    />

    <kt-personal-info-edit-mode v-else
                                @change-mode="toggleMode"
    />
  </kt-widget-layout>
</template>

<script lang="ts" setup>
import { onMounted, ref, Ref } from 'vue'
import { colors } from 'quasar'
import { storeToRefs } from 'pinia'
import { usePersonalStore } from 'stores/personal'

import { KtWidgetLayout } from 'components/old/widget-layout'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'
import { KtPersonalInfoReadMode, KtPersonalInfoEditMode } from './'

const { getPaletteColor } = colors
const { FULL_NAME, WORK_POSITION } = storeToRefs(usePersonalStore())
const separatorColor = getPaletteColor('app-grey-15')

const mode: Ref<'read' | 'edit'> = ref('read')
const toggleMode = (value: 'read' | 'edit') => {
  mode.value = value
}

onMounted(() => {
  document.dispatchEvent(new CustomEvent('PersonalInfo:onMounted'))
})
</script>

<style lang="scss">
.pgk {
  .kt-personal-info {
    .kt-widget-layout-title__suffix {
      margin-left: initial;
    }
    .kt-widget-layout__content {
      $padding-top: 35px;

      padding-top: $padding-top;
    }

    .private-toggle-icon {
      position: absolute;
      top: 0;
      right: -20px;
    }
  }
}
</style>
