<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-2xl font-bold text-gray-900">
            {{ isEdit ? 'Edit Appointment' : 'Create Appointment' }}
          </h1>
          <button @click="$router.push('/appointments')" 
                  class="text-gray-500 hover:text-gray-700">
            Back to Appointments
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white shadow sm:rounded-lg sm:p-6">
        <form @submit.prevent="handleSubmit">
          <div v-if="error" class="rounded-md bg-red-50 p-4 mb-4">
            <div class="text-sm text-red-800">{{ error }}</div>
          </div>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="col-span-2 sm:col-span-1">
              <label for="client_id" class="block text-sm font-medium text-gray-700">
                Client *
              </label>
              <select
                id="client_id"
                v-model="form.client_id"
                required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :disabled="loading"
              >
                <option value="">Select a client</option>
                <option v-for="client in clients" :key="client.id" :value="client.id">
                  {{ client.name }}
                </option>
              </select>
            </div>

            <div class="col-span-2 sm:col-span-1">
              <label for="scheduled_at" class="block text-sm font-medium text-gray-700">
                Date & Time *
              </label>
              <input
                id="scheduled_at"
                v-model="form.scheduled_at"
                type="datetime-local"
                required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :disabled="loading"
              />
            </div>

            <div class="col-span-2">
              <label for="status" class="block text-sm font-medium text-gray-700">
                Status
              </label>
              <select
                id="status"
                v-model="form.status"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :disabled="loading"
              >
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>

            <div class="col-span-2">
              <label for="notes" class="block text-sm font-medium text-gray-700">
                Notes
              </label>
              <textarea
                id="notes"
                v-model="form.notes"
                rows="3"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :disabled="loading"
              ></textarea>
            </div>
          </div>

          <!-- Services Selection -->
          <div class="mt-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Services</h3>
            <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
              <div v-for="service in services" :key="service.id" class="flex items-center">
                <label class="flex items-center">
                  <input
                    type="checkbox"
                    :value="service.id"
                    v-model="form.service_ids"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    :disabled="loading"
                  />
                  <span class="ml-2 text-sm font-medium text-gray-700">
                    {{ service.name }} ({{ service.duration_minutes }}min, ${{ service.price }})
                  </span>
                </label>
              </div>
            </div>
          </div>

          <div class="mt-6">
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
              :disabled="loading"
            >
              <span v-if="!loading">{{ isEdit ? 'Update' : 'Create' }} Appointment</span>
              <span v-else>{{ isEdit ? 'Updating...' : 'Creating...' }}</span>
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ApiService from '../../services/api.js'

const route = useRoute()
const router = useRouter()

const isEdit = computed(() => !!route.params.id)
const appointmentId = computed(() => route.params.id)

const form = ref({
  client_id: '',
  scheduled_at: '',
  status: 'pending',
  notes: '',
  service_ids: []
})

const clients = ref([])
const services = ref([])
const loading = ref(false)
const error = ref('')

const loadClients = async () => {
  try {
    const data = await ApiService.getClients()
    clients.value = data.clients
  } catch (error) {
    console.error('Failed to load clients:', error)
  }
}

const loadServices = async () => {
  try {
    const data = await ApiService.getServices()
    services.value = data.services
  } catch (error) {
    console.error('Failed to load services:', error)
  }
}

const loadAppointment = async () => {
  if (isEdit.value) {
    try {
      const data = await ApiService.getAppointment(appointmentId.value)
      form.value = {
        ...data.appointment,
        service_ids: data.appointment.services.map(s => s.id)
      }
    } catch (error) {
      error.value = 'Failed to load appointment'
    }
  }
}

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  
  try {
    if (isEdit.value) {
      await ApiService.updateAppointment(appointmentId.value, form.value)
    } else {
      await ApiService.createAppointment(form.value)
    }
    router.push('/appointments')
  } catch (error) {
    error.value = error.message || 'Failed to save appointment'
  }
  
  loading.value = false
}

onMounted(() => {
  loadClients()
  loadServices()
  loadAppointment()
})
</script>
