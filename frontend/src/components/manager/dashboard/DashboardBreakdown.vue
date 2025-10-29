<template>

  <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
      <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" />
      </svg>
      Donation & Expense Overview
    </h3>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Expense Pie Chart -->
      <div class="bg-gray-50 rounded-xl p-5 shadow-inner">
        <h4 class="text-lg font-semibold text-red-600 mb-3 flex items-center gap-2">
          <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 10h11M3 6h18M3 14h8M3 18h13" />
          </svg>
          Expense Breakdown
        </h4>
        <div v-if="expensePieData.labels.length > 0">
          <Pie :data="expensePieData" :options="chartOptions" />
        </div>
        <div v-else>
          <p class="text-center text-gray-500">No expense data available</p>
        </div>
      </div>

      <!-- Donation Pie Chart -->
      <div class="bg-gray-50 rounded-xl p-5 shadow-inner">
        <h4 class="text-lg font-semibold text-green-600 mb-3 flex items-center gap-2">
          <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-2.21 0-4 1.79-4 4 0 2.21 1.79 4 4 4V8z M12 2v6m0 8v6" />
          </svg>
          Donation Breakdown
        </h4>
        <div v-if="donationPieData.labels.length > 0">
          <Pie :data="donationPieData" :options="chartOptions" />
        </div>
        <div v-else>
          <p class="text-center text-gray-500">No donation data available</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Pie } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
} from 'chart.js'
import api from '../../../Api/AxiosBase'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

const categoryBreakdown = ref([])

const handleCategoryBreakdown = async () => {
  try {
    const response = await api.get('/reports/category')
    categoryBreakdown.value = response.data.map((item) => ({
      ...item,
      expense_total: Number(item.expense_total) || 0,
      donation_total: Number(item.donation_total) || 0,
    }))
  } catch (error) {
    console.error('Error fetching category breakdown:', error)
  }
}

onMounted(() => {
  handleCategoryBreakdown()
})

// Expense Pie Chart
const expensePieData = computed(() => ({
  labels: categoryBreakdown.value
    .filter((c) => c.expense_total > 0)
    .map((c) => c.category),
  datasets: [
    {
      label: 'Expenses (Rs)',
      backgroundColor: [
        '#F87171', '#DC2626', '#FB923C', '#FBBF24', '#EF4444',
        '#F59E0B', '#E11D48', '#BE123C', '#F43F5E', '#B91C1C',
      ],
      data: categoryBreakdown.value
        .filter((c) => c.expense_total > 0)
        .map((c) => c.expense_total),
    },
  ],
}))

// Donation Pie Chart 
const donationPieData = computed(() => ({
  labels: categoryBreakdown.value
    .filter((c) => c.donation_total > 0)
    .map((c) => c.category),
  datasets: [
    {
      label: 'Donations (Rs)',
      backgroundColor: [
        '#22C55E', '#16A34A', '#4ADE80', '#86EFAC', '#15803D',
        '#3B82F6', '#8B5CF6', '#F59E0B', '#EC4899', '#14B8A6',
      ],
      data: categoryBreakdown.value
        .filter((c) => c.donation_total > 0)
        .map((c) => c.donation_total),
    },
  ],
}))

// --- Chart Options ---
const chartOptions = {
  responsive: true,
  plugins: {
    legend: { position: 'bottom', labels: { color: '#374151' } },
    tooltip: {
      callbacks: {
        label: (context) =>
          `${context.label}: Rs ${Number(context.parsed).toLocaleString()}`,
      },
    },
  },
}
</script>

<style scoped>
canvas {
  max-height: 340px;
}
</style>
