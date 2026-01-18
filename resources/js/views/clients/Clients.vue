<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <h1 class="text-2xl font-bold text-gray-900">Clients</h1>
          <button @click="$router.push('/clients/create')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            Add Client
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <BaseComponent :loading="loading" :error="error" loading-text="Loading clients...">
        <DataTable :data="clients" :columns="columns" :loading="loading" empty-message="No clients found"
          :pagination="false" :sortable="true" @sort="handleSort">
          <template #cell-name="{ item }">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                  <span class="text-indigo-600 font-medium text-sm">{{ getInitials(item.name) }}</span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
                <div class="text-sm text-gray-500">{{ item.appointments_count || 0 }} appointments</div>
              </div>
            </div>
          </template>

          <template #cell-email="{ value }">
            <span class="text-sm text-gray-900">{{ value || '-' }}</span>
          </template>

          <template #cell-phone="{ value }">
            <span class="text-sm text-gray-900">{{ value || '-' }}</span>
          </template>

          <template #actions="{ item }">
            <button @click="$router.push(`/clients/${item.id}`)"
              class="text-indigo-600 hover:text-indigo-900 mr-3 transition-colors">
              View
            </button>
            <button @click="$router.push(`/clients/${item.id}/edit`)"
              class="text-blue-600 hover:text-blue-900 mr-3 transition-colors">
              Edit
            </button>
            <button @click="deleteClient(item)" class="text-red-600 hover:text-red-900 transition-colors">
              Delete
            </button>
          </template>
        </DataTable>
      </BaseComponent>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ApiService from '../../services/api.js'
import BaseComponent from '../../components/BaseComponent.vue'
import DataTable from '../../components/DataTable.vue'

const clients = ref([])
const loading = ref(false)
const error = ref('')

const columns = [
  { key: 'name', label: 'Name', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'phone', label: 'Phone', sortable: true }
]

const loadClients = async () => {
  loading.value = true
  error.value = ''
  try {
    const data = await ApiService.getClients()
    clients.value = data.clients
  } catch (err) {
    console.error('Failed to load clients:', err)
    error.value = 'Failed to load clients. Please try again.'
  }
  loading.value = false
}

const deleteClient = async (client) => {
  if (confirm(`Are you sure you want to delete ${client.name}?`)) {
    try {
      await ApiService.deleteClient(client.id)
      await loadClients()
    } catch (err) {
      console.error('Failed to delete client:', err)
      error.value = 'Failed to delete client. Please try again.'
    }
  }
}

const getInitials = (name) => {
  return name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .join('')
    .slice(0, 2)
}

const handleSort = ({ key, order }) => {
  // Sorting is handled by the DataTable component
  console.log(`Sort by ${key} ${order}`)
}

onMounted(() => {
  loadClients()
})
</script>
