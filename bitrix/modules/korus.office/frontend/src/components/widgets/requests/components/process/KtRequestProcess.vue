<script lang="ts" setup>
import { IUser } from '@/models'
import { KtUserCard, KtIcon, KtBadge, KtUserListLimiter } from '@/components/shared'
import { KtRequestProcessIndicator } from '@/components/widgets/requests/components'

interface IProps {
  author:        IUser;
  executors:     IUser[];
  statusId:      number;
  indicatorText: string;
  messageCount?: number;
}

const props = defineProps<IProps>()
</script>

<template>
  <div class="kt-request-process">
    <div class="kt-request-process__users">
      <kt-user-card :user="props.author" show-avatar size="sm" />

      <kt-icon name="arrow-forward" class="kt-request-process__icon" />

      <div class="kt-request-process__executors">
        <kt-user-list-limiter :users="props.executors" />
        <kt-badge v-if="props.messageCount" floating>{{ props.messageCount }}</kt-badge>
      </div>
    </div>

    <div class="kt-request-process__indicator">
      <kt-request-process-indicator :status-id="props.statusId" :text="props.indicatorText" />
    </div>
  </div>
</template>

<style lang="scss">
.kt-request-process {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 8px;

  &__users {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  &__icon {
    color: var(--kt-ui-icon-secondary);
  }

  &__executors {
    position: relative;
  }
}
</style>
