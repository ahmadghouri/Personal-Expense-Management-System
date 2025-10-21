<script setup>
import { ref } from 'vue';
import UserRegister from './UserRegister.vue';
import api from '../../../Api/AxiosBase';
import { useToast } from 'vue-toastification'
import ClipLoader from '../../ClipLoader.vue';
const toast = useToast()

const showModal = ref(false);
const selectedUser = ref(null);

const props = defineProps({
    users: {
        type: Array,
        default: () => []
    },
    allUser: {
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

        await api.delete(`/admin/users/${$id}`)
        props.allUser()
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
    <div>
        <div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-2xl shadow-lg">
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <ClipLoader v-if="props.isLoadind" color="#f59e0b" size="50px" />
                <table class="min-w-full bg-white text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">User ID</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="user in users" :key="user.id"
                            class="hover:bg-indigo-50 transition-colors duration-200">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ user.id }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ user.name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ user.email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold" :class="{
                                    'bg-gray-500 text-white': user.role === 'admin',
                                    'bg-amber-500 text-white': user.role === 'manager'
                                }">
                                    {{ user.role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="handleEdit(user)" class="text-gray-400 hover:text-gray-600 font-medium">
                                    Edit
                                </button>
                                <span class="mx-2 text-gray-400">|</span>
                                <button class="text-red-500 hover:text-red-700 font-medium"
                                    @click="handleDelete(user.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <UserRegister v-if="showModal" :user="selectedUser" @close="closeModal" :allUser="props.allUser" />
    </div>
</template>
