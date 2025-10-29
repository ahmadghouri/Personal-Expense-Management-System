<script setup>
import { onMounted, ref, computed } from 'vue'
import api from '../../Api/AxiosBase'
import DonationShow from '../../components/admin/donation/DonationShow.vue'
import DonationCreate from '../../components/admin/donation/DonationCreate.vue'

const showModal = ref(false)
const editMode = ref(false)
const selectedDonation = ref(null)
const isLoadind = ref(false);

const donationTypes = ref([])
const showFilters = ref(false)
const donations = ref([])
const search = ref('')

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10
})

const filters = ref({
  type: '',
  dateFrom: '',
  dateTo: '',
  minAmount: '',
  maxAmount: ''
})

// ✅ Fetch donation types
const handleDonationTypes = async () => {
  try {
    const response = await api.get('donation-types')
    donationTypes.value = response.data
  } catch (error) {
    console.error('Error fetching donation types:', error)
  }
}

const handleCategory = async () => {
  try {
    const response = await api.get('categories')
    categorys.value = response.data
  } catch (error) {
    console.log(error)
  }
}

const categorys = ref([])

// ✅ Fetch donations
const handleDonations = async (page = 1) => {
  try {
    isLoadind.value = true;
    const response = await api.get(`donations?page=${page}&per_page=${pagination.value.per_page}`)
    donations.value = response.data.donations?.data || []

    pagination.value = {
      current_page: response.data.donations?.current_page || 1,
      last_page: response.data.donations?.last_page || 1,
      per_page: response.data.donations?.per_page || 10
    }
    isLoadind.value = false;
  } catch (error) {
    console.error('Error fetching donations:', error)
  }
}

// ✅ Handle edit trigger
const handleEditDonation = (donation) => {
  selectedDonation.value = donation
  editMode.value = true
  showModal.value = true
}

// ✅ Filtered Donations
const filteredDonations = computed(() => {
  if (!Array.isArray(donations.value)) return []

  return donations.value.filter((donation) => {
    const searchQuery = search.value.toLowerCase()

    const matchesSearch =
      donation.recipient_name?.toLowerCase().includes(searchQuery) ||
      donation.description?.toLowerCase().includes(searchQuery) ||
      donation.donation_type?.name?.toLowerCase().includes(searchQuery) ||
      donation.user?.name?.toLowerCase().includes(searchQuery)

    const matchesType =
      !filters.value.type || donation.donation_type?.name === filters.value.type

    const matchesMin =
      !filters.value.minAmount || parseFloat(donation.amount) >= parseFloat(filters.value.minAmount)

    const matchesMax =
      !filters.value.maxAmount || parseFloat(donation.amount) <= parseFloat(filters.value.maxAmount)

    const donationDate = new Date(donation.donation_date)
    const matchesDateFrom =
      !filters.value.dateFrom || donationDate >= new Date(filters.value.dateFrom)

    const matchesDateTo =
      !filters.value.dateTo || donationDate <= new Date(filters.value.dateTo)

    return (
      matchesSearch &&
      matchesType &&
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
    type: '',
    dateFrom: '',
    dateTo: '',
    minAmount: '',
    maxAmount: ''
  }
  search.value = ''
}

// ✅ Reset modal state
const closeModal = () => {
  showModal.value = false
  editMode.value = false
  selectedDonation.value = null
}

onMounted(() => {
  handleDonationTypes()
  handleDonations()
  handleCategory()
})
</script>

<template>
  <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
      <h1 class="text-3xl font-bold text-slate-800">Donation Management</h1>

      <button @click="showModal = true"
        class="flex items-center gap-2 bg-amber-600 hover:bg-amber-500 text-white px-6 py-3 rounded-lg font-medium transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Donation
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
          <input v-model="search" type="text" placeholder="Search Donation..."
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
            <label class="block text-sm font-medium text-slate-700 mb-2">Donation Type</label>
            <select v-model="filters.type"
              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
              <option value="">All Types</option>
              <option v-for="type in donationTypes" :key="type.id" :value="type.name">
                {{ type.name }}
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

  <!-- ✅ Donation List -->
  <DonationShow :donations="filteredDonations" :isLoadind="isLoadind" :handleDonations="handleDonations" :pagination="pagination"
    @page-change="handleDonations" @edit-donation="handleEditDonation" />

  <!-- ✅ Create / Edit Modal -->
  <DonationCreate v-if="showModal" :categorys="categorys" :handleDonations="handleDonations" :showModal="showModal" :editMode="editMode"
    :donationToEdit="selectedDonation" :donationTypes="donationTypes" @close="closeModal" />
</template>
