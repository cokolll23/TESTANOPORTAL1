<template>
  <ul v-if="hasPosts" class="kt-gratitudes-post-list">
    <li v-for="gratitude in gratitudePosts" :key="gratitude.ID" class="kt-gratitudes-post-list__item">
      <kt-gratitudes-post-item :title="gratitude.TITLE"
                               :url="gratitude.URL"
                               :datetime="gratitude.DATE_PUBLISH"
                               :icon="gratitude.ICON"
                               :icon-color="gratitude.ICON_COLOR"
                               :sender="gratitude.SENDER"
      />
    </li>
  </ul>

  <div class="kt-gratitudes-post-list__actions">
    <button v-if="canLoadPreviousPosts"
            type="button"
            :disabled="gratitudePostsLoading"
            class="kt-gratitudes-post-list__show-previous-btn"
            @click="loadPreviousPosts"
    >
      {{ $t('gratitudes.showPreviousBtn') }}
    </button>

    <button v-if="canLoadMorePosts"
            type="button"
            :disabled="gratitudePostsLoading"
            class="kt-gratitudes-post-list__show-more-btn"
            @click="loadMorePosts"
    >
      {{ $t('gratitudes.showMoreBtn') }}
    </button>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useGratitudePosts } from '@/composables/old/gratitudes/useGratitudePosts'
import { KtGratitudesPostItem } from '../'

const {
  gratitudePosts,
  gratitudePostsLoading,

  canLoadMorePosts,
  canLoadPreviousPosts,

  loadMorePosts,
  loadPreviousPosts
} = useGratitudePosts()
const hasPosts = computed(() => gratitudePosts.value.length)
</script>

<style lang="scss">
.pgk {
  .kt-gratitudes-post-list {
    $width: 100%;
    $margin-top: 25px;

    width: $width;
    margin-top: $margin-top;

    &__actions {
      $gap: 15px;
      $margin-top: 15px;

      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: $gap;
      margin-top: $margin-top;
    }

    &__show-more-btn,
    &__show-previous-btn {
      $padding: 3px 0;
      $font-size: 12px;
      $line-height: 16px;
      $opacity: 0.7;
      $color: var(--q-app-grey-5);
      $transition: opacity 0.3s ease-out;
      $z-index: 10;

      display: block;
      padding: $padding;
      font-size: $font-size;
      line-height: $line-height;
      position: relative;
      border: none;
      border-bottom: 1px dashed $color;
      outline: none;
      background: none;
      cursor: pointer;
      color: $color;
      opacity: $opacity;
      transition: $transition;
      z-index: $z-index;

      @media screen and (min-width: $breakpoint-lg) {
        $font-size: 14px;
        $line-height: 19px;

        font-size: $font-size;
        line-height: $line-height;
      }

      &:hover {
        $opacity: 1;

        opacity: $opacity;
      }
    }
  }

  .kt-gratitudes-post-list__item + .kt-gratitudes-post-list__item {
    $margin-top: 20px;

    margin-top: $margin-top;
  }
}
</style>
