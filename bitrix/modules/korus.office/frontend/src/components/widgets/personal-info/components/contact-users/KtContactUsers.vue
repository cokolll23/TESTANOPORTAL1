<script lang="ts" setup>
import type { IUser } from '@/models'
import { KtUserCard } from '@/components/shared'
import { KtContactVisibilitySwitcherBtn } from '@/components/widgets/personal-info/components'

interface IProps {
  content:   IUser[];
  fieldCode: string;
  private:   boolean;
  hidden:    boolean;
}

const props = defineProps<IProps>()
</script>

<template>
  <div class="kt-contact-users">
    <div class="kt-contact-users__list">
      <kt-user-card v-for="user in props.content"
                    :key="user.ID"
                    :user="user"
                    show-avatar
                    show-name
                    size="sm"
      />
    </div>

    <kt-contact-visibility-switcher-btn v-if="props.private"
                                        :field-code="props.fieldCode"
                                        :hidden="props.hidden"
    />
  </div>
</template>

<style lang="scss">
.kt-contact-users {
  display: flex;
  align-items: center;
  gap: 8px;
  max-width: 450px;

  &__list {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
}
</style>
