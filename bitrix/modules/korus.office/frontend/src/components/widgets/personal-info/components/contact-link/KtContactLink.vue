<script lang="ts" setup>
import { computed } from 'vue'
import type { ILinkTheme } from '@/models'

import { KtLink, KtTextClamp } from '@/components/shared'
import { KtContactVisibilitySwitcherBtn } from '@/components/widgets/personal-info/components'

interface IProps {
  content:   string;
  href:      string;
  theme:     ILinkTheme;
  fieldCode: string;
  private:   boolean;
  hidden:    boolean;
}

const props = withDefaults(defineProps<IProps>(), {
  theme: 'primary'
})

const normalizedHref = computed(() => {
  const href = props.href ? props.href : props.content

  if (props.fieldCode === 'PERSONAL_WWW') {
    return href.startsWith('http://') || href.startsWith('https://') ? href : `https://${href}`
  }
  else if (props.fieldCode === 'EMAIL') {
    return href.startsWith('mailto:') ? href : `mailto:${href}`
  }

  return href
})
</script>

<template>
  <div class="kt-contact-link">
    <kt-link :href="normalizedHref" :theme="props.theme">
      <kt-text-clamp :content="props.content" :lines="1" />
    </kt-link>

    <kt-contact-visibility-switcher-btn v-if="props.private"
                                        :field-code="props.fieldCode"
                                        :hidden="props.hidden"
    />
  </div>
</template>

<style lang="scss">
.kt-contact-link {
  display: inline-flex;
  align-items: center;
  gap: var(--kt-ui-offset-md);
}
</style>
