<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-2xl font-bold text-gray-900">Services</h1>
          <button @click="$router.push('/services/create')" 
                  class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Add Service
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="loading" class="text-center">
        Loading...
      </div>
      
      <div v-else-if="services.length === 0" class="text-center text-gray-500">
        No services found
      </div>

      <div v-else class="bg-white shadow overflow-hidden sm:rounded-md">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Duration
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Price
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="service in services" :key="service.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ service.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ service.duration_minutes }} min
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ service.price }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="service.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                      class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full">
                  {{ service.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button @click="$router.push(`/services/${service.id}/edit`)" 
                        class="text-blue-600 hover:text-blue-900 mr-3">
                  Edit
                </button>
                <button @click="deleteService(service.id)" 
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

const services = ref([])
const loading = ref(false)

const loadServices = async () => {
  loading.value = true
  try {
    const data = await ApiService.getServices()
    services.value = data.services
  } catch (error) {
    console.error('Failed to load services:', error)
  }
  loading.value = false
}

const deleteService = async (id) => {
  if (confirm('Are you sure you want to delete this service?')) {
    try {
      await ApiService.deleteService(id)
      await loadServices()
    } catch (error) {
      console.error('Failed to delete service:', error)
    }
  }
}

onMounted(() => {
  loadServices()
})
</script>
