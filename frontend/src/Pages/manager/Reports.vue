<template>
  <div class="p-6 space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
      <h1 class="text-3xl font-bold text-gray-800">📊 Manager Reports</h1>
      <span class="text-sm text-gray-500">Last updated: {{ lastUpdated }}</span>
    </div>

    <!-- Info Card -->
    <div
      class="bg-gradient-to-r from-indigo-100 to-purple-100 border border-indigo-300 rounded-xl p-6 shadow-md animate-fade-in"
    >
      <div class="flex items-center space-x-4">
        <svg
          class="w-8 h-8 text-indigo-600"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M9 17v-6h13M9 5v2m0 4v2m0 4v2m-6 0h.01M3 5h.01M3 9h.01M3 13h.01M3 17h.01"
          />
        </svg>
        <div>
          <p class="text-lg font-medium text-gray-700">Coming Soon</p>
          <p class="text-sm text-gray-600">
            Manager Reports module is under development. You'll soon be able to view charts, filter data, and export summaries.
          </p>
        </div>
      </div>
    </div>

    <!-- Placeholder for future charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white border rounded-lg p-4 text-center text-gray-400">
        📈 Monthly Expense Chart
      </div>
      <div class="bg-white border rounded-lg p-4 text-center text-gray-400">
        🧾 Category Breakdown
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const lastUpdated = ref(new Date().toLocaleDateString())
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out;
}
</style>
