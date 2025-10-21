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
    <div>
        <div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-2xl shadow-lg">
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <ClipLoader v-if="props.isLoadind" color="#f59e0b" size="50px" />
                <table class="min-w-full bg-white text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Description</th>
                            <th class="px-6 py-3">Donations</th>
                            <th class="px-6 py-3">Total Amount</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="donationType in donationTypes" :key="donationType.id"
                            class="hover:bg-indigo-50 transition-colors duration-200">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ donationType.id }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ donationType.name }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ donationType.description || 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-600 ">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-500 text-white">
                                    {{ donationType.donations_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold" :class="{
                                    'bg-green-500 text-white': Number(donationType.donations_sum_amount) === 0,
                                    'bg-yellow-500 text-white': Number(donationType.donations_sum_amount) > 0,
                                    'bg-gray-400 text-white': donationType.donations_sum_amount == null
                                }">
                                    {{ Number(donationType.donations_sum_amount) > 0 ?
                                        formatAmount(donationType.donations_sum_amount) :
                                        'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="handleEdit(donationType)"
                                    class="text-gray-400 hover:text-gray-600 font-medium">
                                    Edit
                                </button>
                                <span class="mx-2 text-gray-400">|</span>
                                <button class="text-red-500 hover:text-red-700 font-medium"
                                    @click="handleDelete(donationType.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <DonationTypeCreate v-if="showModal" :donationType="selectedUser" @close="closeModal"
            :handleDonationType="props.handleDonationType" />
    </div>
</template>
