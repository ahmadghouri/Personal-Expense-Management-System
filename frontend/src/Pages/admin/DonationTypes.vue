<script setup>
import { computed, onMounted, ref } from 'vue';
import api from '../../Api/AxiosBase';
import DonationTypeShow from '../../components/admin/donationtype/DonationTypeShow.vue';
import DonationTypeCreate from '../../components/admin/donationtype/DonationTypeCreate.vue';

const showModal = ref(false);
const isLoadind = ref(false);
const selectedUser = ref(null);

function openModal() {
    showModal.value = true;
}
const allDonationTypes = ref([]);

const searchQuery = ref('');
const handleDonationType = async () => {
    try {
        isLoadind.value = true;
        const response = await api.get('donation-types/donations');
        allDonationTypes.value = response.data;
    } catch (error) {
        console.log(error);
    }
    finally {
        isLoadind.value = false;
    }
}
onMounted(() => {
    handleDonationType();
});

const filterDonationTypes = computed(() => {
    const query = searchQuery.value.toLowerCase();
    return allDonationTypes.value.filter(donationType =>
        donationType.name.toLowerCase().includes(query)
    )
})
function handleUpdate(updatedUser) {
    const index = allDonationTypes.value.findIndex(u => u.id === updatedUser.id);
    if (index !== -1) {
        allDonationTypes.value[index] = updatedUser;
    }
    handleDonationType();
    showModal.value = false;
}

</script>

<template>
    <!-- Top Bar -->
    <div
        class="flex flex-col md:flex-row justify-between items-center bg-white p-4 shadow-md rounded-xl mt-6 gap-4 border border-gray-100">
        <!-- Search Input -->
        <div class="relative w-full max-w-md">
            <input type="text" placeholder="Search Donation Type" v-model="searchQuery"
                class="w-full h-11 pl-4 pr-11 border border-gray-300 rounded-lg text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition" />
            <!-- SVG Search Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor"
                class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <!-- Create Button -->
        <button @click="openModal"
            class="flex items-center justify-center gap-2 bg-amber-600 text-white px-5 py-2.5 rounded-lg hover:bg-amber-500 active:bg-amber-700 transition-all duration-200 shadow-sm">
            <!-- SVG Plus Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-5 h-5 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>

            <span class="text-sm font-medium">Create Donation Type</span>
        </button>
    </div>


    <!-- Modal -->
    <DonationTypeCreate v-if="showModal" :donationType="selectedUser" @close="showModal = false" @update="handleUpdate"
        :handleDonationType="handleDonationType" />

    <!-- Donation Type List -->
    <DonationTypeShow :donationTypes="filterDonationTypes" :handleDonationType="handleDonationType"
        :isLoadind="isLoadind" />
</template>
