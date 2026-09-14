<script lang="ts" setup>
import { useLinkTemplate } from '@/composables/shared'
import { KtLink } from '@/components/shared'
import { KtContactVisibilitySwitcherBtn } from '@/components/widgets/personal-info/components'

interface IContactWorkplace {
  CITY:   string;
  FLOOR:  string;
  NUMBER: string;
  URL:    string;
}

interface IProps {
  content: IContactWorkplace;
  fieldCode: string;
  private:   boolean;
  hidden:    boolean;
}

const props = defineProps<IProps>()
</script>

<template>
  <div class="kt-contact-workplace">
    <div class="kt-contact-workplace__list">
      <div class="kt-contact-workplace-location">
        <span class="kt-contact-workplace-location__label">{{ $t('contactWorkplace.office') }}:</span>
        <span class="kt-contact-workplace-location__value">{{ props.content.CITY }}</span>
      </div>

      <div class="kt-contact-workplace-location">
        <span class="kt-contact-workplace-location__label">{{ $t('contactWorkplace.floor') }}:</span>
        <span class="kt-contact-workplace-location__value">{{ props.content.FLOOR }}</span>
      </div>

      <div class="kt-contact-workplace-location">
        <span class="kt-contact-workplace-location__label">{{ $t('contactWorkplace.number') }}:</span>
        <kt-link :href="useLinkTemplate(props.content.URL)" :text="props.content.NUMBER" />
      </div>
    </div>

    <kt-contact-visibility-switcher-btn v-if="props.private"
                                        :field-code="props.fieldCode"
                                        :hidden="props.hidden"
    />
  </div>
</template>

<style lang="scss">
.kt-contact-workplace {
  display: flex;
  align-items: center;
  gap: 8px;

  &__list {
    display: flex;
    flex-direction: column;
    gap: var(--kt-ui-offset-lg);
  }

  &-location {
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
}
</style>
