
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
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative animate-fade-in-up">
        <!-- Close Button -->
        <button
          @click="closeModal"
          class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors"
          aria-label="Close modal"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
          {{ props.user ? '✏️ Edit User' : '👤 Create New User' }}
        </h2>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-4">
          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input
              v-model="name"
              type="text"
              placeholder="Enter full name"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:outline-none"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              v-model="email"
              type="email"
              placeholder="Enter email address"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:outline-none"
            />
          </div>

          <!-- Password (only for new user) -->
          <div v-if="!props.user">
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
              v-model="password"
              type="password"
              placeholder="Enter password"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:outline-none"
            />
          </div>

          <!-- Role -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select
              v-model="role"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:outline-none"
            >
              <option
                v-for="option in roleOptions"
                :key="option"
                :value="option"
              >
                {{ option.charAt(0).toUpperCase() + option.slice(1) }}
              </option>
            </select>
          </div>

          <!-- Submit Button -->
          <div class="pt-3">
            <button
              type="submit"
              class="w-full bg-amber-600 text-white py-2.5 rounded-md hover:bg-amber-700 transition-all duration-200"
            >
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
