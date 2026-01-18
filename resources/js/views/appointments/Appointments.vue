<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-2xl font-bold text-gray-900">Appointments</h1>
          <div>
            <button @click="$router.push('/appointments/create')" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium mr-3">
              Create Appointment
            </button>
            <button @click="$router.push('/appointments/calendar')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
              Calendar View
            </button>
          </div>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="loading" class="text-center">
        Loading...
      </div>
      
      <div v-else-if="appointments.length === 0" class="text-center text-gray-500">
        No appointments found
      </div>

      <div v-else class="bg-white shadow overflow-hidden sm:rounded-md">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Client
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date & Time
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Services
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="appointment in appointments" :key="appointment.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ appointment.client?.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDateTime(appointment.scheduled_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClass(appointment.status)" 
                      class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full">
                  {{ appointment.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div class="space-y-1">
                  <span v-for="service in appointment.services" :key="service.id" 
                        class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1">
                    {{ service.name }}
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button @click="$router.push(`/appointments/${appointment.id}`)" 
                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                  View
                </button>
                <button @click="$router.push(`/appointments/${appointment.id}/edit`)" 
                        class="text-blue-600 hover:text-blue-900 mr-3">
                  Edit
                </button>
                <button @click="deleteAppointment(appointment.id)" 
                        class="text-red-600 hover:text-red-900">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ApiService from '../../services/api.js'

const appointments = ref([])
const loading = ref(false)

const loadAppointments = async () => {
  loading.value = true
  try {
    const data = await ApiService.getAppointments()
    appointments.value = data.appointments
  } catch (error) {
    console.error('Failed to load appointments:', error)
  }
  loading.value = false
}

const deleteAppointment = async (id) => {
  if (confirm('Are you sure you want to delete this appointment?')) {
    try {
      await ApiService.deleteAppointment(id)
      await loadAppointments()
    } catch (error) {
      console.error('Failed to delete appointment:', error)
    }
  }
}

const formatDateTime = (dateString) => {
  return new Date(dateString).toLocaleString()
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
  loadAppointments()
})
</script>
