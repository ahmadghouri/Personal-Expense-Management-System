<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Forgot Password</h2>

      <p class="text-gray-600 mb-4 text-center">
        Enter your email address to reset your password
      </p>

      <form @submit.prevent="sendResetLink">
        <!-- Email -->
        <div class="mb-4">
          <label class="block text-gray-700 mb-2" for="email">Email</label>
          <input v-model="email" type="email" id="email" placeholder="Enter your email"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
          Send Reset Link
        </button>
      </form>

      <div class="mt-4 text-center">
        <RouterLink to="/" class="text-blue-500 hover:underline text-sm">
          Back to Login
        </RouterLink>
      </div>

      <p v-if="message" class="mt-4 text-green-500 text-center">
        {{ message }}
      </p>
      <p v-if="error" class="mt-4 text-red-500 text-center">
        {{ error }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useToast } from 'vue-toastification'
const toast = useToast()

const email = ref("");
const message = ref("");
const error = ref("");

const sendResetLink = async () => {
  message.value = "";
  error.value = "";

  try {
    const response = await axios.post("http://backend.test/api/forgot-password", {
      email: email.value
    });
    toast.success(response.data.message || "Reset link sent! Check your email.")
    message.value = response.data.message || "Reset link sent! Check your email.";
  } catch (err) {
    toast.error(err.response?.data?.message || "Something went wrong!")
    error.value = err.response?.data?.message || "Something went wrong!";
  }
};
</script>

<style scoped>
/* Tailwind handles most styling */
</style>
