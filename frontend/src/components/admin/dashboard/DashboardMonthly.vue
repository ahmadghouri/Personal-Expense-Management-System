<template>
  <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border border-gray-100">
    <h2 class="text-3xl font-semibold mb-6 text-gray-800 tracking-tight">Expense Type Breakdown</h2>

    <!-- Filters -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8">
      <div class="flex flex-col">
        <label class="text-sm font-medium text-gray-600 mb-1">Start Date</label>
        <input
          type="date"
          v-model="startDate"
          class="border border-gray-300 shadow-sm rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
      </div>

      <div class="flex flex-col">
        <label class="text-sm font-medium text-gray-600 mb-1">End Date</label>
        <input
          type="date"
          v-model="endDate"
          class="border border-gray-300 shadow-sm rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
      </div>

      <div class="flex flex-col">
        <label class="text-sm font-medium text-gray-600 mb-1">Category</label>
        <select
          v-model="selectedCategory"
          class="border border-gray-300 shadow-sm rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
        >
          <option value="">All</option>
          <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
        </select>
      </div>

      <div class="flex items-end">
        <button
          @click="filterChart"
          class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-500 transition duration-200"
        >
          Apply Filter
        </button>
      </div>
    </div>

    <!-- Chart -->
    <div class="bg-gray-50 rounded-xl p-4 shadow-inner">
      <canvas ref="chartRef" class="w-full max-w-lg mx-auto"></canvas>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { Chart, ArcElement, Tooltip, Legend, Title } from 'chart.js'
import api from '../../../Api/AxiosBase'

Chart.register(ArcElement, Tooltip, Legend, Title)

// --- States ---
const expenseData = ref([])
const filteredData = ref([])
const chartInstance = ref(null)
const chartRef = ref(null)

const startDate = ref('')
const endDate = ref('')
const selectedCategory = ref('')
const categories = ref([])

// --- Fallback data ---
const fallbackData = [
  { category: 'Rent', total: 430825, percentage: 55.72 },
  { category: 'Transport', total: 246303, percentage: 31.86 },
  { category: 'Grocery', total: 57103, percentage: 7.39 },
  { category: 'Fruits', total: 18244.97, percentage: 2.36 },
  { category: 'Meat', total: 12695, percentage: 1.64 },
  { category: 'Utilities', total: 7963, percentage: 1.03 }
]

// --- Colors ---
const colorPalette = [
  '#3B82F6', '#10B981', '#F59E0B',
  '#EF4444', '#8B5CF6', '#14B8A6',
  '#EAB308', '#F97316'
]

// --- Fetch categories ---
const handleCategory = async () => {
  try {
    const res = await api.get('/categories')
    const data = Array.isArray(res.data) ? res.data : []
    categories.value = data.map(c => c.name || c.category)
  } catch (error) {
    console.error('Error fetching categories:', error)
    categories.value = [...new Set(fallbackData.map(i => i.category))]
  }
}

// --- Fetch expense data ---
const fetchExpenseData = async () => {
  try {
    const res = await api.get('/reports/pie', {
      params: {
        start_date: startDate.value,
        end_date: endDate.value,
        category: selectedCategory.value
      }
    })

    const data = res.data?.data

    // Handle both array and object responses safely
    if (Array.isArray(data)) {
      expenseData.value = data
    } else if (Array.isArray(res.data)) {
      expenseData.value = res.data
    } else {
      console.warn('Unexpected data format, using fallback data')
      expenseData.value = fallbackData
    }

  } catch (error) {
    console.error('Error fetching expense data:', error)
    expenseData.value = fallbackData
  }

  filteredData.value = [...expenseData.value]
}

// --- Render Chart ---
const renderChart = () => {
  const ctx = chartRef.value?.getContext('2d')
  if (!ctx) return

  if (chartInstance.value) chartInstance.value.destroy()

  chartInstance.value = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: filteredData.value.map(i => i.category),
      datasets: [
        {
          label: 'Expense Percentage',
          data: filteredData.value.map(i => i.percentage),
          backgroundColor: filteredData.value.map((_, i) => colorPalette[i % colorPalette.length]),
          borderWidth: 2,
          borderColor: '#fff'
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Expense Type Percentage',
          font: { size: 18 }
        },
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: (context) => {
              const value = context.parsed
              const category = context.label
              return `${category}: ${value?.toFixed(2) ?? 0}%`
            }
          }
        }
      }
    }
  })
}

// --- Filter Function ---
const filterChart = async () => {
  await fetchExpenseData()

  let data = [...expenseData.value]
  if (selectedCategory.value) {
    data = data.filter(i => i.category === selectedCategory.value)
  }

  filteredData.value = data.length ? data : expenseData.value
  renderChart()
}

// --- Lifecycle ---
onMounted(async () => {
  await handleCategory()
  await fetchExpenseData()
  renderChart()
})
</script>

<style scoped>
canvas {
  max-height: 360px;
}
</style>
