<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-2xl font-bold text-gray-900">Calendar</h1>
          <div>
            <button @click="$router.push('/appointments/create')" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium mr-3">
              Create Appointment
            </button>
            <button @click="$router.push('/appointments')" 
                    class="text-gray-500 hover:text-gray-700">
              List View
            </button>
          </div>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <div class="flex justify-between items-center mb-4">
            <button @click="previousMonth" 
                    class="p-2 text-gray-500 hover:text-gray-700">
              ← Previous
            </button>
            <h2 class="text-lg font-medium text-gray-900">
              {{ currentMonth }} {{ currentYear }}
            </h2>
            <button @click="nextMonth" 
                    class="p-2 text-gray-500 hover:text-gray-700">
              Next →
            </button>
          </div>

          <div class="grid grid-cols-7 gap-2">
            <!-- Day headers -->
            <div v-for="day in daysOfWeek" :key="day" 
                 class="text-center text-sm font-medium text-gray-700 py-2">
              {{ day }}
            </div>

            <!-- Calendar days -->
            <div v-for="day in calendarDays" :key="day.date" 
                 :class="getDayClass(day)" 
                 class="min-h-24 p-2 border border-gray-200">
              <div class="text-sm">
                {{ day.dayOfMonth }}
              </div>
              <div v-if="day.appointments?.length" class="mt-1 space-y-1">
                <div v-for="appointment in day.appointments" :key="appointment.id" 
                     :class="getAppointmentClass(appointment.status)"
                     class="text-xs p-1 rounded cursor-pointer hover:opacity-80"
                     @click="viewAppointment(appointment.id)">
                  {{ appointment.client?.name }}
                  <div class="text-xs opacity-75">
                    {{ formatTime(appointment.scheduled_at) }}
                  </div>
                </div>
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

const router = useRouter()

const currentDate = ref(new Date())
const appointments = ref([])

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
  
  // Add days of the month
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
      appointments: dayAppointments
    })
  }
  
  return days
})

const previousMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1)
}

const nextMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1)
}

const getDayClass = (day) => {
  if (!day.isCurrentMonth) {
    return 'bg-gray-50 text-gray-400'
  }
  const isToday = new Date().toDateString() === new Date(day.date).toDateString()
  return isToday ? 'bg-blue-50' : 'bg-white'
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

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString('en-US', { 
    hour: '2-digit', 
    minute: '2-digit' 
  })
}

const viewAppointment = (id) => {
  router.push(`/appointments/${id}`)
}

const loadAppointments = async () => {
  try {
    const data = await ApiService.getAppointments()
    appointments.value = data.appointments
  } catch (error) {
    console.error('Failed to load appointments:', error)
  }
}

onMounted(() => {
  loadAppointments()
})
</script>
