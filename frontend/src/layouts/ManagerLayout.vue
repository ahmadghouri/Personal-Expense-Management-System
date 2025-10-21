<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-800 text-white p-4">
      <h2 class="text-xl font-bold mb-4">Manager Panel</h2>
      <ul class="space-y-2">
        <li><router-link to="/manager/dashboard" class="hover:text-yellow-400">Dashboard</router-link></li>
        <li><router-link to="/manager/reports" class="hover:text-yellow-400">Reports</router-link></li>
         <li @click="logout" class="hover:text-yellow-400">Logout</li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <header class="text-lg font-semibold mb-4">Manager Dashboard</header>
      <router-view />
    </main>
  </div>
</template>
<script setup>
import useAuth from "../components/useAuth";
const { logout } = useAuth();
</script>