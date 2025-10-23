<!-- <script setup>
import { onMounted, ref, computed } from 'vue';
import api from '../../Api/AxiosBase';
import ExpenseShow from '../../components/admin/expense/ExpenseShow.vue';
import ExpenseCreate from '../../components/admin/expense/ExpenseCreate.vue';
const showModal = ref(false);

const categorys = ref([]);
const showFilters = ref(false);
const expenses = ref([]);
const search = ref('');
// const expenses = ref([]);
const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
});
const filters = ref({
    category: '',
    dateFrom: '',
    dateTo: '',
    minAmount: '',
    maxAmount: ''
});

const handleCategory = async () => {
    try {
        const response = await api.get('categories');
        categorys.value = response.data;
    } catch (error) {
        console.log(error);
    }
};

const handleExpense = async (page = 1) => {
    try {
        const response = await api.get(`expenses?page=${page}&per_page=${pagination.value.per_page}`);
        expenses.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            per_page: response.data.per_page,
        };
    } catch (error) {
        console.log(error);
    }
};


// ✅ Filtered Expenses
const filteredExpenses = computed(() => {
    return expenses.value.filter((exp) => {
        const searchQuery = search.value.toLowerCase();

        // ✅ Search Match (check title, category name, description, or user name)
        const matchesSearch =
            exp.title?.toLowerCase().includes(searchQuery) ||
            exp.description?.toLowerCase().includes(searchQuery) ||
            exp.category?.name?.toLowerCase().includes(searchQuery) ||
            exp.user?.name?.toLowerCase().includes(searchQuery);

        // ✅ Category Filter
        const matchesCategory =
            !filters.value.category ||
            exp.category?.name === filters.value.category;

        // ✅ Amount Filters
        const matchesMin =
            !filters.value.minAmount ||
            exp.amount >= parseFloat(filters.value.minAmount);

        const matchesMax =
            !filters.value.maxAmount ||
            exp.amount <= parseFloat(filters.value.maxAmount);

        // ✅ Date Filters
        const expenseDate = new Date(exp.expense_date || exp.date);
        const matchesDateFrom =
            !filters.value.dateFrom ||
            expenseDate >= new Date(filters.value.dateFrom);

        const matchesDateTo =
            !filters.value.dateTo ||
            expenseDate <= new Date(filters.value.dateTo);

        // ✅ Final combined condition
        return (
            matchesSearch &&
            matchesCategory &&
            matchesMin &&
            matchesMax &&
            matchesDateFrom &&
            matchesDateTo
        );
    });
});

// ✅ Clear Filters
const clearFilters = () => {
    filters.value = {
        category: '',
        dateFrom: '',
        dateTo: '',
        minAmount: '',
        maxAmount: ''
    };
    search.value = '';
};

onMounted(() => {
    handleCategory();
    handleExpense();
});
</script> -->

<script setup>
import { onMounted, ref, computed } from 'vue'
import api from '../../Api/AxiosBase'
import ExpenseShow from '../../components/manager/expense/ExpenseShow.vue'
import ExpenseCreate from '../../components/manager/expense/ExpenseCreate.vue'

const showModal = ref(false)
const editMode = ref(false)
const selectedExpense = ref(null)

const categorys = ref([])
const showFilters = ref(false)
const expenses = ref([])
const search = ref('')

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10
})

const filters = ref({
  category: '',
  dateFrom: '',
  dateTo: '',
  minAmount: '',
  maxAmount: ''
})

// ✅ Fetch categories
const handleCategory = async () => {
  try {
    const response = await api.get('categories')
    categorys.value = response.data
  } catch (error) {
    console.log(error)
  }
}

// ✅ Fetch expenses
const handleExpense = async (page = 1) => {
  try {
    const response = await api.get(`expenses?page=${page}&per_page=${pagination.value.per_page}`)
    expenses.value = response.data.data
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      per_page: response.data.per_page
    }
  } catch (error) {
    console.log(error)
  }
}

// ✅ Handle edit trigger from child
const handleEditExpense = (expense) => {
  selectedExpense.value = expense
  editMode.value = true
  showModal.value = true
}

// ✅ Filtered Expenses
const filteredExpenses = computed(() => {
  return expenses.value.filter((exp) => {
    const searchQuery = search.value.toLowerCase()

    const matchesSearch =
      exp.title?.toLowerCase().includes(searchQuery) ||
      exp.description?.toLowerCase().includes(searchQuery) ||
      exp.category?.name?.toLowerCase().includes(searchQuery) ||
      exp.user?.name?.toLowerCase().includes(searchQuery)

    const matchesCategory =
      !filters.value.category || exp.category?.name === filters.value.category

    const matchesMin =
      !filters.value.minAmount || exp.amount >= parseFloat(filters.value.minAmount)

    const matchesMax =
      !filters.value.maxAmount || exp.amount <= parseFloat(filters.value.maxAmount)

    const expenseDate = new Date(exp.expense_date || exp.date)
    const matchesDateFrom =
      !filters.value.dateFrom || expenseDate >= new Date(filters.value.dateFrom)

    const matchesDateTo =
      !filters.value.dateTo || expenseDate <= new Date(filters.value.dateTo)

    return (
      matchesSearch &&
      matchesCategory &&
      matchesMin &&
      matchesMax &&
      matchesDateFrom &&
      matchesDateTo
    )
  })
})

// ✅ Clear Filters
const clearFilters = () => {
  filters.value = {
    category: '',
    dateFrom: '',
    dateTo: '',
    minAmount: '',
    maxAmount: ''
  }
  search.value = ''
}

// ✅ Reset modal state on close
const closeModal = () => {
  showModal.value = false
  editMode.value = false
  selectedExpense.value = null
}

onMounted(() => {
  handleCategory()
  handleExpense()
})
</script>


<template>
  <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
      <div>
        <h1 class="text-3xl font-bold text-slate-800">Expense Management</h1>
      </div>
      <button @click="showModal = true"
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
          <input v-model="search" type="text" placeholder="Search expenses..."
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
            <select v-model="filters.category"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
              <option value="">All Categories</option>
              <option v-for="cat in categorys" :key="cat.id" :value="cat.name">
                {{ cat.name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Date From</label>
            <input v-model="filters.dateFrom" type="date"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Date To</label>
            <input v-model="filters.dateTo" type="date"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Min Amount</label>
            <input v-model="filters.minAmount" type="number" placeholder="0"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Max Amount</label>
            <input v-model="filters.maxAmount" type="number" placeholder="∞"
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


  </div>

  <!-- Expense List -->
  <ExpenseShow :expenses="filteredExpenses" :handleExpense="handleExpense" :pagination="pagination"
    @page-change="handleExpense" @edit-expense="handleEditExpense" />

  <ExpenseCreate v-if="showModal" :handleExpense="handleExpense" :showModal="showModal" :editMode="editMode"
    :expenseToEdit="selectedExpense" :categorys="categorys" @close="closeModal" />
</template>
