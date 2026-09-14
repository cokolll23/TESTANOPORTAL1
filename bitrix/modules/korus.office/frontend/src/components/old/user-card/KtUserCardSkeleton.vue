<template>
  <div class="kt-user-card-skeleton" :class="layoutClasses">
    <div v-if="!props.hideAvatar" class="kt-user-card-skeleton__avatar-wrapper">
      <q-skeleton type="circle" :size="avatar.size" />
    </div>

    <div v-if="!props.hideContent" class="kt-user-card-skeleton__content">
      <span class="kt-user-card-skeleton__name">
        <q-skeleton type="text" width="70%" />
      </span>

      <div class="kt-user-card-skeleton__work-position">
        <q-skeleton type="text" width="70%" />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, computed } from 'vue'

interface IKtUserCardProps {
  hideAvatar?:      boolean;
  hideContent?:     boolean;
  hasWorkPosition?: boolean;
  size?:            string;
}

const props = withDefaults(defineProps<IKtUserCardProps>(), {
  hideAvatar: false,
  hideContent: false,
  hasWorkPosition: false,
  size: 'md'
})

const layoutClasses  = computed(() => ([
  ['xl', 'md', 'xs'].includes(props.size) ? `kt-user-card-skeleton--${props.size}` : ''
]))
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
        return props.hasWorkPosition ? '100px' : '76px'
      case 'md':
        // line-height: 20px
        return props.hasWorkPosition ? '56px' : '36px'
      case 'xs':
        // line-height: 14px
        return props.hasWorkPosition ? '40px' : '26px'
      default:
        return props.size
    }
  })
})
</script>

<style lang="scss">
.pgk {
  .kt-user-card-skeleton {
    display: flex;
    align-items: center;

    &__content {
      flex-grow: 1;
    }

    &__name {
      display: block;
    }
  }

  .kt-user-card-skeleton--xl .kt-user-card-skeleton__avatar-wrapper + .kt-user-card-skeleton__content {
    $margin-left: 30px;

    margin-left: $margin-left;
  }

  .kt-user-card-skeleton--xl .kt-user-card-skeleton__name {
    $margin-bottom: 10px;

    margin-bottom: $margin-bottom;

    .q-skeleton {
      $height: 22px;

      height: $height;
    }
  }

  .kt-user-card-skeleton--xl .kt-user-card-skeleton__work-position .q-skeleton {
    $height: 18px;

    height: $height;
  }

  .kt-user-card-skeleton--md .kt-user-card-skeleton__avatar-wrapper + .kt-user-card-skeleton__content {
    $margin-left: 10px;

    margin-left: $margin-left;
  }

  .kt-user-card-skeleton--md .kt-user-card-skeleton__name {
    $margin-bottom: 8px;

    margin-bottom: $margin-bottom;

    .q-skeleton {
      $height: 18px;

      height: $height;
    }
  }

  .kt-user-card-skeleton--md .kt-user-card-skeleton__work-position .q-skeleton {
    $height: 14px;

    height: $height;
  }

  .kt-user-card-skeleton--xs .kt-user-card-skeleton__avatar-wrapper + .kt-user-card-skeleton__content {
    $margin-left: 10px;

    margin-left: $margin-left;
  }

  .kt-user-card-skeleton--xs .kt-user-card-skeleton__name {
    $margin-bottom: 5px;

    margin-bottom: $margin-bottom;

    .q-skeleton {
      $height: 15px;

      height: $height;
    }
  }

  .kt-user-card-skeleton--xs .kt-user-card-skeleton__work-position .q-skeleton {
    $height: 11px;

    height: $height;
  }
}
</style>
