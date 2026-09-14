<script lang="ts" setup>
import { IUser } from '@/models'
import { KtUserCard, KtTextClamp } from '@/components/shared'

interface IProps {
  users:  IUser[];
  label?: string;
}

const props = defineProps<IProps>()
</script>

<template>
  <div class="kt-structure-user-list">
    <div v-if="props.label" class="kt-structure-user-list__label">{{ props.label }}</div>

    <div class="kt-structure-user-list__users">
      <kt-user-card v-for="user in props.users"
                    :key="user.ID"
                    :user="user"
                    show-avatar
                    show-name
                    size="sm"
      >
        <template #undername>
          <kt-text-clamp :content="user.WORK_POSITION" :lines="2" />
        </template>
      </kt-user-card>
    </div>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-structure-user-list {
  & + & {
    margin-top: var(--kt-ui-offset-lg);
  }

  &__label {
    margin-bottom: var(--kt-ui-offset-lg);
    color: var(--kt-structure-user-list-label-color);
  }

  &__users {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: var(--kt-ui-offset-lg) var(--kt-ui-offset-xl);

    @media screen and (min-width: 360px) {
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    }
  }
}
</style>
