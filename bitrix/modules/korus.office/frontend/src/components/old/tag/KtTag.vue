<template>
  <q-chip clickable
          :removable="props.removable"
          icon-remove="kt:remove"
          color="primary"
          text-color="white"
          class="kt-tag"
          @click="$emit('click')"
          @remove="$emit('removeTag')"
  >
    <a v-if="props.href" class="kt-tag__text" :href="props.href">#{{ props.text }}</a>
    <div v-else class="kt-tag__text">#{{ props.text }}</div>

    <div v-if="props.users?.length > 0" class="kt-tag__users" @click.stop>
      <q-avatar v-for="(user, index) in props.users"
                :key="user.ID"
                :color="user.PHOTO === null ? 'primary' : ''"
                :style="{ 'z-index': 99 - index }"
                size="18px"
      >
        <q-img :src="user.PHOTO" ratio="1" width="18px" />
      </q-avatar>

      <q-menu class="kt-tag__popup">
        <kt-user-card v-for="user in props.users" :key="user.ID" :user="user" size="xs" />
      </q-menu>
    </div>
  </q-chip>
</template>

<script lang="ts" setup>
import { KtUserCard } from 'components/old/user-card'
import { IUser } from '@/models'

interface IKtTagProps {
  text:       string;
  href?:      string;
  removable?: boolean;
  users?:     IUser[];
}

const props = defineProps<IKtTagProps>()
defineEmits(['click', 'removeTag'])
</script>

<style lang="scss">
.pgk {
  .kt-tag {
    $font-size: 12px;

    font-size: $font-size;

    @media screen and (min-width: $breakpoint-lg) {
      $font-size: 14px;

      font-size: $font-size;
    }

    &__text {
      color: var(--q-white)
    }

    &__users {
      $margin-left: 17px;

      margin-left: $margin-left;
    }

    .q-chip__icon--remove {
      $margin-left: 10px;
      $font-size: 10px;

      margin-left: $margin-left;
      font-size: $font-size;
    }

    &__popup {
      $padding: 10px;

      padding: $padding;

      .kt-user-card + .kt-user-card {
        $margin-top: 10px;

        margin-top: $margin-top;
      }
    }

    .q-avatar {
      $margin-right: 0;
      $margin-left: -7px;
      $border-color: var(--q-white);

      margin-left: $margin-left;
      margin-right: $margin-right;
      box-sizing: content-box;
      border: 1px solid $border-color;
    }
  }
}
</style>
