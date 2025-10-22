<script setup>
import { onMounted, ref } from 'vue';
import api from '../../../Api/AxiosBase';

const expenses = ref(null)
const handleExpense = async () =>{
    try {
        const response = await api.get('expenses')
        expenses.value = response.data.expenses;
    } catch (error) {
        console.log(error);
    }
}
        console.log(expenses.value);

onMounted(() => {
    handleExpense();
})
</script>

<template>
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
              <!-- <tr v-if="paginatedExpenses.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                  No expenses found. Add your first expense!
                </td>
              </tr> -->
              <tr
                v-for="expense in expenses"
                :key="expense.id"
                class="hover:bg-slate-50 transition-colors"
              >
                <td class="px-6 py-4 text-sm text-slate-600">
                  {{ new Date(expense.expense_date).toLocaleDateString() }}
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
        
      </div>

</template>