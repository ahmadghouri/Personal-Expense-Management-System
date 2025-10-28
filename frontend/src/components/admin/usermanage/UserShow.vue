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
    <div class=" mx-auto mt-10 bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
      <div class="overflow-x-auto rounded-xl">
        <ClipLoader v-if="props.isLoadind" color="#f59e0b" size="50px" class="my-6" />

        <table class="min-w-full bg-white text-sm text-left">
          <thead class="bg-gray-100 text-gray-600 uppercase text-xs border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 font-semibold">User ID</th>
              <th class="px-6 py-3 font-semibold">Name</th>
              <th class="px-6 py-3 font-semibold">Email</th>
              <th class="px-6 py-3 font-semibold">Role</th>
              <th class="px-6 py-3 text-center font-semibold">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            <tr
              v-for="user in users"
              :key="user.id"
              class="hover:bg-indigo-50 transition-colors duration-200"
            >
              <td class="px-6 py-4 font-medium text-gray-800">
                {{ user.id }}
              </td>

              <td class="px-6 py-4 text-gray-700 font-semibold">
                {{ user.name }}
              </td>

              <td class="px-6 py-4 text-gray-600">
                {{ user.email }}
              </td>

              <td class="px-6 py-4">
                <span
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                  :class="{
                    'bg-gray-500 text-white': user.role === 'admin',
                    'bg-amber-500 text-white': user.role === 'manager',
                    'bg-gray-400 text-white': user.role === 'user'
                  }"
                >
                  {{ user.role }}
                </span>
              </td>

              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                  <!-- Edit -->
                  <button
                    @click="handleEdit(user)"
                    class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition"
                    title="Edit"
                  >
                    <svg
                      class="w-5 h-5"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                      />
                    </svg>
                  </button>

                  <!-- Delete -->
                  <button
                    @click="handleDelete(user.id)"
                    class="p-2 rounded-md text-red-500 hover:text-red-700 hover:bg-red-50 transition"
                    title="Delete"
                  >
                    <svg
                      class="w-5 h-5"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                      />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <UserRegister
      v-if="showModal"
      :user="selectedUser"
      @close="closeModal"
      :allUser="props.allUser"
    />
  </div>
</template>

