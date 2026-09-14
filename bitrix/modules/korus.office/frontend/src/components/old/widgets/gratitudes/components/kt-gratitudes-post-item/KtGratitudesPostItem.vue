<template>
  <div class="kt-gratitude-post">
    <div class="kt-gratitude-post__left">
      <kt-user-card :user="props.sender" size="md" hide-content />
      <kt-icon :name="props.icon" :background-color="props.iconColor" color="white" size="sm" icon-size="20px" />
    </div>

    <div class="kt-gratitude-post__right">
      <div class="kt-gratitude-post__content">
        <kt-link :text="props.title"
                 class="kt-gratitude-post__title ellipsis"
                 @click="openGratitudePostPage(props.url)"
        />

        <time class="kt-gratitude-post__time" :datetime="props.datetime">
          {{ props.datetime }}
        </time>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { useGratitudePosts } from '@/composables/old/gratitudes/useGratitudePosts'
import { KtLink } from 'components/old/link'
import { KtIcon } from 'components/old/icon'
import { KtUserCard } from 'components/old/user-card'
import { IUser } from '@/models'

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

<style lang="scss">
.pgk {
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

    .q-avatar,
    .kt-gratitudes-item {
      position: relative;
    }

    .q-avatar {
      $border-width: 2px;
      $border-color: var(--q-white);
      $z-index: 10;

      box-sizing: content-box;
      border: $border-width solid $border-color;
      z-index: $z-index;
    }

    .kt-icon-wrapper {
      $transform: translateX(-12px);
      $z-index: 5;

      transform: $transform;
      z-index: $z-index;
    }

    &__content {
      display: flex;
      flex-direction: column;
    }

    &__title,
    &__time {
      $font-size: 12px;
      $line-height: 16px;

      font-size: $font-size;
      line-height: $line-height;

      @media screen and (min-width: $breakpoint-lg) {
        $font-size: 14px;
        $line-height: 19px;

        font-size: $font-size;
        line-height: $line-height;
      }
    }

    &__title {
      $color: var(--q-app-grey-2);

      text-decoration: none;
      color: $color;
    }

    &__time {
      $color: var(--q-app-grey-7);

      color: $color;
    }
  }
}
</style>
