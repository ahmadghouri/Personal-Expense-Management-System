
<script setup>
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../../Api/AxiosBase'
import { useToast } from 'vue-toastification'

const toast = useToast()
const router = useRouter()

const props = defineProps({
  showModal: { type: Boolean, default: false },
  editMode: { type: Boolean, default: false },
  categorys: { type: Array, default: () => [] },
  handleExpense: { type: Function, default: () => { } },
  expenseToEdit: { type: Object, default: null }
})

const emit = defineEmits(['close'])
const closeModal = () => emit('close')

function getTodayDate() {
  const today = new Date()
  const yyyy = today.getFullYear()
  const mm = String(today.getMonth() + 1).padStart(2, "0")
  const dd = String(today.getDate()).padStart(2, "0")
  return `${yyyy}-${mm}-${dd}`
}
const expenseForm = ref({
  category_id: '',
  subcategory: '',
  expense_date: getTodayDate(),
  amount: '',
  payment_mode: '',
  description: '',
  attachment: null
})

watch(
  () => props.expenseToEdit,
  (newVal) => {
    if (props.editMode && newVal) {
      expenseForm.value = {
        category_id: String(newVal.category_id ?? ''),
        subcategory: newVal.subcategory ?? '',
        expense_date: newVal.expense_date ?? '',
        amount: newVal.amount ?? '',
        payment_mode: newVal.payment_mode ?? '',
        description: newVal.description ?? '',
        attachment: null
      }
    }
  },
  { immediate: true }
)

const handleFileUpload = (e) => {
  const file = e.target.files[0]
  expenseForm.value.attachment = file instanceof File ? file : null
}

const handleSubmitExpense = async () => {
  try {
    const formData = new FormData()

    for (const key in expenseForm.value) {
      if (key !== 'attachment') {
        formData.append(key, expenseForm.value[key] ?? '')
      }
    }

    if (expenseForm.value.attachment instanceof File) {
      formData.append('attachment', expenseForm.value.attachment)
    }

    const endpoint = props.editMode && props.expenseToEdit?.id
      ? `/expenses/${props.expenseToEdit.id}?_method=PUT`
      : '/expenses'

    await api.post(endpoint, formData)

    toast.success(props.editMode ? 'Expense updated successfully' : 'Expense created successfully')
    closeModal()
    props.handleExpense()
  } catch (error) {
    if (error.response?.status === 422) {
      console.error('Validation errors:', error.response.data.errors)
    } else {
      console.error('Error saving expense:', error)
      toast.error('Error saving expense')
    }
  }
}

watch(() => expenseForm.value.category_id, (val) => {
  const selected = props.categorys.find(c => c.id === Number(val))
  if (selected?.name === 'Donation') {
    router.push('/manager/donation')
    closeModal()
  }
})

const payment_mode = ref([
  { id: 1, name: 'Cash' },
  { id: 2, name: 'Bank Transfer' },
  { id: 3, name: 'Credit Card' },
  { id: 4, name: 'Debit Card' },
  { id: 5, name: 'Mobile Wallet' }
])

</script>


<template>
  <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    @click.self="closeModal">
    <div
      class="bg-white rounded-2xl shadow-xl w-full sm:max-w-3xl max-h-[90vh] overflow-y-auto border border-slate-200">
      <!-- Header -->
      <div class="sticky top-0 z-10 bg-white flex items-center justify-between px-6 py-4 border-b border-slate-200">
        <h2 class="text-xl sm:text-2xl font-semibold text-slate-800">
          {{ editMode ? 'Edit Expense' : 'Add New Expense' }}
        </h2>
        <button @click="closeModal" class="p-2 rounded-lg hover:bg-slate-100 transition">
          <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Form Body -->
      <div class="px-6 py-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <!-- Category -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
              Category <span class="text-red-500">*</span>
            </label>
            <select v-model="expenseForm.category_id" required
              class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Select Category</option>
              <option v-for="cat in categorys" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>

          <!-- Subcategory -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1"> Subcategory </label>
            <input v-model="expenseForm.subcategory" type="text" placeholder="e.g., Groceries, Fuel"
              class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <!-- Expense Date -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
              Expense Date <span class="text-red-500">*</span>
            </label>
            <input v-model="expenseForm.expense_date" type="date" required
              class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <!-- Amount -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
              Amount (Rs.) <span class="text-red-500">*</span>
            </label>
            <input v-model="expenseForm.amount" type="number" placeholder="0.00" min="0" step="0.01" required
              class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <!-- Payment Mode -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
              Payment Mode <span class="text-red-500">*</span>
            </label>
            <select v-model="expenseForm.payment_mode" required
              class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Select Mode</option>
              <option v-for="mode in payment_mode" :key="mode.id" :value="mode.name">
                {{ mode.name }}
              </option>

            </select>
          </div>

          <!-- Attachment -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1"> Attachment </label>
            <input @change="handleFileUpload" type="file" accept=".jpg,.jpeg,.png,.pdf"
              class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <p class="text-xs text-slate-500 mt-1"> JPG, PNG, PDF (max 2MB) </p>
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1"> Description </label>
          <textarea v-model="expenseForm.description" rows="3" placeholder="Add any notes about this expense..."
            class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:justify-end gap-4 pt-2">
          <button @click="closeModal" type="button"
            class="w-full sm:w-auto px-6 py-3 border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-100 transition">
            Cancel
          </button>
          <button @click="handleSubmitExpense" type="button"
            class="w-full sm:w-auto px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white rounded-lg font-medium transition">
            {{ editMode ? 'Update Expense' : 'Add Expense' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
