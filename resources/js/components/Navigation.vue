<template>
  <nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <router-link to="/" class="text-xl font-bold text-gray-900 hover:text-indigo-600">
            SmartBiz
          </router-link>
        </div>

        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-4">
            <router-link 
              v-for="item in navigationItems" 
              :key="item.name"
              :to="item.path"
              class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
              :class="{ 'bg-gray-100': $route.name === item.name }"
            >
              {{ item.name }}
            </router-link>
          </div>

          <div class="relative">
            <button @click="toggleMobileMenu" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 6h16M4 6v16M4 6h16"></path>
              </svg>
            </button>
          </div>

          <!-- Mobile menu -->
          <div v-if="showMobileMenu" class="absolute right-0 top-16 w-48 bg-white rounded-md shadow-lg py-1 z-50">
            <div class="px-2 pt-2 pb-3 space-y-1">
              <router-link 
                v-for="item in navigationItems" 
                :key="item.name"
                :to="item.path"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                @click="closeMobileMenu"
              >
                {{ item.name }}
              </router-link>
            </div>
          </div>
        </div>

        <!-- User menu -->
        <div class="ml-4 flex items-center md:ml-6">
          <div class="relative">
            <button @click="toggleUserMenu" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
              <span class="sr-only">Open user menu</span>
              <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 00-3 3v1a3 3 0 003 3h18a3 3 0 003 3v1a3 3 0 003 3h18a3 3 0 003 3z"></path>
                </svg>
              </div>
            </button>

            <!-- User dropdown -->
            <div v-if="showUserMenu" class="absolute right-0 top-16 w-48 bg-white rounded-md shadow-lg py-1 z-50">
              <div class="px-2 pt-2 pb-3 space-y-1">
                <div class="px-4 py-2 text-sm text-gray-700">
                  Logged in as {{ user?.name || 'User' }}
                </div>
                <div class="border-t border-gray-100"></div>
                <button @click="handleLogout" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                  Sign out
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth.js'

const router = useRouter()
const { user, logout } = useAuthStore()

const showMobileMenu = ref(false)
const showUserMenu = ref(false)

const navigationItems = computed(() => [
  { name: 'Dashboard', path: '/' },
  { name: 'Clients', path: '/clients' },
  { name: 'Services', path: '/services' },
  { name: 'Appointments', path: '/appointments' },
  { name: 'Calendar', path: '/appointments/calendar' },
  { name: 'Payments', path: '/payments' }
])

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
  showUserMenu.value = false
}

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
  showMobileMenu.value = false
}

const handleLogout = async () => {
  await logout()
  router.push('/login')
}

const closeMobileMenu = () => {
  showMobileMenu.value = false
}
</script>
