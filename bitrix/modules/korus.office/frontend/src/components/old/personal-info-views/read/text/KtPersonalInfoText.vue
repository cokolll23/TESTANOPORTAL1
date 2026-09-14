<template>
  <kt-widget-layout-row :label="props.label">
    <kt-widget-layout-text :color="props.color">
      <slot>
        <span v-if="props.fieldCode === 'UF_WEB_SITES'" class="layout-text-clamp">{{ props.content }}</span>
        <text-clamp v-else :text="props.content" />
      </slot>
      <q-icon v-if="props.private"
              :name="eyeStatus ? 'kt:eye-slashed' : 'kt:eye'"
              size="16px"
              color="primary"
              class="private-toggle-icon q-ml-sm"
              style="cursor: pointer"
              @click="toggleVisibility"
      />
    </kt-widget-layout-text>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import TextClamp from 'vue3-text-clamp'
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'

import { usePersonalFieldsStore } from 'stores/personal-fields'
const store = usePersonalFieldsStore()

interface IKtPersonalInfoTextProps {
  label: string;
  content: string;
  color?: string;
  fieldCode?: string;
  private?: boolean;
  hidden?: boolean;
}

const props = defineProps<IKtPersonalInfoTextProps>()

const eyeStatus = ref(props.hidden)

const toggleVisibility = () => {
  const formData = new FormData()
  formData.set('fieldCode', String(props.fieldCode))
  formData.set('value', String(eyeStatus.value ? 0 : 1))

  store.changeVisibility(formData)
  eyeStatus.value = !eyeStatus.value
}

</script>
