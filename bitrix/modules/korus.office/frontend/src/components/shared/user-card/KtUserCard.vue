<script lang="ts" setup>
import { computed } from 'vue'
import { useImageSrc } from '@/composables/shared'
import { IUser, ILinkTheme } from '@/models'
import { KtAvatar, KtImg, KtLink } from '@/components/shared'

interface IProps {
  user:        IUser;
  showAvatar?: boolean;
  showName?:   boolean;
  boldName?:   boolean;
  size?:       'sm' | 'md' | 'lg';
  theme?:      ILinkTheme;
}

const props = withDefaults(defineProps<IProps>(), {
  showAvatar: false,
  showName: false,
  size: 'md',
  theme: 'dark'
})

const href = computed(() => `/company/personal/user/${props.user.ID}/`)

const isDefaultSize = computed(() => ['sm', 'md', 'lg'].includes(props.size))
const layoutClasses = computed(() => ({
  [`size-${props.size}`]: isDefaultSize.value
}))
</script>

<template>
  <article class="kt-user-card" :class="layoutClasses" data-test-user-card="card">
    <a v-if="props.showAvatar"
       :href="href"
       target="_blank"
       class="kt-user-card__avatar-wr"
       data-test-user-card="avatar-wrapper"
    >
      <kt-avatar class="kt-user-card__avatar">
        <kt-img v-bind="useImageSrc(props.user.PHOTO)" alt="" ratio="1" />
      </kt-avatar>
    </a>

    <div v-if="props.showName || $slots.undername"
         class="kt-user-card__content"
         data-test-user-card="content"
    >
      <kt-link :href="href"
               :text="props.user.FULL_NAME"
               :class="{
                 'text-weight-bold': props.boldName,
                 'kt-user-card__name': true
               }"
               :theme="props.theme"
               target="_blank"
               data-test-user-card="name"
      >

      </kt-link>

      <span v-if="$slots.undername" class="kt-user-card__undername" data-test-user-card="undername">
        <slot name="undername"></slot>
      </span>
    </div>
  </article>
</template>

<style lang="scss">
/**
  * TODO:REMOVE класс-завязку на боди
 */
body:not(.pgk) {
  .kt-user-card {
    display: flex;
    align-items: center;
    gap: 15px;

    &.size-sm {
      --kt-user-card-photo-size: var(--kt-user-card-photo-size-sm);
      --kt-user-card-name-font-size: var(--kt-user-card-name-font-size-sm);
      --kt-user-card-name-line-height: var(--kt-user-card-name-line-height-sm);
      --kt-user-card-undername-font-size: var(--kt-user-card-undername-font-size-sm);
      --kt-user-card-undername-line-height: var(--kt-user-card-undername-line-height-sm);

      gap: 10px;
    }

    &.size-md {
      --kt-user-card-photo-size: var(--kt-user-card-photo-size-md);
      --kt-user-card-name-font-size: var(--kt-user-card-name-font-size-md);
      --kt-user-card-name-line-height: var(--kt-user-card-name-line-height-md);
      --kt-user-card-undername-font-size: var(--kt-user-card-undername-font-size-md);
      --kt-user-card-undername-line-height: var(--kt-user-card-undername-line-height-md);
    }

    &.size-lg {
      --kt-user-card-photo-size: var(--kt-user-card-photo-size-lg);
      --kt-user-card-name-font-size: var(--kt-user-card-name-font-size-lg);
      --kt-user-card-name-line-height: var(--kt-user-card-name-line-height-lg);
      --kt-user-card-undername-font-size: var(--kt-user-card-undername-font-size-lg);
      --kt-user-card-undername-line-height: var(--kt-user-card-undername-line-height-lg);

      gap: 20px;
    }

    &__avatar-wr .q-img__loading .q-spinner {
      font-size: 10px;
    }

    &__avatar {
      font-size: var(--kt-user-card-photo-size);
    }

    &__content {
      display: flex;
      flex-direction: column;
      overflow: hidden;
      gap: 2px;
      margin: 0;
    }

    &__name {
      font-size: var(--kt-user-card-name-font-size);
      line-height: var(--kt-user-card-name-line-height);
      word-break: break-word;
    }

    &__undername {
      font-size: var(--kt-user-card-undername-font-size);
      line-height: var(--kt-user-card-undername-line-height);
      color: var(--kt-user-card-undername-color);
    }
  }
}
</style>
