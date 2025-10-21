<script setup>
import { ref, watch } from 'vue';
import api from '../../../Api/AxiosBase';
import { useToast } from 'vue-toastification';
const toast = useToast();

const props = defineProps({
    category: {
        type: Object,
        default: null
    },
    handleCategory: {
        type: Function,
        required: false
    }
});

const emit = defineEmits(['close', 'update']);

const name = ref('');
const description = ref('');

// Prefill data when editing
watch(() => props.category, (newCategory) => {
    if (newCategory) {
        name.value = newCategory.name || '';
        description.value = newCategory.description || '';
    } else {
        name.value = '';
        description.value = '';
    }
}, { immediate: true });

async function submitForm() {
    try {
        if (props.category) {
            // ✏️ Update mode
            await api.put(`/categories/${props.category.id}`, {
                name: name.value,
                description: description.value,
            });
            toast.success('Category updated successfully');
            props.handleCategory?.()
            closeModal();
        } else {
            // 🆕 Create mode
            await api.post('/categories', {
                name: name.value,
                description: description.value,
            });
            toast.success('Category registered successfully');
            props.handleCategory?.()
            closeModal();
        }

    } catch (error) {
        console.error(error);
        toast.error('Error saving category');
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
                <button @click="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="pi pi-times text-xl"></i>
                </button>

                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
                    {{ props.user ? '✏️ Edit Category' : '👤 Create New Category' }}
                </h2>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input v-model="name" type="text" placeholder="Enter full name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input v-model="description" type="text" placeholder="Enter description"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:outline-none" />
                    </div>

                    <div class="pt-3">
                        <button type="submit"
                            class="w-full bg-amber-600 text-white py-2.5 rounded-md hover:bg-amber-700 transition-all duration-200">
                            {{ props.user ? 'Update User' : 'Create New Category' }}
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
