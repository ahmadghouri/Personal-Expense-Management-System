<script setup>
import { DeprecationTypes, ref } from 'vue';
import api from '../../../Api/AxiosBase';
import { useToast } from 'vue-toastification'
import ClipLoader from '../../ClipLoader.vue';
import DonationTypeCreate from './DonationTypeCreate.vue';
// import CategoryCreate from './CategoryCreate.vue';
const toast = useToast()

const showModal = ref(false);
const selectedUser = ref(null);

const props = defineProps({
    donationTypes: {
        type: Array,
        default: () => []
    },
    handleDonationType: {
        type: Function,
    },
    isLoadind: {
        type: Boolean,
        default: false
    }
})

function handleEdit(user) {
    selectedUser.value = { ...user };
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    selectedUser.value = null;
}

const handleDelete = async ($id) => {
    try {

        await api.delete(`donation-types/${$id}`)
        props.handleDonationType()
        toast.success('User deleted successfully')

    } catch (error) {
        console.log(error);
        toast.error('Error deleting user')
    }
    finally {

    }
}
// function to format amount
function formatAmount(amount) {
    if (!amount) return '0.00'
    return Number(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })
}
</script>

<template>
  <div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-2xl shadow-md border border-slate-200">
    <div class="overflow-x-auto rounded-xl">
      <ClipLoader v-if="props.isLoadind" color="#f59e0b" size="50px" class="m-6" />

      <table class="min-w-full text-sm text-left">
        <thead class="bg-slate-50 text-slate-700 border-b border-slate-200 uppercase text-xs">
          <tr>
            <th class="px-6 py-3 font-semibold">ID</th>
            <th class="px-6 py-3 font-semibold">Name</th>
            <th class="px-6 py-3 font-semibold">Description</th>
            <th class="px-6 py-3 font-semibold">Donations</th>
            <th class="px-6 py-3 font-semibold text-right">Total Amount</th>
            <th class="px-6 py-3 text-center font-semibold">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">
          <tr
            v-for="donationType in donationTypes"
            :key="donationType.id"
            class="hover:bg-slate-50 transition-colors duration-200"
          >
            <td class="px-6 py-4 font-medium text-slate-800">
              {{ donationType.id }}
            </td>

            <td class="px-6 py-4 text-slate-900 font-semibold">
              {{ donationType.name }}
            </td>

            <td class="px-6 py-4 text-slate-600">
              {{ donationType.description || '—' }}
            </td>

            <td class="px-6 py-4">
              <span
                class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700"
              >
                {{ donationType.donations_count }}
              </span>
            </td>

            <td class="px-6 py-4 text-right">
              <span
                class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                :class="{
                  'bg-green-50 text-green-700': Number(donationType.donations_sum_amount) > 0,
                  'bg-slate-100 text-slate-500': !donationType.donations_sum_amount
                }"
              >
                {{
                  Number(donationType.donations_sum_amount) > 0
                    ? `Rs. ${Number(donationType.donations_sum_amount).toLocaleString()}`
                    : '—'
                }}
              </span>
            </td>

            <td class="px-6 py-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <!-- Edit -->
                <button
                  @click="handleEdit(donationType)"
                  class="p-2 rounded-md text-blue-600 hover:bg-blue-100 transition"
                  title="Edit"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                  </svg>
                </button>

                <!-- Delete -->
                <button
                  @click="handleDelete(donationType.id)"
                  class="p-2 rounded-md text-red-600 hover:bg-red-100 transition"
                  title="Delete"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <DonationTypeCreate
      v-if="showModal"
      :donationType="selectedUser"
      @close="closeModal"
      :handleDonationType="props.handleDonationType"
    />
  </div>
</template>

