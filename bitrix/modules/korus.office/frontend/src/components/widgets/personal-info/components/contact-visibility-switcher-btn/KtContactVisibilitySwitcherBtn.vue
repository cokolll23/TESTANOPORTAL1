<script lang="ts" setup>
import { ref, computed } from 'vue'
import { usePersonalFieldsStore } from '@/stores/personal-fields'

import { KtBtnLink, KtIcon } from '@/components/shared'

interface IProps {
  fieldCode: string;
  hidden:    boolean;
}

const props = defineProps<IProps>()

const personalFieldsStore = usePersonalFieldsStore()

const eyeStatus = ref(props.hidden)
const iconName = computed(() => {
  return eyeStatus.value ? 'eye-slashed' : 'eye'
})

function toggleVisibility () {
  const formData = new FormData()
  formData.set('fieldCode', String(props.fieldCode))
  formData.set('value', String(eyeStatus.value ? 0 : 1))

  personalFieldsStore.changeVisibility(formData)
  eyeStatus.value = !eyeStatus.value
}
</script>

<template>
  <kt-btn-link theme="primary" class="kt-contact-visibility-switcher-btn" @click="toggleVisibility">
    <template #icon>
      <kt-icon :name="iconName" size="16px" fit="contain" />
    </template>
  </kt-btn-link>
</template>

<style lang="scss">
.kt-contact-visibility-switcher-btn {
  flex-shrink: 0;
  color: currentColor;
}
</style>
