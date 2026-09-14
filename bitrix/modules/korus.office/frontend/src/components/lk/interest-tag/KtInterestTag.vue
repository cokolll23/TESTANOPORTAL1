<script lang="ts" setup>
import type { IInterest } from '@/stores/interests'
import { KtTag, KtAvatar, KtImg, KtMenu, KtUserCard } from '@/components/shared'

interface IProps {
  text:  string;
  users: IInterest['USERS'];
}

const props = withDefaults(defineProps<IProps>(), {
  users: () => ([])
})
</script>

<template>
  <kt-tag v-bind="$attrs" class="kt-interest-tag">
    <div class="kt-interest-tag__content">
      <a v-if="$attrs['href']" :href="$attrs['href']" class="kt-interest-tag__text">#{{ props.text }}</a>
      <span v-else class="kt-interest-tag__text">#{{ props.text }}</span>

      <div v-if="props.users.length > 0"
           :style="{
             'margin-right': `calc(${-1 * (props.users.length - 1)} * var(--kt-ui-offset-md))`,
           }"
           class="kt-interest-tag__users"
           @click.stop
      >
        <kt-avatar v-for="(user, index) in props.users"
                   :key="user.ID"
                   :style="{
                     margin: 0,
                     transform: `translateX(calc(${-1 * index} * var(--kt-ui-offset-md)))`
                   }"
                   size="20px"
                   class="kt-interest-tag__user"
        >
          <kt-img :src="user.PHOTO" alt="" no-spinner ratio="1" width="20px" />
        </kt-avatar>

        <kt-menu class="kt-interest-tag-popover">
          <kt-user-card v-for="user in props.users"
                        :key="user.ID"
                        :user="user"
                        show-avatar
                        show-name
                        size="sm"
                        class="kt-interest-tag-popover__user"
          />
        </kt-menu>
      </div>
    </div>
  </kt-tag>
</template>

<style lang="scss">
.kt-interest-tag {
  &.q-chip--selected .q-avatar {
    display: inline-block;
  }

  &__content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--kt-ui-offset-sm);
    max-width: 100%;
  }

  &__text {
    overflow: hidden;
    text-overflow: ellipsis;
  }

  &:is(:hover, :active, :focus-visible) &__text {
    --ui-link-color: var(--kt-tag-color);
  }

  &__users {
    cursor: pointer;
  }

  &__user {
    margin-left: calc(-1 * var(--kt-ui-offset-md));
    box-sizing: content-box;
    border: 1px solid var(--kt-ui-white);
  }

  &-popover__user + &-popover__user {
    margin-top: var(--kt-ui-offset-md);
  }
}
</style>
