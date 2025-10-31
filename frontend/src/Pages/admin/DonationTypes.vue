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
    <div class="flex justify-between items-center bg-white p-4 shadow-md rounded-md mt-6">
        <!-- Search Input -->
        <div class="relative max-w-sm w-full">
            <input type="text" placeholder="Search Donation Type"
                class="w-full h-10 pl-4 pr-10 border rounded-md focus:outline-none focus:ring-amber-500"
                v-model="searchQuery" />
            <i class="pi pi-search absolute right-3 top-3 text-gray-500"></i>
        </div>

        <!-- Create Button -->
        <div class="flex items-center bg-amber-600 text-white px-6 py-2 rounded-md hover:bg-amber-500 transition cursor-pointer gap-2"
            @click="openModal">
            <i class="pi pi-plus text-sm"></i>
            <span>Create Donation Type</span>
        </div>
    </div>

    <DonationTypeCreate v-if="showModal" :donationType="selectedUser" @close="showModal = false" @update="handleUpdate"
        :handleDonationType="handleDonationType" />

    <DonationTypeShow :donationTypes="filterDonationTypes" :handleDonationType="handleDonationType" :isLoadind="isLoadind" />
</template>
