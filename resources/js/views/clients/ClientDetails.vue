<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-2xl font-bold text-gray-900">Client Details</h1>
          <div>
            <button @click="$router.push(`/clients/${clientId}/edit`)" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium mr-3">
              Edit
            </button>
            <button @click="$router.push('/clients')" 
                    class="text-gray-500 hover:text-gray-700">
              Back to Clients
            </button>
          </div>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="loading" class="text-center">
        Loading...
      </div>

      <div v-else-if="client" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="col-span-2 sm:col-span-1">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Contact Information</h3>
              <dl class="mt-2 space-y-2">
                <div class="flex justify-between">
                  <dt class="text-sm font-medium text-gray-500">Name:</dt>
                  <dd class="text-sm text-gray-900">{{ client.name }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-sm font-medium text-gray-500">Email:</dt>
                  <dd class="text-sm text-gray-900">{{ client.email || 'Not provided' }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-sm font-medium text-gray-500">Phone:</dt>
                  <dd class="text-sm text-gray-900">{{ client.phone || 'Not provided' }}</dd>
                </div>
              </dl>
            </div>

            <div class="col-span-2 sm:col-span-1">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Notes</h3>
              <p class="mt-2 text-sm text-gray-900">
                {{ client.notes || 'No notes available' }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Appointments Section -->
      <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Appointments</h3>
          <div class="mt-4">
            <div v-if="client.appointments?.length" class="space-y-4">
              <div v-for="appointment in client.appointments" :key="appointment.id" 
                   class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start">
                  <div>
                    <h4 class="text-sm font-medium text-gray-900">
                      {{ formatDate(appointment.scheduled_at) }}
                    </h4>
                    <p class="text-sm text-gray-500 mt-1">
                      Status: 
                      <span :class="getStatusClass(appointment.status)">
                        {{ appointment.status }}
                      </span>
                    </p>
                  </div>
                  <div class="text-right">
                    <button @click="$router.push(`/appointments/${appointment.id}`)" 
                            class="text-indigo-600 hover:text-indigo-900 text-sm">
                      View Details
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-sm text-gray-500">
              No appointments found for this client
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import ApiService from '../../services/api.js'

const route = useRoute()

const clientId = computed(() => route.params.id)
const client = ref(null)
const loading = ref(false)

const loadClient = async () => {
  loading.value = true
  try {
    const data = await ApiService.getClient(clientId.value)
    client.value = data.client
  } catch (error) {
    console.error('Failed to load client:', error)
  }
  loading.value = false
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
  loadClient()
})
</script>
