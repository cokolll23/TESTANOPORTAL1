<script lang="ts" setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { usePersonalStore } from '@/stores/personal'
import { usePermissionsStore } from '@/stores/permissions'

import { KtBtn } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle } from '@/components/lk'
import { KtPersonalInfoReadMode, KtPersonalInfoEditMode } from './'

const { FULL_NAME, WORK_POSITION } = storeToRefs(usePersonalStore())
const { CAN_EDIT } = storeToRefs(usePermissionsStore())

const mode = ref<'read' | 'edit'>('read')
const isReadMode = computed(() => mode.value === 'read')

function toggleMode (value: 'read' | 'edit') {
  mode.value = value
}
</script>

<template>
  <kt-widget-wrapper>
    <article class="kt-personal-contacts">
      <header v-if="isReadMode" class="kt-personal-contacts__header">
        <kt-widget-title :text="$t('personalContacts.title')" />

        <kt-btn v-if="CAN_EDIT"
                theme="tertiary"
                icon="pencil"
                round
                dense
                @click="toggleMode('edit')"
        />
      </header>

      <div class="kt-personal-contacts__content">
        <kt-personal-info-read-mode v-if="isReadMode" />
        <kt-personal-info-edit-mode v-else @change-mode="toggleMode" />
      </div>
    </article>
  </kt-widget-wrapper>
</template>

<style lang="scss">
.kt-personal-contacts {
  &__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: var(--kt-ui-offset-md);
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }
}
</style>
