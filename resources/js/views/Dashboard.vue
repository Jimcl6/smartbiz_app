<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <Navigation />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- KPI Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Clients</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.total_counts?.clients || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Active Services</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.total_counts?.services || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M12 8v4l-4 4h4a2 2 0 012 2v4a2 2 0 012 2h4a2 2 0 002 2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                    </path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Today's Appointments</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.total_counts?.appointments_today || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M12 8v4l-4 4h4a2 2 0 012 2v4a2 2 0 012 2h4a2 2 0 002 2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                    </path>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Monthly Revenue</dt>
                  <dd class="text-lg font-medium text-gray-900">${{ monthlyRevenue.total || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Revenue Chart -->
      <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Monthly Revenue</h3>
          <div class="border-t border-gray-200">
            <div class="p-4">
              <div v-if="monthlyRevenue.daily_breakdown?.length" class="space-y-2">
                <div v-for="day in monthlyRevenue.daily_breakdown" :key="day.date" class="flex justify-between">
                  <span class="text-sm text-gray-600">{{ day.date }}</span>
                  <span class="text-sm font-medium text-gray-900">${{ day.revenue }}</span>
                </div>
              </div>
              <div v-else class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v6a2 2 0 00-2-2h4a2 2 0 00-2 2v4a2 2 0 012 2v6a2 2 0 002 2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                  </path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">No revenue data for this month</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Upcoming Appointments -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Upcoming Appointments</h3>
          <div class="border-t border-gray-200">
            <div class="p-4">
              <div v-if="upcomingAppointments?.length" class="space-y-4">
                <div v-for="appointment in upcomingAppointments" :key="appointment.id"
                  class="border border-gray-200 rounded-lg p-4">
                  <div class="flex justify-between items-start">
                    <div>
                      <h4 class="text-sm font-medium text-gray-900">{{ appointment.client_name }}</h4>
                      <p class="text-sm text-gray-500 mt-1">{{ formatDate(appointment.scheduled_at) }}</p>
                      <div class="mt-2">
                        <span v-for="service in appointment.services" :key="service.name"
                          class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-2">
                          {{ service.name }}
                        </span>
                      </div>
                    </div>
                    <span :class="getStatusClass(appointment.status)"
                      class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full">
                      {{ appointment.status }}
                    </span>
                  </div>
                </div>
              </div>
              <div v-else class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v6a2 2 0 00-2-2h4a2 2 0 00-2 2v4a2 2 0 012 2v6a2 2 0 002 2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                  </path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">No upcoming appointments</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth.js'
import ApiService from '../services/api.js'
import Navigation from '../components/Navigation.vue'

const router = useRouter()
const { logout } = useAuthStore()

const stats = ref({})
const monthlyRevenue = ref({})
const upcomingAppointments = ref([])

const loadDashboardData = async () => {
  try {
    const [summaryData] = await Promise.all([
      ApiService.getDashboardSummary()
    ])

    stats.value = summaryData.total_counts
    monthlyRevenue.value = summaryData.monthly_revenue
    upcomingAppointments.value = summaryData.upcoming_appointments
  } catch (error) {
    console.error('Failed to load dashboard data:', error)
  }
}

const handleLogout = async () => {
  await logout()
  router.push('/login')
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    confirmed: 'bg-green-100 text-green-800',
    completed: 'bg-blue-100 text-blue-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
  loadDashboardData()
})
</script>
