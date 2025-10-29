<script setup>
import { computed, onMounted, ref } from 'vue';
import api from '../../Api/AxiosBase';
import CategoryShow from '../../components/admin/category/CategoryShow.vue';
import CategoryCreate from '../../components/admin/category/CategoryCreate.vue';

const showModal = ref(false);
const isLoadind = ref(false);
const selectedUser = ref(null);

function openModal() {
    showModal.value = true;
}
const allCategorys = ref([]);

const searchQuery = ref('');
const handleCategory = async () => {
    try {
        isLoadind.value = true;
        const response = await api.get('categories/expenses');
        allCategorys.value = response.data;
    } catch (error) {
        console.log(error);
    }
    finally {
        isLoadind.value = false;
    }
}
onMounted(() => {
    handleCategory();
});

const filterCategory = computed(() => {
    const query = searchQuery.value.toLowerCase();
    return allCategorys.value.filter(category =>
        category.name.toLowerCase().includes(query)
    )
})
function handleUpdate(updatedUser) {
    const index = allCategorys.value.findIndex(u => u.id === updatedUser.id);
    if (index !== -1) {
        allCategorys.value[index] = updatedUser;
    }
    handleCategory();
    showModal.value = false;
}

</script>

<template>
    <!-- Top Bar -->
    <div class="flex justify-between items-center bg-white p-4 shadow-md rounded-md mt-6">
        <!-- Search Input -->
        <div class="relative max-w-sm w-full">
            <input type="text" placeholder="Search Category"
                class="w-full h-10 pl-4 pr-10 border rounded-md focus:outline-none focus:ring-amber-500"
                v-model="searchQuery" />
            <i class="pi pi-search absolute right-3 top-3 text-gray-500"></i>
        </div>

        <!-- Create Button -->
        <div class="flex items-center bg-amber-600 text-white px-6 py-2 rounded-md hover:bg-amber-500 transition cursor-pointer gap-2"
            @click="openModal">
            <i class="pi pi-plus text-sm"></i>
            <span>Create Category</span>
        </div>
    </div>

    <!-- User Register -->
    <CategoryCreate v-if="showModal" :category="selectedUser" @close="showModal = false" @update="handleUpdate"
        :handleCategory="handleCategory" />

    <!-- User List -->
    <CategoryShow :categorys="filterCategory" :handleCategory="handleCategory" :isLoadind="isLoadind" />
</template>
