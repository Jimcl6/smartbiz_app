<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <Navigation />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Calendar</h1>
            <p class="mt-1 text-sm text-gray-500">View and manage your appointment schedule</p>
          </div>
          <div class="flex space-x-3">
            <button @click="$router.push('/appointments/create')"
              class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Create Appointment
            </button>
            <button @click="$router.push('/appointments')"
              class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
              </svg>
              List View
            </button>
          </div>
        </div>
      </div>

      <!-- Calendar Controls -->
      <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="flex justify-between items-center">
          <button @click="previousMonth"
            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>

          <div class="text-center">
            <h2 class="text-xl font-semibold text-gray-900">
              {{ currentMonth }} {{ currentYear }}
            </h2>
            <button @click="goToToday" class="mt-1 text-sm text-indigo-600 hover:text-indigo-800">
              Today
            </button>
          </div>

          <button @click="nextMonth"
            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018 8v4a8 8 0 018 8h4a8 8 0 018 8v4a8 8 0 018 8H4"></path>
        </svg>
        <p class="mt-2 text-gray-500">Loading calendar...</p>
      </div>

      <!-- Calendar Grid -->
      <div v-else class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Day Headers -->
        <div class="grid grid-cols-7 bg-gray-50">
          <div v-for="day in daysOfWeek" :key="day"
            class="text-center text-sm font-medium text-gray-700 py-3 border-b border-gray-200">
            {{ day }}
          </div>
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7">
          <div v-for="day in calendarDays" :key="day.date" :class="getDayClass(day)"
            class="min-h-32 p-2 border-r border-b border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer"
            @click="selectDate(day)">
            <div class="flex flex-col h-full">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-medium"
                  :class="{ 'text-gray-900': day.isCurrentMonth, 'text-gray-400': !day.isCurrentMonth }">
                  {{ day.dayOfMonth }}
                </span>
                <span v-if="day.isToday" class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full">
                  Today
                </span>
              </div>

              <div class="flex-1 overflow-y-auto">
                <div v-for="appointment in day.appointments" :key="appointment.id"
                  :class="getAppointmentClass(appointment.status)"
                  class="text-xs p-1 mb-1 rounded truncate cursor-pointer hover:opacity-80"
                  @click.stop="viewAppointment(appointment)">
                  <div class="font-medium">{{ formatTime(appointment.scheduled_at) }}</div>
                  <div class="truncate">{{ appointment.client_name }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Selected Date Details -->
      <div v-if="selectedDate" class="mt-6 bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-gray-900">
            Appointments for {{ formatDate(selectedDate) }}
          </h3>
          <button @click="selectedDate = null" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div v-if="selectedDateAppointments.length === 0" class="text-center py-8 text-gray-500">
          No appointments scheduled for this date
        </div>

        <div v-else class="space-y-3">
          <div v-for="appointment in selectedDateAppointments" :key="appointment.id"
            class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <div class="flex items-center mb-2">
                  <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span class="text-sm font-medium text-gray-900">
                    {{ formatTime(appointment.scheduled_at) }}
                  </span>
                  <span :class="getStatusClass(appointment.status)"
                    class="ml-3 inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full">
                    {{ appointment.status }}
                  </span>
                </div>

                <div class="flex items-center mb-2">
                  <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <span class="text-sm text-gray-900">{{ appointment.client_name }}</span>
                </div>

                <div v-if="appointment.services && appointment.services.length" class="flex flex-wrap gap-1">
                  <span v-for="service in appointment.services" :key="service.name"
                    class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                    {{ service.name }}
                  </span>
                </div>
              </div>

              <div class="flex space-x-2 ml-4">
                <button @click="editAppointment(appointment)" class="text-indigo-600 hover:text-indigo-900 text-sm">
                  Edit
                </button>
                <button @click="deleteAppointment(appointment)" class="text-red-600 hover:text-red-900 text-sm">
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import ApiService from '../../services/api.js'
import Navigation from '../../components/Navigation.vue'

const router = useRouter()

const currentDate = ref(new Date())
const appointments = ref([])
const loading = ref(false)
const selectedDate = ref(null)

const currentMonth = computed(() => currentDate.value.toLocaleDateString('en-US', { month: 'long' }))
const currentYear = computed(() => currentDate.value.getFullYear())

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

const calendarDays = computed(() => {
  const year = currentDate.value.getFullYear()
  const month = currentDate.value.getMonth()
  const firstDay = new Date(year, month, 1).getDay()
  const daysInMonth = new Date(year, month + 1, 0).getDate()

  const days = []

  // Add empty cells for days before month starts
  for (let i = 0; i < firstDay; i++) {
    days.push({ dayOfMonth: '', date: null, isCurrentMonth: false })
  }

  // Add days of month
  for (let day = 1; day <= daysInMonth; day++) {
    const date = new Date(year, month, day)
    const dateString = date.toISOString().split('T')[0]
    const dayAppointments = appointments.value.filter(apt =>
      new Date(apt.scheduled_at).toDateString() === date.toDateString()
    )

    days.push({
      dayOfMonth: day,
      date: dateString,
      isCurrentMonth: true,
      isToday: date.toDateString() === new Date().toDateString(),
      appointments: dayAppointments
    })
  }

  return days
})

const selectedDateAppointments = computed(() => {
  if (!selectedDate.value) return []
  return appointments.value.filter(apt =>
    new Date(apt.scheduled_at).toDateString() === new Date(selectedDate.value).toDateString()
  )
})

const previousMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1)
}

const nextMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1)
}

const goToToday = () => {
  currentDate.value = new Date()
}

const selectDate = (day) => {
  if (day.date) {
    selectedDate.value = day.date
  }
}

const getDayClass = (day) => {
  if (!day.isCurrentMonth) {
    return 'bg-gray-50 text-gray-400'
  }
  return day.isToday ? 'bg-blue-50' : 'bg-white'
}

const getAppointmentClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    confirmed: 'bg-green-100 text-green-800',
    completed: 'bg-blue-100 text-blue-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
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

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit'
  })
}

const viewAppointment = (appointment) => {
  router.push(`/appointments/${appointment.id}`)
}

const editAppointment = (appointment) => {
  router.push(`/appointments/${appointment.id}/edit`)
}

const deleteAppointment = async (appointment) => {
  if (confirm(`Are you sure you want to delete the appointment with ${appointment.client_name}?`)) {
    try {
      await ApiService.deleteAppointment(appointment.id)
      await loadAppointments()
    } catch (error) {
      console.error('Failed to delete appointment:', error)
      alert('Failed to delete appointment. Please try again.')
    }
  }
}

const loadAppointments = async () => {
  loading.value = true
  try {
    const data = await ApiService.getAppointments()
    appointments.value = data.appointments || []
  } catch (error) {
    console.error('Failed to load appointments:', error)
  }
  loading.value = false
}

onMounted(() => {
  loadAppointments()
})
</script>
