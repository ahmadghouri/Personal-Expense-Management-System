<script setup>
import { onMounted, ref, watch } from 'vue'
import api from '../../../Api/AxiosBase'
import { useToast } from 'vue-toastification'

const toast = useToast()

const props = defineProps({
  showModal: { type: Boolean, default: false },
  editMode: { type: Boolean, default: false },
  handleDonations: { type: Function, default: () => {} },
  donationToEdit: { type: Object, default: null },
  donationTypes: { type: Array, default: () => [] },
  categorys: { type: Array, default: () => [] }
})

const emit = defineEmits(['close'])
const closeModal = () => emit('close')

// ✅ Function for current date
function getTodayDate() {
  const today = new Date()
  const yyyy = today.getFullYear()
  const mm = String(today.getMonth() + 1).padStart(2, "0")
  const dd = String(today.getDate()).padStart(2, "0")
  return `${yyyy}-${mm}-${dd}`
}

// ✅ Reactive form data
const donationForm = ref({
  category_id: '',
  donation_type_id: '',
  donation_date: getTodayDate(),
  amount: '',
  recipient_name: '',
  description: '',
  proof_attachment: null
})

// ✅ Automatically set “Donation” category on mount
onMounted(() => {
  const donationCategory = props.categorys.find(c => c.name === 'Donation')
  if (donationCategory) {
    donationForm.value.category_id = donationCategory.id
  }
})

// ✅ Watch for edit data and prefill
watch(
  () => props.donationToEdit,
  (newVal) => {
    if (props.editMode && newVal) {
      donationForm.value = {
        category_id: newVal.category_id ?? '',
        donation_type_id: newVal.donation_type_id ?? '',
        donation_date: newVal.donation_date ?? getTodayDate(),
        amount: newVal.amount ?? '',
        recipient_name: newVal.recipient_name ?? '',
        description: newVal.description ?? '',
        proof_attachment: null
      }
    }
  },
  { immediate: true }
)

// ✅ File upload handler
const handleFileUpload = (e) => {
  const file = e.target.files[0]
  donationForm.value.proof_attachment = file instanceof File ? file : null
}

// ✅ Submit form (create or update)
const handleSubmitDonation = async () => {
  try {
    const formData = new FormData()
    formData.append('category_id', donationForm.value.category_id)
    formData.append('donation_type_id', donationForm.value.donation_type_id)
    formData.append('donation_date', donationForm.value.donation_date)
    formData.append('amount', donationForm.value.amount)
    formData.append('recipient_name', donationForm.value.recipient_name)
    formData.append('description', donationForm.value.description)

    if (donationForm.value.proof_attachment) {
      formData.append('proof', donationForm.value.proof_attachment)
    }

    const endpoint =
      props.editMode && props.donationToEdit?.id
        ? `/donations/${props.donationToEdit.id}?_method=PUT`
        : '/donations'

    await api.post(endpoint, formData)

    toast.success(props.editMode ? 'Donation updated successfully' : 'Donation added successfully')
    closeModal()
    props.handleDonations()
  } catch (error) {
    if (error.response?.status === 422) {
      console.error('Validation errors:', error.response.data.errors)
      toast.error('Please fill required fields properly')
    } else {
      console.error('Error saving donation:', error)
      toast.error('Error saving donation')
    }
  }
}
</script>

<template>
  <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
       @click.self="closeModal">
    <div class="bg-white rounded-2xl shadow-xl w-full sm:max-w-3xl max-h-[90vh] overflow-y-auto border border-slate-200">
      <!-- Header -->
      <div class="sticky top-0 z-10 bg-white flex items-center justify-between px-6 py-4 border-b border-slate-200">
        <h2 class="text-xl sm:text-2xl font-semibold text-slate-800">
          {{ editMode ? 'Edit Donation' : 'Add New Donation' }}
        </h2>
        <button @click="closeModal" class="p-2 rounded-lg hover:bg-slate-100 transition">
          <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Form Body -->
      <div class="px-6 py-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <!-- ✅ Fixed Category (Donation only) -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
              Category <span class="text-red-500">*</span>
            </label>
            <select v-model="donationForm.category_id"
                    class="w-full px-4 py-2 rounded-lg border border-slate-300 bg-gray-100 cursor-not-allowed"
                    disabled>
              <option v-for="cat in categorys.filter(c => c.name === 'Donation')" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>

          <!-- Donation Type -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
              Donation Type <span class="text-red-500">*</span>
            </label>
            <select v-model="donationForm.donation_type_id" required
                    class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Select Type</option>
              <option v-for="type in donationTypes" :key="type.id" :value="type.id">
                {{ type.name }}
              </option>
            </select>
          </div>

          <!-- Date -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Donation Date <span class="text-red-500">*</span></label>
            <input v-model="donationForm.donation_date" type="date" required
                   class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <!-- Amount -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Amount / Value <span class="text-red-500">*</span></label>
            <input v-model="donationForm.amount" type="number" min="0" step="0.01" placeholder="0.00" required
                   class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <!-- Recipient -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Recipient Name</label>
            <input v-model="donationForm.recipient_name" type="text" placeholder="Enter recipient name" required
                   class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <!-- Proof Attachment -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Proof Attachment (optional)</label>
            <input @change="handleFileUpload" type="file" accept=".jpg,.jpeg,.png,.pdf"
                   class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <p class="text-xs text-slate-500 mt-1"> JPG, PNG, PDF (max 2MB) </p>
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Description / Purpose</label>
          <textarea v-model="donationForm.description" rows="3" placeholder="Write purpose or details..."
                    class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row sm:justify-end gap-4 pt-2">
          <button @click="closeModal" type="button"
                  class="w-full sm:w-auto px-6 py-3 border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-100 transition">
            Cancel
          </button>
          <button @click="handleSubmitDonation" type="button"
                  class="w-full sm:w-auto px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white rounded-lg font-medium transition">
            {{ editMode ? 'Update Donation' : 'Add Donation' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
