<template>
    <router-view />
</template>

<script lang="ts" setup>
import { onMounted } from 'vue'
import { API } from '@/api'
import { useRoute, useRouter } from 'vue-router'
import { useAppIcons } from '@/composables/useAppIcons'
import { useRootStore } from 'stores/root'

const { fetchAppData } = useRootStore()

useAppIcons()

onMounted(async () => {
  const router = useRouter()
  const route = useRoute()

  await router.isReady()

  API.create(route.params.id as string)

  fetchAppData()
})
</script>
