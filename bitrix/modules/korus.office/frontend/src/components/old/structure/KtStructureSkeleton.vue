<template>
  <div class="kt-structure-skeleton">
    <q-skeleton type="rect" width="100%" height="80px" class="q-mb-sm" />

    <q-skeleton type="text" width="120px" height="15px" class="q-mb-sm" />
    <kt-user-card-skeleton size="md" />
  </div>
</template>

<script lang="ts" setup>
import { KtUserCardSkeleton } from 'components/old/user-card'
</script>
