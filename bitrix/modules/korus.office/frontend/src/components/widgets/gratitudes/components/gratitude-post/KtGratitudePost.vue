<script lang="ts" setup>
import { useGratitudePosts } from '@/composables/gratitudes/useGratitudePosts'
import { IUser } from '@/models'

import { KtUserCard, KtTextClamp } from '@/components/shared'
import { KtGratitude } from '@/components/widgets/gratitudes/components'

interface IProps {
  title:     string;
  url:       string;
  datetime:  string;
  sender:    IUser;
  icon:      string;
  iconColor: string;
}

const props = defineProps<IProps>()

const { openGratitudePostPage } = useGratitudePosts()
</script>

<template>
  <div class="kt-gratitude-post">
    <div class="kt-gratitude-post__left">
      <kt-user-card :user="props.sender" show-avatar size="md" class="kt-gratitude-post__user" />
      <kt-gratitude :icon="props.icon"
                    :color="props.iconColor"
                    is-active
                    class="kt-gratitude-post__gratitude"
      />
    </div>

    <div class="kt-gratitude-post__right">
      <div class="kt-gratitude-post__content">
        <a class="kt-gratitude-post__title" @click="openGratitudePostPage(props.url)">
          <kt-text-clamp :content="props.title" :lines="1" />
        </a>

        <div class="kt-gratitude-post__time">
          <kt-text-clamp :content="props.datetime" :lines="1" />
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
@use 'vars';

.kt-gratitude-post {
  display: flex;
  align-items: flex-start;

  &__left {
    display: flex;
    align-items: center;
  }

  &__right {
    overflow: hidden;
  }

  &__user {
    border: 1px solid var(--kt-gratitude-post-user-border-color);
    border-radius: 50%;
  }

  &__gratitude {
    transform: translateX(-8px);
    z-index: -1;
  }

  &__content {
    display: flex;
    flex-direction: column;
  }

  &__title {
    text-decoration: none;
    color: var(--kt-gratitude-post-title-color);
    cursor: pointer;
  }

  &__time {
    color: var(--kt-gratitude-post-time-color);
  }
}
</style>
