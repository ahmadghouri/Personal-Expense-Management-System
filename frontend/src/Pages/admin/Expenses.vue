<script setup>
import { onMounted, ref } from 'vue';
import api from '../../Api/AxiosBase';
import ExpenseShow from '../../components/admin/expense/ExpenseShow.vue';
const categorys = ref([]);
const showFilters = ref(false);

const handleCategory = async () => {
    try {
        const response = await api.get('categories')
        categorys.value = response.data;
    } catch (error) {
        console.log(error);
    }
}
onMounted(() => {
    handleCategory();
})
</script>
<template>
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Expense Management</h1>
                <!-- <p class="text-slate-600 mt-1">Track aur manage karen apne expenses</p> -->
            </div>
            <button @click="openAddModal"
                class="flex items-center gap-2 bg-amber-600 hover:bg-amber-500 text-white px-6 py-3 rounded-lg font-medium transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Expense
            </button>
        </div>

        <!-- Search & Filter Bar -->
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 w-5 h-5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input type="text" placeholder="Search expenses..."
                        class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none" />
                </div>
                <button @click="showFilters = !showFilters" :class="[
                    'flex items-center gap-2 px-6 py-3 rounded-lg font-medium transition-colors',
                    showFilters ? 'bg-amber-500 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
                ]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                </button>
            </div>

            <!-- Advanced Filters -->
            <div v-if="showFilters" class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Category</label>
                        <select
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">All Categories</option>
                            <option v-for="cat in categorys" :key="cat.id" :value="cat.name">
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date From</label>
                        <input type="date"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date To</label>
                        <input type="date"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Min Amount</label>
                        <input type="number" placeholder="0"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Max Amount</label>
                        <input type="number" placeholder="∞"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
                    </div>
                    <div class="flex items-end">
                        <button @click="clearFilters"
                            class="w-full px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-medium transition-colors">
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="mt-4 flex items-center gap-4 text-sm flex-wrap">
            <div class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium">
                Total: {{categorys.length }} expenses
            </div>
            <div class="px-4 py-2 bg-green-50 text-green-700 rounded-lg font-medium">
                <!-- Amount: Rs. {{ totalAmount.toLocaleString() }} -->
            </div>
        </div>
    </div>

        <ExpenseShow/>
</template>