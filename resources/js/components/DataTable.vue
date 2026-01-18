<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th v-for="column in columns" :key="column.key" :class="[
                        'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider',
                        column.sortable ? 'cursor-pointer hover:bg-gray-100' : ''
                    ]" @click="column.sortable ? sort(column.key) : null">
                        <div class="flex items-center">
                            {{ column.label }}
                            <svg v-if="column.sortable && sortBy === column.key" class="ml-1 w-3 h-3"
                                :class="sortOrder === 'asc' ? 'text-indigo-600' : 'text-gray-400'" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="sortOrder === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                            </svg>
                        </div>
                    </th>
                    <th v-if="actions" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="data.length === 0">
                    <td :colspan="columns.length + (actions ? 1 : 0)" class="px-6 py-4 text-center text-gray-500">
                        {{ emptyMessage }}
                    </td>
                </tr>
                <tr v-for="item in paginatedData" :key="item[idKey]" class="hover:bg-gray-50">
                    <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap text-sm"
                        :class="column.class || 'text-gray-900'">
                        <slot :name="`cell-${column.key}`" :item="item" :value="getItemValue(item, column.key)">
                            {{ getItemValue(item, column.key) }}
                        </slot>
                    </td>
                    <td v-if="actions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <slot name="actions" :item="item"></slot>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="pagination && totalPages > 1"
            class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="prevPage" :disabled="currentPage === 1"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                    Previous
                </button>
                <button @click="nextPage" :disabled="currentPage === totalPages"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ startItem }}</span>
                        to
                        <span class="font-medium">{{ endItem }}</span>
                        of
                        <span class="font-medium">{{ data.length }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <button v-for="page in visiblePages" :key="page" @click="currentPage = page" :class="[
                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                            currentPage === page
                                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                        ]">
                            {{ page }}
                        </button>

                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    columns: {
        type: Array,
        required: true
    },
    idKey: {
        type: String,
        default: 'id'
    },
    loading: {
        type: Boolean,
        default: false
    },
    emptyMessage: {
        type: String,
        default: 'No data available'
    },
    pagination: {
        type: Boolean,
        default: false
    },
    itemsPerPage: {
        type: Number,
        default: 10
    },
    sortable: {
        type: Boolean,
        default: false
    },
    actions: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['sort'])

// Sorting
const sortBy = ref('')
const sortOrder = ref('asc')

// Pagination
const currentPage = ref(1)

// Computed properties
const sortedData = computed(() => {
    if (!props.sortable || !sortBy.value) return props.data

    return [...props.data].sort((a, b) => {
        const aVal = getItemValue(a, sortBy.value)
        const bVal = getItemValue(b, sortBy.value)

        if (aVal < bVal) return sortOrder.value === 'asc' ? -1 : 1
        if (aVal > bVal) return sortOrder.value === 'asc' ? 1 : -1
        return 0
    })
})

const totalPages = computed(() => {
    return Math.ceil(sortedData.value.length / props.itemsPerPage)
})

const paginatedData = computed(() => {
    if (!props.pagination) return sortedData.value

    const start = (currentPage.value - 1) * props.itemsPerPage
    const end = start + props.itemsPerPage
    return sortedData.value.slice(start, end)
})

const visiblePages = computed(() => {
    const pages = []
    const maxVisible = 5
    const total = totalPages.value
    const current = currentPage.value

    let start = Math.max(1, current - Math.floor(maxVisible / 2))
    let end = Math.min(total, start + maxVisible - 1)

    if (end - start + 1 < maxVisible) {
        start = Math.max(1, end - maxVisible + 1)
    }

    for (let i = start; i <= end; i++) {
        pages.push(i)
    }

    return pages
})

const startItem = computed(() => {
    return (currentPage.value - 1) * props.itemsPerPage + 1
})

const endItem = computed(() => {
    const end = currentPage.value * props.itemsPerPage
    return Math.min(end, sortedData.value.length)
})

// Methods
const getItemValue = (item, key) => {
    return key.split('.').reduce((obj, k) => obj?.[k], item)
}

const sort = (key) => {
    if (sortBy.value === key) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortBy.value = key
        sortOrder.value = 'asc'
    }
    emit('sort', { key, order: sortOrder.value })
}

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++
    }
}

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--
    }
}

// Watch for data changes to reset pagination
watch(() => props.data, () => {
    currentPage.value = 1
})
</script>
