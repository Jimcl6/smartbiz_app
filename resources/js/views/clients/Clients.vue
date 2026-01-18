<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-2xl font-bold text-gray-900">Clients</h1>
          <button @click="$router.push('/clients/create')" 
                  class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Add Client
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="loading" class="text-center">
        Loading...
      </div>
      
      <div v-else-if="clients.length === 0" class="text-center text-gray-500">
        No clients found
      </div>

      <div v-else class="bg-white shadow overflow-hidden sm:rounded-md">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Phone
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="client in clients" :key="client.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ client.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ client.email || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ client.phone || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button @click="$router.push(`/clients/${client.id}`)" 
                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                  View
                </button>
                <button @click="$router.push(`/clients/${client.id}/edit`)" 
                        class="text-blue-600 hover:text-blue-900 mr-3">
                  Edit
                </button>
                <button @click="deleteClient(client.id)" 
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

const clients = ref([])
const loading = ref(false)

const loadClients = async () => {
  loading.value = true
  try {
    const data = await ApiService.getClients()
    clients.value = data.clients
  } catch (error) {
    console.error('Failed to load clients:', error)
  }
  loading.value = false
}

const deleteClient = async (id) => {
  if (confirm('Are you sure you want to delete this client?')) {
    try {
      await ApiService.deleteClient(id)
      await loadClients()
    } catch (error) {
      console.error('Failed to delete client:', error)
    }
  }
}

onMounted(() => {
  loadClients()
})
</script>
