<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login</h2>

      <form @submit.prevent="loginFormHandler">
        <!-- Email -->
        <div class="mb-4">
          <label class="block text-gray-700 mb-2" for="email">Email</label>
          <input v-model="email" type="email" id="email" placeholder="Enter your email"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <!-- Password -->
        <div class="mb-4">
          <label class="block text-gray-700 mb-2" for="password">Password</label>
          <input v-model="password" type="password" id="password" placeholder="Enter your password"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <!-- Forgot Password -->
        <div class="mb-4 text-right">
          <RouterLink to="/forgot-password" class="text-blue-500 hover:underline text-sm">
            Forgot Password?
          </RouterLink>
        </div>

        <!-- Login Button -->
        <button type="submit"
          class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
          Login
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>

import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../../Api/AxiosBase";

import { useToast } from 'vue-toastification'
const toast = useToast()

const router = useRouter();

const email = ref("");
const password = ref("");

const loginFormHandler = async () => {

  try {
    const res = await api.post('/login', {
      email: email.value,
      password: password.value
    })
    localStorage.setItem('auth_token', res.data.token);
    localStorage.setItem('user_role', res.data.user_role);
    toast.success(res.data.user_role === 'admin' ? 'Admin Login Successful' : 'Manager Login Successful')

    if (res.data.user_role === 'admin') {
      router.push({ name: "AdminDashboard" });
    } else {
      router.push({ name: "ManagerDashboard" });
    }
  } catch (error) {
    console.log(error);
    toast.error('Login Failed')
  }
}
</script>

<style scoped></style>
