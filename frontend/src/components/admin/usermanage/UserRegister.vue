
<script setup>
import { ref, watch } from 'vue';
import api from '../../../Api/AxiosBase';
import { useToast } from 'vue-toastification';
const toast = useToast();

const props = defineProps({
    user: {
        type: Object,
        default: null
    },
    allUser: {
        type: Function,
        required: false
    }
});

const emit = defineEmits(['close', 'update']);

const name = ref('');
const email = ref('');
const password = ref('');
const roleOptions = ['admin', 'manager'];
const role = ref('manager');

// Prefill data when editing
watch(() => props.user, (newUser) => {
    if (newUser) {
        name.value = newUser.name || '';
        email.value = newUser.email || '';
        role.value = newUser.role || 'manager';
        password.value = ''; // hide old password
    } else {
        name.value = '';
        email.value = '';
        password.value = '';
        role.value = 'manager';
    }
}, { immediate: true });

async function submitForm() {
    try {
        if (props.user) {
            // ✏️ Update mode
            await api.put(`/admin/users/${props.user.id}`, {
                name: name.value,
                email: email.value,
                role: role.value,
            });
            toast.success('User updated successfully');
            props.allUser?.()
            closeModal();
        } else {
            // 🆕 Create mode
            await api.post('/register', {
                name: name.value,
                email: email.value,
                password: password.value,
                role: role.value
            });
            toast.success('User registered successfully');
            props.allUser?.()
            closeModal();
        }

    } catch (error) {
        console.error(error);
        toast.error('Error saving user');
    }
}

function closeModal() {
    emit('close');
}
</script>


<template>
    <transition name="fade">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-white/30 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">
                <!-- Close Button -->
                <button @click="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="pi pi-times text-xl"></i>
                </button>

                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
                    {{ props.user ? '✏️ Edit User' : '👤 Create New User' }}
                </h2>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input v-model="name" type="text" placeholder="Enter full name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="email" type="email" placeholder="Enter email address"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:outline-none" />
                    </div>
                    <!-- Password Field -->
                    <div v-if="!props.user" class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input v-model="password" type="password" placeholder="Enter password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select v-model="role"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:outline-none">
                            <option v-for="option in roleOptions" :key="option" :value="option">
                                {{ option.charAt(0) + option.slice(1) }}
                            </option>
                        </select>
                    </div>

                    <div class="pt-3">
                        <button type="submit"
                            class="w-full bg-amber-600 text-white py-2.5 rounded-md hover:bg-amber-700 transition-all duration-200">
                            {{ props.user ? 'Update User' : 'Create User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.3s ease-out;
}
</style>
