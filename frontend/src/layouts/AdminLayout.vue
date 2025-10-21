<script setup>
import useAuth from "../components/useAuth";
const { logout } = useAuth();

const navLink = [
  { to: '/admin/dashboard', name: "Dashboard" },
  { to: '/admin/expenses', name: "Expenses" },
  { to: '/admin/donation', name: "Donation" },
  { to: '/admin/category', name: "Category" },
  { to: '/admin/donation-types', name: "Donation Types" },
  { to: '/admin/reports', name: "Reports" },
  { to: '/admin/user', name: "User Manage" },
];
</script>

<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white p-6 flex-shrink-0 shadow-lg overflow-y-auto">
      <h2 class="text-2xl font-semibold mb-6 border-b border-gray-700 pb-2">
        Admin Panel
      </h2>
      <ul class="space-y-4">
        <li
          v-for="(link, index) in navLink"
          :key="index"
          class="flex items-center gap-3"
        >
          <router-link
            :to="link.to"
            class="flex-1 px-4 py-2 rounded-md font-medium transition-colors duration-200"
            :class="{
              'bg-amber-600 text-white': $route.path === link.to,
              'bg-gray-800 text-white hover:bg-amber-600 hover:text-white': $route.path !== link.to
            }"
          >
            {{ link.name }}
          </router-link>
        </li>

        <li
          class="flex items-center gap-3 px-4 py-2 rounded-md bg-gray-800 hover:bg-amber-600 hover:text-white transition-colors duration-200 cursor-pointer"
          @click="logout"
        >
          <span class="flex-1 text-white font-medium">Logout</span>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto">
      <header class="text-lg font-semibold mb-4">Admin Dashboard</header>
      <router-view />
    </main>
  </div>
</template>
