<!-- <script setup>
import api from '../../../Api/AxiosBase';
import { useToast } from 'vue-toastification'
const toast = useToast()

const baseUrl = api.defaults.baseURL.replace('/api', '');

// ✅ Props
const props = defineProps({
  expenses: {
    type: Array,
    default: () => []
  },
  pagination: {
    type: Object,
    default: () => ({
      current_page: 1,
      last_page: 1,
      per_page: 10
    })
  },
  handleExpense: {
    type: Function,
    default: () => {}
  }
});

// ✅ Emits for pagination
const emit = defineEmits(['page-change']);

// Placeholder methods (you can connect them later)
const openEditModal = (expense) => {
  console.log('Edit clicked:', expense);
};

const deleteExpense = async(id) => {
  try {
    await api.delete(`expenses/${id}`)
    toast.success('Expense deleted successfully')
    props.handleExpense()
  } catch (error) {
    console.log(error);
    toast.error('Error deleting expense')
  }
};
</script> -->

<script setup>
import api from '../../../Api/AxiosBase'
import { useToast } from 'vue-toastification'
const toast = useToast()

const baseUrl = api.defaults.baseURL.replace('/api', '')

// ✅ Props
const props = defineProps({
  expenses: {
    type: Array,
    default: () => []
  },
  pagination: {
    type: Object,
    default: () => ({
      current_page: 1,
      last_page: 1,
      per_page: 10
    })
  },
  handleExpense: {
    type: Function,
    default: () => {}
  }
})

// ✅ Emits for pagination and edit
const emit = defineEmits(['page-change', 'edit-expense'])

// ✅ Trigger edit modal with selected expense
const openEditModal = (expense) => {
  emit('edit-expense', expense)
}

// ✅ Delete expense
const deleteExpense = async (id) => {
  try {
    await api.delete(`expenses/${id}`)
    toast.success('Expense deleted successfully')
    props.handleExpense()
  } catch (error) {
    console.error(error)
    toast.error('Error deleting expense')
  }
}
</script>


<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden border border-slate-200">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
          <tr>
            <th class="px-4 py-3 text-left font-semibold">Date</th>
            <th class="px-4 py-3 text-left font-semibold">Category</th>
            <th class="px-4 py-3 text-left font-semibold">Description</th>
            <!-- <th class="px-4 py-3 text-left font-semibold">User</th> -->
            <th class="px-4 py-3 text-left font-semibold">Payment Mode</th>
            <th class="px-4 py-3 text-right font-semibold">Amount</th>
            <th class="px-4 py-3 text-center font-semibold">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">
          <tr
            v-for="expense in props.expenses"
            :key="expense.id"
            class="hover:bg-slate-50 transition"
          >
            <td class="px-4 py-3 text-slate-600">
              {{ new Date(expense.expense_date).toLocaleDateString() }}
            </td>

            <td class="px-4 py-3">
              <div class="text-slate-900 font-medium">{{ expense.category.name }}</div>
              <div v-if="expense.subcategory" class="text-xs text-slate-500">
                {{ expense.subcategory }}
              </div>
            </td>

            <td class="px-4 py-3 text-slate-600">
              {{ expense.description || '-' }}
            </td>

            <!-- <td class="px-4 py-3">
              <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                {{ expense.user.name }}
              </span>
            </td> -->

            <td class="px-4 py-3">
              <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                {{ expense.payment_mode }}
              </span>
              <div v-if="expense.attachment" class="text-xs text-blue-500 mt-1 pl-2">
                <a :href="`${baseUrl}/${expense.attachment}`" target="_blank">Attachment</a>
              </div>
            </td>

            <td class="px-4 py-3 text-right font-semibold text-slate-900">
              Rs. {{ expense.amount.toLocaleString() }}
            </td>

            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <button
                  @click="openEditModal(expense)"
                  class="p-2 rounded-md text-blue-600 hover:bg-blue-100 transition"
                  title="Edit"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>

                <button
                  @click="deleteExpense(expense.id)"
                  class="p-2 rounded-md text-red-600 hover:bg-red-100 transition"
                  title="Delete"
                >
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
    <div class="flex flex-wrap justify-between items-center px-6 py-4 border-t border-slate-200 bg-slate-50">
      <div class="text-sm text-slate-600">
        Page {{ props.pagination.current_page }} of {{ props.pagination.last_page }}
      </div>

      <div class="flex gap-2 mt-2 sm:mt-0">
        <button
          @click="emit('page-change', props.pagination.current_page - 1)"
          :disabled="props.pagination.current_page === 1"
          class="px-4 py-2 text-sm rounded-md border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Previous
        </button>

        <button
          @click="emit('page-change', props.pagination.current_page + 1)"
          :disabled="props.pagination.current_page === props.pagination.last_page"
          class="px-4 py-2 text-sm rounded-md border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

