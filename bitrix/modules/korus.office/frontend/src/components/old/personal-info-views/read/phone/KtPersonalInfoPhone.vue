<template>
  <kt-widget-layout-row :label="props.label" v-if="visible">
    <kt-widget-layout-text :color="props.color">
      <slot>
        {{ props.content }}
      </slot>

      <q-icon v-if="props.private"
              :name="eyeStatus ? 'kt:eye-slashed' : 'kt:eye'"
              size="16px"
              color="primary"
              class="q-ml-sm private-toggle-icon"
              style="cursor: pointer"
              @click="toggleVisibility"
      />
    </kt-widget-layout-text>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'
import { usePermissionsStore } from 'stores/permissions'
import { usePersonalFieldsStore } from 'stores/personal-fields'

interface IKtPersonalInfoPhoneProps {
  label:   string;
  content: string;
  color:   string;
  private: boolean;
  hidden:  boolean;
  fieldCode: string;
}

const store = usePersonalFieldsStore()
const { IS_OWN_PROFILE } = storeToRefs(usePermissionsStore())
const props = defineProps<IKtPersonalInfoPhoneProps>()
const visible = computed(() => {
  if (props.private) {
    return IS_OWN_PROFILE.value || !props.hidden
  }

  return true
})

const eyeStatus:any = ref(props.hidden)

const toggleVisibility = () => {
  const formData = new FormData()
  formData.set('fieldCode', String(props.fieldCode))
  formData.set('value', String(eyeStatus.value ? 0 : 1))

  store.changeVisibility(formData)
  eyeStatus.value = !eyeStatus.value
}
</script>
