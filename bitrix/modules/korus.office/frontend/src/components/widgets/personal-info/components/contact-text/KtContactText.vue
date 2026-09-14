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
  <div class="kt-contact-text">
    <span class="kt-contact-text__content">{{ props.content }}</span>

    <kt-contact-visibility-switcher-btn v-if="props.private"
                                        :field-code="props.fieldCode"
                                        :hidden="props.hidden"
    />
  </div>
</template>

<style lang="scss">
.kt-contact-text {
  max-width: 400px;
  display: inline-flex;
  align-items: center;
  gap: var(--kt-ui-offset-md);
}
</style>
