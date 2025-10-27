<template>
  <div class="min-h-screen bg-gray-100 py-4 sm:py-6">
    <div class=" mx-auto space-y-8">

      <div
        class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between transition-all duration-300 hover:shadow-xl">
        <div class="mb-4 sm:mb-0">
          <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">💰 Expense Dashboard</h1>
          <p class="text-md text-gray-500 mt-1">
            <span class="font-semibold text-blue-600">Top 5 Categories</span> Overview (Current Period)
          </p>
        </div>
        <button @click="fetchTopCategories" :disabled="loading"
          class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-md transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-[1.02]">
          <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          <span>{{ loading ? 'Refreshing...' : 'Refresh Data' }}</span>
        </button>
      </div>

      <div v-if="topCategories.length > 0 && !loading" class="grid grid-cols-1 sm:grid-cols-3 gap-6 animate-in">
        <div
          class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500 transform hover:shadow-lg transition-all duration-300">
          <p class="text-sm font-medium text-gray-500">Total Expenses</p>
          <p class="text-2xl font-extrabold text-gray-900 mt-1">PKR {{ formatAmount(totalAmount) }}</p>
        </div>
        <div
          class="bg-white rounded-xl shadow-md p-5 border-l-4 border-yellow-500 transform hover:shadow-lg transition-all duration-300">
          <p class="text-sm font-medium text-gray-500">Highest Category</p>
          <p class="text-2xl font-extrabold text-gray-900 mt-1 truncate">{{ topCategories[0]?.category || 'N/A' }}</p>
        </div>
        <div
          class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500 transform hover:shadow-lg transition-all duration-300">
          <p class="text-sm font-medium text-gray-500">Categories Tracked</p>
          <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ topCategories.length }}</p>
        </div>
      </div>

      <div v-if="loading" class="bg-white rounded-xl shadow-md border p-8 text-center text-blue-500 animate-pulse">
        <svg class="mx-auto h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="mt-2 text-lg font-semibold">Fetching the latest expense report...</p>
      </div>

      <div v-else-if="topCategories.length === 0"
        class="bg-white rounded-xl shadow-md border border-red-300 p-8 text-center text-red-600">
        <svg class="mx-auto h-8 w-8 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="mt-2 text-lg font-semibold">No Expense Data Found!</p>
        <p class="text-sm text-gray-500 mt-1">Please add some transactions or check your selected date range.</p>
      </div>

      <div v-else class="space-y-4 animate-in">
        <div v-for="(cat, index) in topCategories" :key="index"
          class="bg-white rounded-xl shadow-md p-5 border border-gray-200 hover:shadow-lg transition-all duration-300 flex items-center space-x-4">
          <div
            :class="[getRankBadgeColor(index), 'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold shadow-md']">
            {{ index + 1 }}
          </div>

          <div class="flex-grow min-w-0">
            <div class="flex items-center justify-between">
              <span class="text-lg font-semibold text-gray-800 truncate">{{ cat.category }}</span>
              <span class="text-sm font-bold text-gray-600 ml-4">{{ getPercentage(cat.total_amount) }}%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
              <div :class="[getRankColor(index), 'h-2.5 rounded-full shadow-inner transition-all duration-500']"
                :style="{ width: getPercentage(cat.total_amount) + '%' }"></div>
            </div>
          </div>

          <div class="flex-shrink-0 text-right">
            <p class="text-xl font-extrabold text-blue-600">PKR {{ formatAmount(cat.total_amount) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ getMedal(index) }} Rank</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../../../Api/AxiosBase';

// --- State and Constants ---
const topCategories = ref([]);
const loading = ref(false);
const API_URL = '/reports/top-categories'; // Aap ki API URL

// --- Computed Properties ---
const totalAmount = computed(() => {
  // Ensure correct parsing of the amount string from API for calculation
  return topCategories.value.reduce((sum, cat) => {
    // Remove all non-digit and non-dot characters (if your API returns formatted string like "10,000.00")
    const amountString = String(cat.total_amount).replace(/,/g, '');
    const amount = parseFloat(amountString);
    return sum + (isNaN(amount) ? 0 : amount); // Handle non-numeric values gracefully
  }, 0);
});

// --- Helper Functions ---
const formatAmount = (amount) => {
  // Check if amount is already a formatted string (like '10,000.00' from API)
  if (typeof amount === 'string' && amount.includes(',')) {
    return amount;
  }
  // Convert to number and format with commas and two decimal places
  const numAmount = parseFloat(String(amount).replace(/,/g, ''));
  if (isNaN(numAmount)) return '0.00';
  return numAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const getPercentage = (amount) => {
  const numAmount = parseFloat(String(amount).replace(/,/g, ''));
  if (totalAmount.value === 0 || isNaN(numAmount)) return '0.0';
  const percentage = (numAmount / totalAmount.value) * 100;
  return percentage.toFixed(1);
};

const getMedal = (index) => {
  // Better emojis for visual interest
  const medals = ['🥇 Top Spender', '🥈 Second Spot', '🥉 Third Place', '✨ High Rank', '🌟 Top 5'];
  return medals[index] || '💡 Analyzed';
};

// Progress Bar Colors (Used in template for the progress bar itself)
const getRankColor = (index) => {
  const colors = [
    'bg-gradient-to-r from-yellow-400 to-amber-500', // Gold for 1st
    'bg-gradient-to-r from-gray-400 to-gray-500',   // Silver for 2nd
    'bg-gradient-to-r from-amber-600 to-orange-700', // Bronze for 3rd
    'bg-gradient-to-r from-sky-500 to-blue-600',    // Blue for 4th
    'bg-gradient-to-r from-indigo-500 to-purple-600' // Purple for 5th
  ];
  return colors[index] || 'bg-gradient-to-r from-slate-400 to-slate-500';
};

// Rank Badge Colors (Used in template for the circular badge)
const getRankBadgeColor = (index) => {
  const colors = [
    'bg-yellow-500 text-white',
    'bg-gray-400 text-white',
    'bg-orange-600 text-white',
    'bg-blue-600 text-white',
    'bg-purple-600 text-white'
  ];
  return colors[index] || 'bg-indigo-500 text-white';
};

// --- Fetching Data ---
const fetchTopCategories = async () => {
  loading.value = true;
  try {
    const response = await api.get(API_URL);
    // Assuming API response has a key like 'data' or 'top_categories'
    // Adjust 'response.data.top_5_categories' based on your actual API structure
    topCategories.value = response.data.top_5_categories || response.data || [];
    // Only keep the top 5, just in case
    topCategories.value = topCategories.value.slice(0, 5);

  } catch (error) {
    console.error('Error fetching top categories:', error);
    topCategories.value = [];
    // Display a more user-friendly error
    alert('Failed to load top categories. Check your API connection.');
  } finally {
    loading.value = false;
  }
};

// --- Lifecycle Hook ---
onMounted(() => {
  fetchTopCategories();
});
</script>

<style scoped>
/* Add the animation from your original code */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-in {
  animation: slideIn 0.6s ease-out;
  /* Slightly longer and smoother animation */
}
</style>