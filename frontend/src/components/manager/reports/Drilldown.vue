<template>
    <div class=" bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-6">
        <div class=" mx-auto">
            <!-- Header with Back Button -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button @click="goBack"
                            class="bg-gray-200 hover:bg-gray-300 p-3 rounded-xl transition-all transform hover:scale-105">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </button>
                        <div>
                            <h1
                                class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                Expense Details - {{ categoryName }}
                            </h1>
                            <p class="text-gray-600 mt-1">Detailed breakdown of all expenses</p>
                        </div>
                    </div>

                    <!-- Category Selector -->
                    <div class="flex items-center gap-3">
                        <select v-model="selectedCategoryId" @change="fetchDrilldown"
                            class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all font-medium">
                            <option value="">Select Category</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <div
                    class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent">
                </div>
                <p class="mt-4 text-gray-600 text-lg">Loading expense details...</p>
            </div>

            <!-- No Data State -->
            <div v-else-if="!loading && expenses.length === 0" class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-xl text-gray-600 font-semibold">No expenses found</p>
                <p class="text-sm text-gray-500 mt-2">Select a category to view expense details</p>
            </div>

            <!-- Summary Cards -->
            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Amount -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Amount</p>
                            <h3 class="text-3xl font-bold">{{ formatCurrency(totalAmount) }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Entries -->
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Total Entries</p>
                            <h3 class="text-3xl font-bold">{{ expenses.length }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Amount -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Average Amount</p>
                            <h3 class="text-3xl font-bold">{{ formatCurrency(averageAmount) }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Expense Cards -->


            <!-- Table View Toggle -->
            <div v-if="expenses.length > 0" class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">Detailed Table View</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Category</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Amount</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Payment</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="expense in expenses" :key="expense.id"
                                class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ expense.id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    <div class="font-semibold">{{ expense.category?.name }}</div>
                                    <div v-if="expense.subcategory" class="text-xs text-gray-500">{{ expense.subcategory
                                    }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-green-600">
                                    {{ formatCurrency(expense.amount) }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span :class="getPaymentBadgeColor(expense.payment_mode)"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ expense.payment_mode || 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ formatDate(expense.expense_date) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="line-clamp-2">{{ expense.description || '-' }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../../Api/AxiosBase';

const route = useRoute();
const router = useRouter();

const expenses = ref([]);
const totalAmount = ref(0);
const categoryName = ref('');
const selectedCategoryId = ref('');
const categories = ref([]);
const loading = ref(false);

// Calculate average amount
const averageAmount = computed(() => {
    if (expenses.value.length === 0) return 0;
    return totalAmount.value / expenses.value.length;
});

// Format currency
const formatCurrency = (amount) => {
    const numAmount = parseFloat(amount);
    return new Intl.NumberFormat('en-PK', {
        style: 'currency',
        currency: 'PKR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(numAmount);
};

// Format date
const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('en-PK', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};



// Get payment badge color
const getPaymentBadgeColor = (mode) => {
    const colors = {
        'cash': 'bg-green-100 text-green-800',
        'card': 'bg-purple-100 text-purple-800',
        'online': 'bg-blue-100 text-blue-800',
        'mobile wallet': 'bg-orange-100 text-orange-800'
    };
    return colors[mode?.toLowerCase()] || 'bg-gray-100 text-gray-800';
};

// Fetch categories
const fetchCategories = async () => {
    try {
        const response = await api.get('/categories');
        categories.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

// Fetch drill-down data
const fetchDrilldown = async () => {
    if (!selectedCategoryId.value) return;

    loading.value = true;
    try {
        const response = await api.get(`/reports/drilldown/${selectedCategoryId.value}`);
        expenses.value = response.data.expenses || [];
        totalAmount.value = parseFloat(response.data.total_amount) || 0;

        // Get category name
        const category = categories.value.find(c => c.id == selectedCategoryId.value);
        categoryName.value = category?.name || 'Unknown Category';
    } catch (error) {
        console.error('Error fetching drill-down data:', error);
        expenses.value = [];
        totalAmount.value = 0;
        alert('Failed to load expense details');
    } finally {
        loading.value = false;
    }
};

// Go back


// Load on mount
onMounted(async () => {
    await fetchCategories();

    // Get category ID from route params or query
    const categoryId = route.params.categoryId || route.query.categoryId;
    if (categoryId) {
        selectedCategoryId.value = categoryId;
        await fetchDrilldown();
    }
});
</script>

<style scoped>
/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>