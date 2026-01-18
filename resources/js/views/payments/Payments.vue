<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
          <button @click="showPaymentForm = !showPaymentForm" 
                  class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Record Payment
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Payment Form -->
      <div v-if="showPaymentForm" class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Record New Payment</h3>
          <form @submit.prevent="handlePaymentSubmit">
            <div v-if="paymentError" class="rounded-md bg-red-50 p-4 mb-4">
              <div class="text-sm text-red-800">{{ paymentError }}</div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div class="col-span-2 sm:col-span-1">
                <label for="appointment_id" class="block text-sm font-medium text-gray-700">
                  Appointment *
                </label>
                <select
                  id="appointment_id"
                  v-model="paymentForm.appointment_id"
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :disabled="paymentLoading"
                >
                  <option value="">Select an appointment</option>
                  <option v-for="appointment in availableAppointments" :key="appointment.id" :value="appointment.id">
                    {{ appointment.client?.name }} - {{ formatDate(appointment.scheduled_at) }}
                  </option>
                </select>
              </div>

              <div class="col-span-2 sm:col-span-1">
                <label for="amount" class="block text-sm font-medium text-gray-700">
                  Amount ($) *
                </label>
                <input
                  id="amount"
                  v-model.number="paymentForm.amount"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :disabled="paymentLoading"
                />
              </div>

              <div class="col-span-2 sm:col-span-1">
                <label for="method" class="block text-sm font-medium text-gray-700">
                  Payment Method *
                </label>
                <select
                  id="method"
                  v-model="paymentForm.method"
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :disabled="paymentLoading"
                >
                  <option value="">Select method</option>
                  <option value="cash">Cash</option>
                  <option value="gcash">GCash</option>
                  <option value="bank">Bank Transfer</option>
                  <option value="card">Card</option>
                </select>
              </div>

              <div class="col-span-2 sm:col-span-1">
                <label for="status" class="block text-sm font-medium text-gray-700">
                  Status
                </label>
                <select
                  id="status"
                  v-model="paymentForm.status"
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  :disabled="paymentLoading"
                >
                  <option value="pending">Pending</option>
                  <option value="paid">Paid</option>
                  <option value="failed">Failed</option>
                </select>
              </div>
            </div>

            <div class="mt-6">
              <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                :disabled="paymentLoading"
              >
                <span v-if="!paymentLoading">Record Payment</span>
                <span v-else>Recording...</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Payments List -->
      <div v-if="loading" class="text-center">
        Loading...
      </div>
      
      <div v-else-if="payments.length === 0" class="text-center text-gray-500">
        No payments found
      </div>

      <div v-else class="bg-white shadow overflow-hidden sm:rounded-md">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Appointment
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Amount
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Method
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="payment in payments" :key="payment.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ payment.appointment?.client?.name }} - {{ formatDate(payment.appointment?.scheduled_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                ${{ payment.amount }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ payment.method }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getPaymentStatusClass(payment.status)" 
                      class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full">
                  {{ payment.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ payment.paid_at ? formatDate(payment.paid_at) : '-' }}
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

const payments = ref([])
const appointments = ref([])
const loading = ref(false)
const paymentLoading = ref(false)
const showPaymentForm = ref(false)
const paymentError = ref('')

const paymentForm = ref({
  appointment_id: '',
  amount: 0,
  method: '',
  status: 'pending'
})

const availableAppointments = computed(() => {
  return appointments.value.filter(apt => apt.status !== 'completed' && apt.status !== 'cancelled')
})

const loadPayments = async () => {
  loading.value = true
  try {
    const [paymentsData, appointmentsData] = await Promise.all([
      ApiService.getPayments(),
      ApiService.getAppointments()
    ])
    payments.value = paymentsData.payments
    appointments.value = appointmentsData.appointments
  } catch (error) {
    console.error('Failed to load payments:', error)
  }
  loading.value = false
}

const handlePaymentSubmit = async () => {
  paymentLoading.value = true
  paymentError.value = ''
  
  try {
    await ApiService.createPayment(paymentForm.value)
    paymentForm.value = {
      appointment_id: '',
      amount: 0,
      method: '',
      status: 'pending'
    }
    showPaymentForm.value = false
    await loadPayments()
  } catch (error) {
    paymentError.value = error.message || 'Failed to record payment'
  }
  
  paymentLoading.value = false
}

const formatDate = (dateString) => {
  return dateString ? new Date(dateString).toLocaleDateString() : ''
}

const getPaymentStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    paid: 'bg-green-100 text-green-800',
    failed: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
  loadPayments()
})
</script>
