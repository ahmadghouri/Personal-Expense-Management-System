<script setup>
import api from '../../../Api/AxiosBase'
import { useToast } from 'vue-toastification'
import { ref } from 'vue'
import ClipLoader from 'vue-spinner/src/ClipLoader.vue'

const toast = useToast()
const showPopup = ref(false)
const popupUrl = ref('')
const baseUrl = api.defaults.baseURL.replace('/api', '')

// ✅ Props
const props = defineProps({
  donations: { type: Array, default: () => [] },
  isLoadind: { type: Boolean, default: false },
  pagination: {
    type: Object,
    default: () => ({
      current_page: 1,
      last_page: 1,
      per_page: 10
    })
  },
  handleDonations: { type: Function, default: () => { } }
})

const emit = defineEmits(['page-change', 'edit-donation'])

const openEditModal = (donation) => emit('edit-donation', donation)

const deleteDonation = async (id) => {
  try {
    await api.delete(`donations/${id}`)
    toast.success('Donation deleted successfully')
    props.handleDonations()
  } catch (error) {
    console.error(error)
    toast.error('Error deleting donation')
  }
}

function openPopup(url) {
  popupUrl.value = url
  showPopup.value = true
}
function closePopup() {
  showPopup.value = false
}
</script>

<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden border border-slate-200">
    <div class="overflow-x-auto">
      <ClipLoader v-if="props.isLoadind" color="#f59e0b" size="50px" class="m-6" />
      <table class="min-w-full text-sm" v-else>
        <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
          <tr>
            <th class="px-4 py-3 text-left font-semibold">Date</th>
            <th class="px-4 py-3 text-left font-semibold">Donation Type</th>
            <th class="px-4 py-3 text-left font-semibold">Recipient</th>
            <th class="px-4 py-3 text-left font-semibold">Description</th>
            <th class="px-4 py-3 text-right font-semibold">Amount</th>
            <th class="px-4 py-3 text-left font-semibold">Proof</th>
            <th class="px-4 py-3 text-center font-semibold">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">
          <tr v-for="donation in props.donations" :key="donation.id" class="hover:bg-slate-50 transition">
            <td class="px-4 py-3 text-slate-600">
              {{ new Date(donation.donation_date).toLocaleDateString() }}
            </td>

            <td class="px-4 py-3 text-slate-900 font-medium">
              {{ donation.donation_type?.name || '—' }}
            </td>

            <td class="px-4 py-3">
              <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                {{ donation.recipient_name || '-' }}
              </span>
            </td>

            <td class="px-4 py-3 text-slate-600">
              {{ donation.description || '—' }}
            </td>

            <td class="px-4 py-3 text-right font-semibold text-slate-900">
              Rs. {{ parseFloat(donation.amount || 0).toLocaleString() }}
            </td>

            <td class="px-4 py-3 text-blue-600">
              <button v-if="donation.proof" @click="openPopup(`${baseUrl}/${donation.proof}`)"
                class="underline hover:text-blue-800 text-sm">
                View
              </button>
              <span v-else class="text-slate-400 text-sm">—</span>
            </td>

            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <button @click="openEditModal(donation)"
                  class="p-2 rounded-md text-blue-600 hover:bg-blue-100 transition" title="Edit">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>

                <button @click="deleteDonation(donation.id)"
                  class="p-2 rounded-md text-red-600 hover:bg-red-100 transition" title="Delete">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- ✅ Pagination -->
    <div class="flex flex-wrap justify-between items-center px-6 py-4 border-t border-slate-200 bg-slate-50"
      v-if="props.pagination?.last_page > 1">
      <div class="text-sm text-slate-600">
        Page {{ props.pagination?.current_page || 1 }} of {{ props.pagination?.last_page || 1 }}
      </div>

      <div class="flex gap-2 mt-2 sm:mt-0">
        <button @click="emit('page-change', props.pagination.current_page - 1)"
          :disabled="props.pagination.current_page === 1"
          class="px-4 py-2 text-sm rounded-md border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed">
          Previous
        </button>

        <button @click="emit('page-change', props.pagination.current_page + 1)"
          :disabled="props.pagination.current_page === props.pagination.last_page"
          class="px-4 py-2 text-sm rounded-md border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed">
          Next
        </button>
      </div>
    </div>

    <!-- ✅ Image Popup -->
    <div v-if="showPopup" class="fixed inset-0 bg-black/50  backdrop-blur-sm flex items-center justify-center z-50"
      @click.self="closePopup">
      <div class="relative p-3 rounded-xl">
        <button @click="closePopup"
          class="absolute top-2 right-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full w-8 h-8 flex items-center justify-center">
          ✕
        </button>
        <img :src="popupUrl" alt="Donation Proof" class="max-h-[80vh] max-w-[90vw] rounded-lg object-contain" />
      </div>
    </div>

  </div>
</template>
