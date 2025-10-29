<script setup>
import { computed, onMounted, ref } from 'vue';
import UserShow from '../../components/admin/usermanage/UserShow.vue';
import UserRegister from '../../components/admin/usermanage/UserRegister.vue';
import api from '../../Api/AxiosBase';

const showModal = ref(false);
const isLoadind = ref(false);
const selectedUser = ref(null);

function openModal() {
    showModal.value = true;
}
const allUsers = ref([]);

const searchQuery = ref('');
const allUser = async () => {
    try {
        isLoadind.value = true;
        const response = await api.get('/admin/users');
        allUsers.value = response.data;
    } catch (error) {
        console.log(error);
    }
    finally{
        isLoadind.value = false;
    }
}
onMounted(() => {
    allUser();
});

const filterUser = computed(() => {
    const query = searchQuery.value.toLowerCase();
    return allUsers.value.filter(user =>
        user.name.toLowerCase().includes(query) ||
        user.email.toLowerCase().includes(query) ||
        user.role.toLowerCase().includes(query)
    )
})
function handleUpdate(updatedUser) {
    const index = allUsers.value.findIndex(u => u.id === updatedUser.id);
    if (index !== -1) {
        users.value[index] = updatedUser;
    }
    allUser();
    showModal.value = false;
}

</script>

<template>
    <!-- Top Bar -->
    <div class="flex justify-between items-center bg-white p-4 shadow-md rounded-md mt-6">
        <!-- Search Input -->
        <div class="relative max-w-sm w-full">
            <input type="text" placeholder="Search User"
                class="w-full h-10 pl-4 pr-10 border rounded-md focus:outline-none focus:ring-amber-500"
                v-model="searchQuery" />
            <i class="pi pi-search absolute right-3 top-3 text-gray-500"></i>
        </div>

        <!-- Create Button -->
        <div class="flex items-center bg-amber-600 text-white px-6 py-2 rounded-md hover:bg-amber-500 transition cursor-pointer gap-2"
            @click="openModal">
            <i class="pi pi-plus text-sm"></i>
            <span>Create User</span>
        </div>
    </div>

    <UserRegister v-if="showModal" :user="selectedUser" @close="showModal = false" @update="handleUpdate"
        :allUser="allUser" />

    <UserShow :users="filterUser" :allUser="allUser" :isLoadind="isLoadind" />
</template>
