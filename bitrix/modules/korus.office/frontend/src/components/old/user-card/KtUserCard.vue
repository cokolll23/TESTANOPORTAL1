<template>
  <a :href="href"
     :target="props.target"
     class="kt-user-card kt-link"
     :class="layoutClasses"
  >
    <div v-if="!props.hideAvatar" class="kt-user-card__avatar-wrapper">
      <q-avatar :size="avatar.size" :color="avatar.bgColor">
        <q-img :src="props.user.PHOTO" ratio="1" :width="avatar.size" />
      </q-avatar>
    </div>

    <div v-if="!props.hideContent" class="kt-user-card__content">
      <span class="kt-user-card__name">{{ props.user.FULL_NAME }}</span>

      <div v-if="hasWorkPosition" class="kt-user-card__work-position">
        {{ props.user.WORK_POSITION }}
      </div>
    </div>
  </a>
</template>

<script lang="ts" setup>
import { reactive, computed } from 'vue'
import { IUser, ILinkTheme, ILinkTarget } from '@/models'

interface IKtUserCardProps {
  user:         IUser;
  hideAvatar?:  boolean;
  hideContent?: boolean;
  size?:        string;
  theme?:       ILinkTheme;
  target?:      ILinkTarget
  justify?:       'start' | 'center' | 'end';
}

const props = withDefaults(defineProps<IKtUserCardProps>(), {
  hideAvatar: false,
  hideContent: false,
  size: 'md',
  theme: 'primary',
  target: '_self'
})

const layoutClasses  = computed(() => ([
  ['xl', 'md', 'xs'].includes(props.size) ? `kt-user-card--${props.size}` : '',
  `kt-link--${props.theme}-theme`,
  props.justify ? `justify-${props.justify}` : ''
]))
const hasWorkPosition = computed(() => props.user.WORK_POSITION != null)
const href = computed(() => `/company/personal/user/${props.user.ID}/`)
const avatar = reactive({
  size: computed(() => {
    /**
     * Если должность пользователя не указана, то уменьшаем
     * высоту аватара на значение, равное минимальной высоте
     * блока должность (line-height при соответствующем размере `size`)
     */
    switch (props.size) {
      case 'xl':
        // line-height: 24px
        return hasWorkPosition.value ? '100px' : '76px'
      case 'md':
        // line-height: 20px
        return hasWorkPosition.value ? '56px' : '36px'
      case 'xs':
        // line-height: 14px
        return hasWorkPosition.value ? '40px' : '26px'
      default:
        return props.size
    }
  }),
  bgColor: computed(() => props.user.PHOTO === null ? 'app-grey-13' : '')
})
</script>

<style lang="scss">
.pgk {
  .kt-user-card {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin: 2px 0;

    &__work-position {
      $color: var(--q-app-grey-5);

      word-break: break-word;
      color: $color;
    }
  }

  .kt-user-card--xl .kt-user-card__avatar-wrapper .q-img__loading .q-spinner {
    $font-size: 30px;

    font-size: $font-size;
  }

  .kt-user-card--xl .kt-user-card__avatar-wrapper + .kt-user-card__content {
    $margin-left: 30px;

    margin-left: $margin-left;
  }

  .kt-user-card--xl .kt-user-card__name {
    $font-size: 22px;
    $line-height: 32px;

    font-size: $font-size;
    line-height: $line-height;
  }

  .kt-user-card--xl .kt-user-card__work-position {
    $font-size: 18px;
    $line-height: 24px;

    font-size: $font-size;
    line-height: $line-height;
  }

  .kt-user-card--md .kt-user-card__avatar-wrapper .q-img__loading .q-spinner {
    $font-size: 20px;

    font-size: $font-size;
  }

  .kt-user-card--md .kt-user-card__avatar-wrapper + .kt-user-card__content {
    $margin-left: 10px;

    margin-left: $margin-left;
  }

  .kt-user-card--md .kt-user-card__name {
    $font-size: 12px;
    $line-height: 19px;

    font-size: $font-size;
    line-height: $line-height;

    @media screen and (min-width: $breakpoint-lg) {
      $font-size: 15px;
      $line-height: 20px;

      font-size: $font-size;
      line-height: $line-height;
    }
  }

  .kt-user-card--md .kt-user-card__work-position {
    $font-size: 11px;
    $line-height: 16px;

    font-size: $font-size;
    line-height: $line-height;

    @media screen and (min-width: $breakpoint-lg) {
      $font-size: 14px;
      $line-height: 20px;

      font-size: $font-size;
      line-height: $line-height;
    }
  }

  .kt-user-card--xs .kt-user-card__avatar-wrapper .q-img__loading .q-spinner {
    $font-size: 10px;

    font-size: $font-size;
  }

  .kt-user-card--xs .kt-user-card__avatar-wrapper + .kt-user-card__content {
    $margin-left: 10px;

    margin-left: $margin-left;
  }

  .kt-user-card--xs .kt-user-card__name {
    $font-size: 12px;
    $line-height: 19px;

    font-size: $font-size;
    line-height: $line-height;

    @media screen and (min-width: $breakpoint-lg) {
      $font-size: 15px;
      $line-height: 20px;

      font-size: $font-size;
      line-height: $line-height;
    }
  }

  .kt-user-card--xs .kt-user-card__work-position {
    $font-size: 11px;
    $line-height: 14px;

    font-size: $font-size;
    line-height: $line-height;
  }
}
</style>
