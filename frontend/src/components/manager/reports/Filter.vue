<template>
  <div class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <div class="mx-auto">
      <!-- Filter Form -->
      <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Filters</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
          <!-- Category Filter -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
            <select v-model="filters.category_id"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all">
              <option value="">All Categories</option>
              <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>

          <!-- Payment Mode Filter -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Mode</label>
            <select v-model="filters.payment_mode"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all">
              <option value="">All Payment Modes</option>
              <option v-for="mode in payment_mode" :key="mode.id" :value="mode.name">
                {{ mode.name }}
              </option>
            </select>
          </div>

          <!-- Donation Type Filter -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Donation Type</label>
            <select v-model="filters.donation_type"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all">
              <option value="">All Donation Types</option>
              <option v-for="d in donationTypes" :key="d.id" :value="d.id">{{ d.name }}</option>
            </select>
          </div>

          <!-- Start Date -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
            <input type="date" v-model="filters.start_date"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all" />
          </div>

          <!-- End Date -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
            <input type="date" v-model="filters.end_date"
              class="w-full p-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-all" />
          </div>

          <!-- Filter Button -->
          <div class="flex items-end">
            <button @click="fetchReports" type="button"
              class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg transform transition-all hover:scale-105">
              Apply Filters
            </button>
          </div>
        </div>

        <!-- Clear Filters and Export Buttons -->
        <div class="flex flex-wrap items-center justify-between gap-4">
          <button @click="clearFilters" type="button" class="text-sm text-gray-600 hover:text-gray-800 underline">
            Clear All Filters
          </button>

          <!-- Export Buttons -->
          <div class="flex gap-3">
            <button @click="exportToExcel" type="button" :disabled="data.length === 0"
              class="flex items-center gap-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-5 py-2.5 rounded-lg font-semibold shadow-md transform transition-all hover:scale-105">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Export to Excel
            </button>

            <button @click="exportToPDF" type="button" :disabled="data.length === 0"
              class="flex items-center gap-2 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-5 py-2.5 rounded-lg font-semibold shadow-md transform transition-all hover:scale-105">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              Export to PDF
            </button>
          </div>
        </div>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Total Amount Card -->
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-blue-100 text-sm font-medium mb-1">Total Amount</p>
              <h3 class="text-4xl font-bold">{{ formatCurrency(total) }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
              <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Total Records Card -->
        <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-purple-100 text-sm font-medium mb-1">Total Records</p>
              <h3 class="text-4xl font-bold">{{ data.length }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
              <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Categories -->
      <div v-if="topCategories.length > 0" class="bg-white rounded-2xl shadow-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Top Categories</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div v-for="(c, index) in topCategories" :key="index"
            class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">{{ c.category || 'Unknown' }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ formatCurrency(c.total_amount) }}</p>
              </div>
              <div class="text-3xl">
                {{ index === 0 ? '🥇' : index === 1 ? '🥈' : '🥉' }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Table -->
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-bold text-gray-800">Data Records</h2>
        </div>

        <ClipLoader v-if="loading" color="#f59e0b" size="50px" class="m-6 text-center items-center" />

        <div v-else-if="data.length === 0" class="p-12 text-center">
          <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-xl text-gray-600">No records found</p>
          <p class="text-sm text-gray-500 mt-2">Try adjusting your filters</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Category / Type</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Amount</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Payment Mode</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="item in data" :key="item.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-900">{{ item.id }}</td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    {{ item.category?.name || item.donation_type?.name || 'Unknown' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                  {{ formatCurrency(item.amount) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  <span v-if="item.payment_mode" :class="{
                    'bg-green-100 text-green-800': item.payment_mode === 'Cash',
                    'bg-purple-100 text-purple-800': item.payment_mode === 'Bank Transfer',
                    'bg-blue-100 text-blue-800': item.payment_mode === 'Debit Card',
                    'bg-yellow-100 text-yellow-800': item.payment_mode === 'Mobile Wallet',
                    'bg-red-100 text-red-800': item.payment_mode === 'Credit Card',
                  }" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium capitalize">
                    {{ item.payment_mode }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  {{ formatDate(item.expense_date || item.donation_date) }}
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
import { ref, onMounted } from 'vue';
import api from '../../../Api/AxiosBase';
import * as XLSX from 'xlsx';
import { jsPDF } from 'jspdf';
import autoTable from 'jspdf-autotable';

const filters = ref({
  category_id: '',
  payment_mode: '',
  donation_type: '',
  start_date: '',
  end_date: ''
});

const data = ref([]);
const total = ref(0);
const topCategories = ref([]);
const categories = ref([]);
const donationTypes = ref([]);
const loading = ref(false);

const payment_mode = ref([
  { id: 1, name: 'Cash' },
  { id: 2, name: 'Bank Transfer' },
  { id: 3, name: 'Credit Card' },
  { id: 4, name: 'Debit Card' },
  { id: 5, name: 'Mobile Wallet' }
]);

// Format currency
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-PK', {
    style: 'currency',
    currency: 'PKR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount || 0);
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

// Clear all filters
const clearFilters = () => {
  filters.value = {
    category_id: '',
    payment_mode: '',
    donation_type: '',
    start_date: '',
    end_date: ''
  };
  fetchReports();
};

// Export to Excel
const exportToExcel = () => {
  if (data.value.length === 0) return;

  // Prepare data for export
  const exportData = data.value.map(item => ({
    'ID': item.id,
    'Category/Type': item.category?.name || item.donation_type?.name || 'Unknown',
    'Amount (PKR)': item.amount,
    'Payment Mode': item.payment_mode || '-',
    'Date': formatDate(item.expense_date || item.donation_date)
  }));

  // Add summary row
  exportData.push({});
  exportData.push({
    'ID': 'TOTAL',
    'Category/Type': '',
    'Amount (PKR)': total.value,
    'Payment Mode': '',
    'Date': ''
  });

  // Create workbook and worksheet
  const ws = XLSX.utils.json_to_sheet(exportData);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Reports');

  // Set column widths
  ws['!cols'] = [
    { wch: 10 },
    { wch: 25 },
    { wch: 15 },
    { wch: 20 },
    { wch: 15 }
  ];

  // Generate filename with date
  const filename = `reports_${new Date().toISOString().split('T')[0]}.xlsx`;

  // Save file
  XLSX.writeFile(wb, filename);
};

// Export to PDF
const exportToPDF = () => {
  if (data.value.length === 0) return;

  const doc = new jsPDF();

  // Add title
  doc.setFontSize(18);
  doc.text('Financial Reports', 14, 20);

  // Add date range if filters are applied
  let yPos = 30;
  if (filters.value.start_date || filters.value.end_date) {
    doc.setFontSize(10);
    const dateRange = `Date Range: ${filters.value.start_date || 'Start'} to ${filters.value.end_date || 'End'}`;
    doc.text(dateRange, 14, yPos);
    yPos += 10;
  }

  // Prepare table data
  const tableData = data.value.map(item => [
    item.id,
    item.category?.name || item.donation_type?.name || 'Unknown',
    formatCurrency(item.amount),
    item.payment_mode || '-',
    formatDate(item.expense_date || item.donation_date)
  ]);

  // Add table using autoTable
  autoTable(doc, {
    head: [['ID', 'Category/Type', 'Amount', 'Payment Mode', 'Date']],
    body: tableData,
    startY: yPos,
    theme: 'grid',
    styles: {
      fontSize: 9,
      cellPadding: 3
    },
    headStyles: {
      fillColor: [59, 130, 246],
      textColor: 255,
      fontStyle: 'bold'
    },
    columnStyles: {
      2: { halign: 'right' }
    }
  });

  // Add summary
  const finalY = doc.lastAutoTable.finalY + 10;
  doc.setFontSize(12);
  doc.setFont(undefined, 'bold');
  doc.text(`Total Amount: ${formatCurrency(total.value)}`, 14, finalY);
  doc.text(`Total Records: ${data.value.length}`, 14, finalY + 8);

  // Generate filename with date
  const filename = `reports_${new Date().toISOString().split('T')[0]}.pdf`;

  // Save file
  doc.save(filename);
};

// Fetch categories and donation types on mount
onMounted(async () => {
  try {
    const catRes = await api.get('/categories');
    categories.value = catRes.data;

    const donationRes = await api.get('/donation-types');
    donationTypes.value = donationRes.data;

    // Load initial data
    fetchReports();
  } catch (error) {
    console.error('Error loading initial data:', error);
  }
});

// Fetch reports with filters
const fetchReports = async () => {
  loading.value = true;
  try {
    const query = Object.entries(filters.value)
      .filter(([key, value]) => value)
      .map(([key, value]) => `${key}=${value}`)
      .join('&');

    const res = await api.get(`/reports/filter?${query}`);
    data.value = res.data.data || [];
    total.value = res.data.total || 0;
    topCategories.value = res.data.top_categories || [];
  } catch (error) {
    console.error('Error fetching reports:', error);
    data.value = [];
    total.value = 0;
    topCategories.value = [];
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Custom scrollbar for table */
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