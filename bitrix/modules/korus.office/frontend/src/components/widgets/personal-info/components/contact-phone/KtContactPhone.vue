<script lang="ts" setup>
import { KtContactVisibilitySwitcherBtn } from '@/components/widgets/personal-info/components'

interface IProps {
  content:   string;
  fieldCode: string;
  private:   boolean;
  hidden:    boolean;
}

const props = defineProps<IProps>()
</script>

<template>
  <div class="kt-contact-phone">
    <span class="kt-contact-phone__text">{{ props.content }}</span>

    <kt-contact-visibility-switcher-btn v-if="props.private"
                                        :field-code="props.fieldCode"
                                        :hidden="props.hidden"
    />
  </div>
</template>

<style lang="scss">
.kt-contact-phone {
  display: inline-flex;
  align-items: center;
  gap: var(--kt-ui-offset-md);
}
</style>
