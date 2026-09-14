<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVacationsStore } from '@/stores/current-vacation'
import { usePermissionsStore } from '@/stores/permissions'

import { KtUserCard, KtBtn, KtSeparator } from '@/components/shared'
import { KtWidgetWrapper, KtWidgetTitle, KtWidgetStub } from '@/components/lk'

import stubImage from '@/assets/widgets/deputy/stub.png'

const { VACATION, VACATION_FORMATTED, USERS: DEPUTY_USERS } = storeToRefs(useVacationsStore())
const { EDIT_DEPUTY } = storeToRefs(usePermissionsStore())

const deputyUserList = computed(() => {
  const users = DEPUTY_USERS.value

  if (VACATION.value == null || users == null) {
    return []
  }

  return VACATION.value.DEPUTY.map(id => users[id])
})
</script>

<template>
  <kt-widget-wrapper>
    <article class="kt-deputy">
      <header class="kt-deputy__header">
        <kt-widget-title href="/lk/deputy/" :text="$t('deputy.title')" />
      </header>

      <div class="kt-deputy__content">
        <div v-if="deputyUserList.length > 0" class="kt-deputy-block">
          <template v-for="(deputyUser, index) in deputyUserList" :key="deputyUser.ID">
            <span class="kt-deputy-block__period">{{ VACATION_FORMATTED }}</span>
            <kt-user-card :user="deputyUser" show-avatar show-name size="sm">
              <template #undername>{{ deputyUser.WORK_POSITION }}</template>
            </kt-user-card>

            <kt-separator v-if="index !== deputyUserList.length - 1" class="kt-deputy-block__separator" />
          </template>
        </div>

        <kt-widget-stub v-else :text="$t('deputy.stubText')" :icon="stubImage" />
      </div>

      <footer v-if="EDIT_DEPUTY" class="kt-deputy__footer">
        <kt-btn theme="primary" href="/lk/deputy/" :label="$t('common.btn.add')" icon-right="add" />
      </footer>
    </article>
  </kt-widget-wrapper>
</template>

<style lang="scss">
@use 'vars';

.kt-deputy {
  &__header {
    margin-bottom: var(--kt-widget-wrapper-header-offset);
  }

  &-block {
    display: flex;
    flex-direction: column;
    gap: 8px;

    &__period {
      color: var(--kt-deputy-period-color);
    }

    &__separator {
      margin: var(--kt-deputy-user-separator-offset) 0;
      background-color: var(--kt-deputy-user-separator-bg);
    }
  }

  &__footer {
    margin-top: var(--kt-widget-wrapper-footer-offset);
    text-align: center;
  }
}
</style>
