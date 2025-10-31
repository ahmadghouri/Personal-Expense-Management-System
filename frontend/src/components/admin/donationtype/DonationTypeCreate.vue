<script setup>
import { ref, watch } from 'vue';
import api from '../../../Api/AxiosBase';
import { useToast } from 'vue-toastification';
const toast = useToast();

const props = defineProps({
    donationType: {
        type: Object,
        default: null
    },
    handleDonationType: {
        type: Function,
        required: false
    }
});

const emit = defineEmits(['close', 'update']);

const name = ref('');
const description = ref('');

watch(() => props.donationType, (newDonationType) => {
    if (newDonationType) {
        name.value = newDonationType.name || '';
        description.value = newDonationType.description || '';
    } else {
        name.value = '';
        description.value = '';
    }
}, { immediate: true });

async function submitForm() {
    try {
        if (props.donationType) {
            await api.put(`/donation-types/${props.donationType.id}`, {      
                name: name.value,
                description: description.value,
            });
            toast.success('Donation Type updated successfully');
            props.handleDonationType?.()
            closeModal();
        } else {
            await api.post('/donation-types', {
                name: name.value,
                description: description.value,
            });
            toast.success('Donation Type registered successfully');
            props.handleDonationType?.()
            closeModal();
        }

    } catch (error) {
        console.error(error);
        toast.error('Error saving donation type');
    }
}

function closeModal() {
    emit('close');
}
</script>


<template>
   
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-white/30 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">
                <!-- Close Button -->
                <button @click="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="pi pi-times text-xl"></i>
                </button>

                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
                    {{ props.donationType ? '✏️ Edit Donation Type' : '👤 Create New Donation Type' }}
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
                            {{ props.donationType ? 'Update Donation Type' : 'Create New Donation Type' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    
</template>

<style scoped>

</style>
