<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
          <div class="flex space-x-4">
            <button 
              @click="count++"
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200"
            >
              Clicked {{ count }} times
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Hero Section -->
      <section class="text-center mb-12">
        <h2 class="text-4xl font-extrabold text-gray-900 mb-4">
          Welcome to <span class="text-blue-600">SmartBiz</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
          Your Vue 3 + Laravel SPA with Tailwind CSS is ready for development!
        </p>
      </section>

      <!-- Feature Cards -->
      <section class="grid md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Vue 3</h3>
          <p class="text-gray-600">Modern reactive framework with Composition API</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Laravel</h3>
          <p class="text-gray-600">Powerful PHP backend framework</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Tailwind CSS</h3>
          <p class="text-gray-600">Utility-first CSS framework for rapid styling</p>
        </div>
      </section>

      <!-- Interactive Demo -->
      <section class="bg-white rounded-xl shadow-md p-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Interactive Demo</h3>
        <div class="space-y-6">
          <!-- Color Palette Demo -->
          <div>
            <h4 class="text-lg font-semibold text-gray-800 mb-3">Color Palette</h4>
            <div class="flex space-x-2">
              <div class="w-16 h-16 bg-red-500 rounded-lg"></div>
              <div class="w-16 h-16 bg-yellow-500 rounded-lg"></div>
              <div class="w-16 h-16 bg-green-500 rounded-lg"></div>
              <div class="w-16 h-16 bg-blue-500 rounded-lg"></div>
              <div class="w-16 h-16 bg-indigo-500 rounded-lg"></div>
              <div class="w-16 h-16 bg-purple-500 rounded-lg"></div>
            </div>
          </div>

          <!-- Typography Demo -->
          <div>
            <h4 class="text-lg font-semibold text-gray-800 mb-3">Typography</h4>
            <p class="text-xs text-gray-600 mb-1">Extra Small Text</p>
            <p class="text-sm text-gray-600 mb-1">Small Text</p>
            <p class="text-base text-gray-800 mb-1">Base Text</p>
            <p class="text-lg text-gray-800 mb-1">Large Text</p>
            <p class="text-xl text-gray-800 mb-1">Extra Large Text</p>
            <p class="text-2xl font-bold text-gray-900">2XL Bold Text</p>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-gray-400">SmartBiz - Vue 3 + Laravel + Tailwind CSS</p>
      </div>
    </footer>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'App',
  setup() {
    const title = ref('SmartBiz Dashboard')
    const count = ref(0)

    return {
      title,
      count
    }
  }
}
</script>
