<template>

    <!-- Summary Cards with Glassmorphism -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-8">
        <!-- Total Expenses Card -->
        <div
            class="group relative overflow-hidden bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300">

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-3 py-1 rounded-full">EXPENSES</span>
                </div>
                <p class="text-sm text-gray-600 mb-1 font-medium">Total Expenses</p>
                <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                    {{ formatCurrency(reportsOverall.total_expense) }}</p>
            </div>
        </div>

        <!-- Total Donations Card -->
        <div
            class="group relative overflow-hidden bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300">

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-semibold text-green-600 bg-green-100 px-3 py-1 rounded-full">DONATIONS</span>
                </div>
                <p class="text-sm text-gray-600 mb-1 font-medium">Total Donations</p>
                <p
                    class="text-3xl font-bold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent">
                    {{ formatCurrency(reportsOverall.total_donation) }}</p>
            </div>
        </div>

        <!-- Overall Total Card -->
        <div
            class="group relative overflow-hidden bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6 hover:shadow-2xl transition-all duration-300 ">

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-semibold text-purple-600 bg-purple-100 px-3 py-1 rounded-full">TOTAL</span>
                </div>
                <p class="text-sm text-gray-600 mb-1 font-medium">Overall Total</p>
                <p
                    class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">
                    {{ formatCurrency(reportsOverall.overall_total) }}</p>
            </div>
        </div>
    </div>

</template>

<script setup>
import { onMounted, ref } from 'vue';
import api from '../../../Api/AxiosBase';


const reportsOverall = ref({
    totalExpenses: 0,
    totalIncome: 0,
    totalBalance: 0,
});

const getReportOverall = async () => {
    try {
        const res = await api.get('reports/summary')
        reportsOverall.value = res.data;

    } catch (error) {
        console.error('Error fetching report overall:', error);
    }
}

const formatCurrency = (amount) => {
    const numAmount = parseFloat(amount);
    return new Intl.NumberFormat('en-PK', {
        style: 'currency',
        currency: 'PKR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(numAmount);
};

onMounted(() => {
    getReportOverall();
})
</script>