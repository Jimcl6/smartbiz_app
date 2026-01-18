<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-2xl font-bold text-gray-900">
            {{ isEdit ? 'Edit Client' : 'Create Client' }}
          </h1>
          <button @click="$router.push('/clients')" 
                  class="text-gray-500 hover:text-gray-700">
            Back to Clients
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
              <label for="name" class="block text-sm font-medium text-gray-700">
                Name *
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :disabled="loading"
              />
            </div>

            <div class="col-span-2 sm:col-span-1">
              <label for="email" class="block text-sm font-medium text-gray-700">
                Email
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :disabled="loading"
              />
            </div>

            <div class="col-span-2 sm:col-span-1">
              <label for="phone" class="block text-sm font-medium text-gray-700">
                Phone
              </label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :disabled="loading"
              />
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

          <div class="mt-6">
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
              :disabled="loading"
            >
              <span v-if="!loading">{{ isEdit ? 'Update' : 'Create' }} Client</span>
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
const clientId = computed(() => route.params.id)

const form = ref({
  name: '',
  email: '',
  phone: '',
  notes: ''
})

const loading = ref(false)
const error = ref('')

const loadClient = async () => {
  if (isEdit.value) {
    try {
      const data = await ApiService.getClient(clientId.value)
      form.value = data.client
    } catch (error) {
      error.value = 'Failed to load client'
    }
  }
}

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  
  try {
    if (isEdit.value) {
      await ApiService.updateClient(clientId.value, form.value)
    } else {
      await ApiService.createClient(form.value)
    }
    router.push('/clients')
  } catch (error) {
    error.value = error.message || 'Failed to save client'
  }
  
  loading.value = false
}

onMounted(() => {
  loadClient()
})
</script>
