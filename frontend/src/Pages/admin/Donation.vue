<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <!-- <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
          <div>
            <h1 class="text-3xl font-bold text-slate-800">Expense Management</h1>
            <p class="text-slate-600 mt-1">Track aur manage karen apne expenses</p>
          </div>
          <button
            @click="openAddModal"
            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all hover:shadow-lg"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Expense
          </button>
        </div> -->

        <!-- Search & Filter Bar -->
        <!-- <div class="space-y-4">
          <div class="flex gap-4">
            <div class="flex-1 relative">
              <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
              </svg>
              <input
                v-model="searchTerm"
                type="text"
                placeholder="Search expenses..."
                class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
              />
            </div>
            <button
              @click="showFilters = !showFilters"
              :class="[
                'flex items-center gap-2 px-6 py-3 rounded-lg font-medium transition-colors',
                showFilters ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
              ]"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
              </svg>
              Filters
            </button>
          </div> -->

          <!-- Advanced Filters -->
          <!-- <div v-if="showFilters" class="bg-slate-50 p-4 rounded-lg border border-slate-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Category</label>
                <select
                  v-model="filters.category"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                >
                  <option value="">All Categories</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.name">
                    {{ cat.name }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Date From</label>
                <input
                  v-model="filters.dateFrom"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Date To</label>
                <input
                  v-model="filters.dateTo"
                  type="date"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Min Amount</label>
                <input
                  v-model="filters.amountMin"
                  type="number"
                  placeholder="0"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Max Amount</label>
                <input
                  v-model="filters.amountMax"
                  type="number"
                  placeholder="∞"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                />
              </div>
              <div class="flex items-end">
                <button
                  @click="clearFilters"
                  class="w-full px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-medium transition-colors"
                >
                  Clear Filters
                </button>
              </div>
            </div>
          </div>
        </div> -->

        <!-- Summary Cards -->
        <!-- <div class="mt-4 flex items-center gap-4 text-sm flex-wrap">
          <div class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium">
            Total: {{ filteredExpenses.length }} expenses
          </div>
          <div class="px-4 py-2 bg-green-50 text-green-700 rounded-lg font-medium">
            Amount: Rs. {{ totalAmount.toLocaleString() }}
          </div>
        </div>
      </div> -->

      <!-- Expenses Table -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Date</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Category</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Description</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Payment Mode</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-slate-700">Amount</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
              <tr v-if="paginatedExpenses.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                  No expenses found. Add your first expense!
                </td>
              </tr>
              <tr
                v-for="expense in paginatedExpenses"
                :key="expense.id"
                class="hover:bg-slate-50 transition-colors"
              >
                <td class="px-6 py-4 text-sm text-slate-600">
                  {{ formatDate(expense.expense_date) }}
                </td>
                <td class="px-6 py-4">
                  <div>
                    <div class="text-sm font-medium text-slate-900">{{ expense.category.name }}</div>
                    <div v-if="expense.subcategory" class="text-xs text-slate-500">
                      {{ expense.subcategory }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                  {{ expense.description || '-' }}
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                    {{ expense.payment_mode }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-semibold text-slate-900">
                  Rs. {{ expense.amount.toLocaleString() }}
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <button
                      @click="openEditModal(expense)"
                      class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                      title="Edit"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </button>
                    <button
                      @click="deleteExpense(expense.id)"
                      class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                      title="Delete"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div
          v-if="totalPages > 1"
          class="flex items-center justify-between px-6 py-4 border-t border-slate-200 bg-slate-50"
        >
          <div class="text-sm text-slate-600">
            Showing {{ indexOfFirstItem + 1 }} to {{ Math.min(indexOfLastItem, filteredExpenses.length) }} of {{ filteredExpenses.length }} expenses
          </div>
          <div class="flex gap-2">
            <button
              @click="currentPage--"
              :disabled="currentPage === 1"
              class="flex items-center gap-1 px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
              Previous
            </button>
            <button
              v-for="page in totalPages"
              :key="page"
              @click="currentPage = page"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors',
                currentPage === page
                  ? 'bg-blue-600 text-white'
                  : 'border border-slate-300 text-slate-700 hover:bg-white'
              ]"
            >
              {{ page }}
            </button>
            <button
              @click="currentPage++"
              :disabled="currentPage === totalPages"
              class="flex items-center gap-1 px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              Next
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Add/Edit Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
        @click.self="closeModal"
      >
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="flex items-center justify-between p-6 border-b border-slate-200">
            <h2 class="text-2xl font-bold text-slate-800">
              {{ editMode ? 'Edit Expense' : 'Add New Expense' }}
            </h2>
            <button
              @click="closeModal"
              class="p-2 hover:bg-slate-100 rounded-lg transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Category <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="formData.category_id"
                  required
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                >
                  <option value="">Select Category</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Subcategory
                </label>
                <input
                  v-model="formData.subcategory"
                  type="text"
                  placeholder="e.g., Groceries, Fuel"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Expense Date <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="formData.expense_date"
                  type="date"
                  required
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Amount (Rs.) <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="formData.amount"
                  type="number"
                  placeholder="0.00"
                  min="0"
                  step="0.01"
                  required
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Payment Mode <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="formData.payment_mode"
                  required
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                >
                  <option value="Cash">Cash</option>
                  <option value="Credit Card">Credit Card</option>
                  <option value="Debit Card">Debit Card</option>
                  <option value="Bank Transfer">Bank Transfer</option>
                  <option value="Mobile Wallet">Mobile Wallet</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  Attachment
                </label>
                <input
                  @change="handleFileUpload"
                  type="file"
                  accept=".jpg,.jpeg,.png,.pdf"
                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                />
                <p class="text-xs text-slate-500 mt-1">JPG, PNG, PDF (max 2MB)</p>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Description
              </label>
              <textarea
                v-model="formData.description"
                placeholder="Add any notes about this expense..."
                rows="3"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              ></textarea>
            </div>

            <div class="flex gap-3 pt-4">
              <button
                @click="closeModal"
                type="button"
                class="flex-1 px-6 py-3 border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-50 transition-colors"
              >
                Cancel
              </button>
              <button
                @click="submitForm"
                type="button"
                class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors"
              >
                {{ editMode ? 'Update Expense' : 'Add Expense' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'ExpenseManagement',
  setup() {
    // State
    const expenses = ref([]);
    const categories = ref([]);
    const showModal = ref(false);
    const editMode = ref(false);
    const currentExpense = ref(null);
    const searchTerm = ref('');
    const showFilters = ref(false);
    const currentPage = ref(1);
    const itemsPerPage = 5;

    // Filters
    const filters = ref({
      category: '',
      dateFrom: '',
      dateTo: '',
      amountMin: '',
      amountMax: ''
    });

    // Form Data
    const formData = ref({
      category_id: '',
      subcategory: '',
      expense_date: new Date().toISOString().split('T')[0],
      amount: '',
      payment_mode: 'Cash',
      description: '',
      attachment: null
    });

    // API Base URL - Replace with your Laravel backend URL
    const API_URL = 'http://localhost:8000/api';

    // Fetch expenses from backend
    const fetchExpenses = async () => {
      try {
        const response = await axios.get(`${API_URL}/expenses`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        expenses.value = response.data.expenses;
      } catch (error) {
        console.error('Error fetching expenses:', error);
        // Mock data for demo
        expenses.value = [
          { id: 1, category: { name: 'Food' }, subcategory: 'Restaurant', expense_date: '2025-10-15', amount: 1500, payment_mode: 'Credit Card', description: 'Dinner with team', user: { name: 'Ahmad' } },
          { id: 2, category: { name: 'Transport' }, subcategory: 'Fuel', expense_date: '2025-10-18', amount: 3000, payment_mode: 'Cash', description: 'Petrol', user: { name: 'Ahmad' } },
          { id: 3, category: { name: 'Entertainment' }, subcategory: 'Movies', expense_date: '2025-10-20', amount: 800, payment_mode: 'Debit Card', description: 'Movie tickets', user: { name: 'Ahmad' } },
          { id: 4, category: { name: 'Shopping' }, subcategory: 'Clothing', expense_date: '2025-10-21', amount: 5000, payment_mode: 'Cash', description: 'New shirt', user: { name: 'Ahmad' } },
          { id: 5, category: { name: 'Food' }, subcategory: 'Groceries', expense_date: '2025-10-22', amount: 2500, payment_mode: 'Credit Card', description: 'Monthly groceries', user: { name: 'Ahmad' } }
        ];
      }
    };

    // Fetch categories
    const fetchCategories = async () => {
      try {
        const response = await axios.get(`${API_URL}/categories`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        categories.value = response.data.categories;
      } catch (error) {
        console.error('Error fetching categories:', error);
        // Mock data
        categories.value = [
          { id: 1, name: 'Food' },
          { id: 2, name: 'Transport' },
          { id: 3, name: 'Entertainment' },
          { id: 4, name: 'Shopping' },
          { id: 5, name: 'Bills' }
        ];
      }
    };

    // Filtered expenses
    const filteredExpenses = computed(() => {
      let filtered = [...expenses.value];

      // Search filter
      if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase();
        filtered = filtered.filter(exp =>
          exp.category.name.toLowerCase().includes(term) ||
          exp.subcategory?.toLowerCase().includes(term) ||
          exp.description?.toLowerCase().includes(term) ||
          exp.payment_mode.toLowerCase().includes(term)
        );
      }

      // Category filter
      if (filters.value.category) {
        filtered = filtered.filter(exp => exp.category.name === filters.value.category);
      }

      // Date filters
      if (filters.value.dateFrom) {
        filtered = filtered.filter(exp => exp.expense_date >= filters.value.dateFrom);
      }
      if (filters.value.dateTo) {
        filtered = filtered.filter(exp => exp.expense_date <= filters.value.dateTo);
      }

      // Amount filters
      if (filters.value.amountMin) {
        filtered = filtered.filter(exp => exp.amount >= parseFloat(filters.value.amountMin));
      }
      if (filters.value.amountMax) {
        filtered = filtered.filter(exp => exp.amount <= parseFloat(filters.value.amountMax));
      }

      return filtered;
    });

    // Pagination
    const indexOfLastItem = computed(() => currentPage.value * itemsPerPage);
    const indexOfFirstItem = computed(() => indexOfLastItem.value - itemsPerPage);
    const paginatedExpenses = computed(() =>
      filteredExpenses.value.slice(indexOfFirstItem.value, indexOfLastItem.value)
    );
    const totalPages = computed(() => Math.ceil(filteredExpenses.value.length / itemsPerPage));

    // Total amount
    const totalAmount = computed(() =>
      filteredExpenses.value.reduce((sum, exp) => sum + parseFloat(exp.amount), 0)
    );

    // Methods
    const openAddModal = () => {
      editMode.value = false;
      currentExpense.value = null;
      formData.value = {
        category_id: '',
        subcategory: '',
        expense_date: new Date().toISOString().split('T')[0],
        amount: '',
        payment_mode: 'Cash',
        description: '',
        attachment: null
      };
      showModal.value = true;
    };

    const openEditModal = (expense) => {
      editMode.value = true;
      currentExpense.value = expense;
      formData.value = {
        category_id: expense.category_id || categories.value.find(c => c.name === expense.category.name)?.id || '',
        subcategory: expense.subcategory || '',
        expense_date: expense.expense_date,
        amount: expense.amount,
        payment_mode: expense.payment_mode,
        description: expense.description || '',
        attachment: null
      };
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
      editMode.value = false;
      currentExpense.value = null;
    };

    const handleFileUpload = (event) => {
      formData.value.attachment = event.target.files[0];
    };

    const submitForm = async () => {
      try {
        const data = new FormData();
        Object.keys(formData.value).forEach(key => {
          if (formData.value[key] !== null && formData.value[key] !== '') {
            data.append(key, formData.value[key]);
          }
        });

        if (editMode.value) {
          // Update expense
          await axios.post(`${API_URL}/expenses/${currentExpense.value.id}`, data, {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`,
              'Content-Type': 'multipart/form-data'
            }
          });
          alert('Expense updated successfully!');
        } else {
          // Add new expense
          await axios.post(`${API_URL}/expenses`, data, {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`,
              'Content-Type': 'multipart/form-data'
            }
          });
          alert('Expense added successfully!');
        }

        await fetchExpenses();
        closeModal();
      } catch (error) {
        console.error('Error submitting form:', error);
        alert('Error: ' + (error.response?.data?.message || 'Something went wrong'));
      }
    };

    const deleteExpense = async (id) => {
      if (!confirm('Are you sure you want to delete this expense?')) return;

      try {
        await axios.delete(`${API_URL}/expenses/${id}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        alert('Expense deleted successfully!');
        await fetchExpenses();
      } catch (error) {
        console.error('Error deleting expense:', error);
        alert('Error deleting expense');
      }
    };

    const clearFilters = () => {
      filters.value = {
        category: '',
        dateFrom: '',
        dateTo: '',
        amountMin: '',
        amountMax: ''
      };
    };

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('en-PK', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      });
    };

    // Lifecycle
    onMounted(() => {
      fetchExpenses();
      fetchCategories();
    });

    return {
      expenses,
      categories,
      showModal,
      editMode,
      searchTerm,
      showFilters,
      currentPage,
      filters,
      formData,
      filteredExpenses,
      paginatedExpenses,
      totalPages,
      totalAmount,
      indexOfFirstItem,
      indexOfLastItem,
      openAddModal,
      openEditModal,
      closeModal,
      handleFileUpload,
      submitForm,
      deleteExpense,
      clearFilters,
      formatDate
    };
  }
};
</script>

<style scoped>
/* Additional custom styles if needed */
</style>