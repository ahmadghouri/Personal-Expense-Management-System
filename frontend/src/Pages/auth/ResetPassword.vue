<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>

            <form @submit.prevent="submitReset">
                <!-- New Password -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="password">New Password</label>
                    <input type="password" id="password" v-model="password" placeholder="Enter new password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" v-model="password_confirmation"
                        placeholder="Confirm new password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Reset Password
                </button>
            </form>

            <p class="mt-4 text-sm text-gray-600 text-center">
                <router-link to="/login" class="text-blue-500 hover:underline">
                    Back to Login
                </router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../Api/AxiosBase';
import { useToast } from 'vue-toastification'
const toast = useToast()

const route = useRoute();
const router = useRouter();

const token = route.query.token;
const email = route.query.email;
const password = ref('');
const password_confirmation = ref('');

const submitReset = async () => {
    try {
        await api.post('http://backend.test/api/reset-password', {
            token,
            email,
            password: password.value,
            password_confirmation: password_confirmation.value
        });
        toast.success('Password reset successful!');
        router.push('/');
    } catch (err) {
        toast.error(err.response?.data?.message || "Something went wrong!")
        console.error(err.response.data.message);
    }
};
</script>