<script setup>
import { ref } from 'vue';
import api from '../../../Api/AxiosBase';
import { useToast } from 'vue-toastification'
import ClipLoader from '../../ClipLoader.vue';
import CategoryCreate from './CategoryCreate.vue';
const toast = useToast()

const showModal = ref(false);
const selectedUser = ref(null);

const props = defineProps({
    categorys: {
        type: Array,
        default: () => []
    },
    handleCategory: {
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

        await api.delete(`/categories/${$id}`)
        props.handleCategory()
        toast.success('User deleted successfully')

    } catch (error) {
        console.log(error);
        toast.error('Error deleting user')
    }
    finally {

    }
}
</script>

<template>
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-slate-200 mt-10">
        <div class="overflow-x-auto">
            <ClipLoader v-if="props.isLoadind" color="#f59e0b" size="50px" class="m-6" />

            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">ID</th>
                        <th class="px-4 py-3 text-left font-semibold">Name</th>
                        <th class="px-4 py-3 text-left font-semibold">Description</th>
                        <th class="px-4 py-3 text-left font-semibold">Expenses</th>
                        <th class="px-4 py-3 text-right font-semibold">Total Amount</th>
                        <th class="px-4 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <tr v-for="category in categorys" :key="category.id" class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-slate-600 font-medium">
                            {{ category.id }}
                        </td>

                        <td class="px-4 py-3 text-slate-900 font-semibold">
                            {{ category.name }}
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ category.description || '—' }}
                        </td>

                        <td class="px-4 py-3">
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-gray-500 text-white">
                                {{ category.expenses_count }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" :class="{
                                'bg-amber-500 text-white': Number(category.expenses_sum_amount) > 0,
                                'bg-gray-400 text-white': !category.expenses_sum_amount
                            }">
                                {{ Number(category.expenses_sum_amount) > 0 ?
                                    `Rs. ${Number(category.expenses_sum_amount).toLocaleString()}` :
                                '—' }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <!-- Edit -->
                                <button @click="handleEdit(category)"
                                    class="p-2 rounded-md text-blue-600 hover:bg-blue-100 transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <!-- Delete -->
                                <button @click="handleDelete(category.id)"
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

        <!-- Modal -->
        <CategoryCreate v-if="showModal" :category="selectedUser" @close="closeModal"
            :handleCategory="props.handleCategory" />
    </div>
</template>
