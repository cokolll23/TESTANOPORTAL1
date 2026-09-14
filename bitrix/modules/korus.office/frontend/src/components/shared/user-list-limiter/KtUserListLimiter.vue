<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useElementSize } from '@vueuse/core'
import type { IUser } from '@/models'

import {
  KtBtn,
  KtMenu,
  KtSeparator,
  KtScrollArea,
  KtUserCard
} from '@/components/shared'

interface IProps {
  users:  IUser[];
  limit:  number;
  title?: string;
}

const props = withDefaults(defineProps<IProps>(), {
  limit: 3
})

const usersVisible = computed(() => props.users.slice(0, props.limit))
const usersHidden = computed(() => props.users.slice(props.limit))
const hasHidden = computed(() => usersHidden.value.length > 0)

const layoutStyles = computed(() => ({
  'margin-right': `calc(${-1 * usersVisible.value.length} * var(--kt-ui-offset-md))`,
  'padding-right': `calc(${usersVisible.value.length} * var(--kt-ui-offset-md))`
}))

const popupUserListRef = ref<null | HTMLElement>(null)
const popupUserListSize = useElementSize(popupUserListRef)

const scrollAreaMaxWidth = 300
const scrollAreaWidth = computed(() => {
  return Math.max(popupUserListSize.width.value, scrollAreaMaxWidth)
})

const scrollAreaMaxHeight = 150
const scrollAreaHeight = computed(() => {
  return Math.min(popupUserListSize.height.value, scrollAreaMaxHeight)
})

const scrollAreaStyles = computed(() => {
  return { width: scrollAreaWidth.value + 'px', height: scrollAreaHeight.value + 'px' }
})
</script>

<template>
  <div class="kt-user-list-limiter" :style="layoutStyles">
    <kt-user-card v-for="(user, index) in usersVisible"
                  :key="user.ID"
                  :user="user"
                  :style="{
                    margin: 0,
                    transform: `translateX(calc(${-1 * index} * var(--kt-ui-offset-md)))`
                  }"
                  show-avatar
                  size="sm"
    />

    <kt-btn v-if="hasHidden"
            theme="secondary"
            round
            :label="`+${usersHidden.length}`"
            :style="{
              margin: 0,
              transform: `translateX(calc(${-1 * usersVisible.length} * var(--kt-ui-offset-md)))`
            }"
            class="kt-user-list-limiter__show-more-btn"
    >
      <kt-menu class="kt-user-list-limiter-popup">
        <header v-if="props.title" class="kt-user-list-limiter-popup__header">{{ props.title }}</header>

        <kt-separator v-if="props.title" class="kt-user-list-limiter-popup__separator" />

        <kt-scroll-area visible :style="scrollAreaStyles">
          <ul class="kt-user-list-limiter-popup__list" ref="popupUserListRef">
            <li v-for="user in usersHidden" :key="user.ID" class="kt-user-list-limiter-popup__item">
              <kt-user-card :user="user" show-avatar show-name size="sm" />
            </li>
          </ul>
        </kt-scroll-area>
      </kt-menu>
    </kt-btn>
  </div>
</template>

<style lang="scss">
.kt-user-list-limiter {
  display: flex;
  align-items: center;

  .kt-user-card__avatar {
    $border-width: 1px;

    font-size: calc(var(--kt-user-card-photo-size) - 2 * $border-width);
    box-sizing: content-box;
    border: $border-width solid var(--kt-ui-white);
  }

  &__show-more-btn {
    border: 1px solid var(--kt-ui-white);
    background-color: var(--kt-ui-layer-low-accent-gray);
  }
}

.kt-user-list-limiter-popup {
  &__separator {
    margin: var(--kt-ui-offset-md) 0;
    background-color: var(--kt-ui-border-subtle-01);
  }

  &__list {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  &__item + &__item {
    margin-top: var(--kt-ui-offset-md);
  }
}
</style>
