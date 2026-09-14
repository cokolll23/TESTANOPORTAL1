<template>
  <kt-widget-layout-row :label="props.label">
    <kt-widget-layout-text v-if="props.isEmpty" :color="props.color" >
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
    <template v-else>
      <kt-link :href="defineHref(props.href ? props.href : props.content, props.fieldCode)" :theme="props.theme">
        <slot>
          <span class="layout-text-clamp">{{ props.content }}</span>
        </slot>
      </kt-link>
      <q-icon v-if="props.private"
              :name="eyeStatus ? 'kt:eye-slashed' : 'kt:eye'"
              size="16px"
              color="primary"
              class="q-ml-sm private-toggle-icon"
              style="cursor: pointer"
              @click="toggleVisibility"
      />
    </template>
  </kt-widget-layout-row>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { KtWidgetLayoutRow } from 'components/old/widget-layout-row'
import { KtWidgetLayoutText } from 'components/old/widget-layout-text'
import { KtLink } from 'components/old/link'
import { ILinkTheme } from '@/models'

import { usePersonalFieldsStore } from 'stores/personal-fields'
const store = usePersonalFieldsStore()

interface IKtPersonalInfoLinkProps {
  label:   string;
  content: string;
  isEmpty: boolean;
  href:    string;
  theme?:  ILinkTheme;
  fieldCode: string;
  private: boolean;
  hidden: boolean;
}

const props = withDefaults(defineProps<IKtPersonalInfoLinkProps>(), {
  theme: 'primary'
})

const eyeStatus:any = ref(props.hidden)

const toggleVisibility = () => {
  const formData = new FormData()
  formData.set('fieldCode', String(props.fieldCode))
  formData.set('value', String(eyeStatus.value ? 0 : 1))

  store.changeVisibility(formData)
  eyeStatus.value = !eyeStatus.value
}

const defineHref = (href: string, code: string) => {
  if (code === 'PERSONAL_WWW') {
    return href.startsWith('http://') || href.startsWith('https://') ? href : `https://${href}`
  } else if (code === 'EMAIL') {
    return href.startsWith('mailto:') ? href : `mailto:${href}`
  }
  return href
}

</script>

<style lang="scss">
.pgk {
  .layout-text-clamp {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 250px;
  }
}
</style>
