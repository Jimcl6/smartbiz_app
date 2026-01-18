<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth.js'

const router = useRouter()
const { isAuthenticated, fetchUser } = useAuthStore()

onMounted(async () => {
  // Check authentication status on app load
  await fetchUser()
  
  // Redirect to login if not authenticated and not on login page
  if (!isAuthenticated.value && router.currentRoute.value.name !== 'login') {
    router.push('/login')
  }
})
</script>
