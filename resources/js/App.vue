<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <Navigation v-if="isAuthenticated" />
    <router-view />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth.js'
import Navigation from './components/Navigation.vue'

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
