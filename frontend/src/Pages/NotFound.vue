<template>
  <div class="flex flex-col items-center justify-center h-screen bg-gray-100 text-center">
    <h1 class="text-6xl font-bold text-red-500 mb-4">404</h1>
    <h2 class="text-2xl font-semibold mb-2">Page Not Found</h2>
    <p class="text-gray-600 mb-6">
      Oops! The page you are looking for doesn’t exist.
    </p>
    <router-link
      to="/"
      class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
    >
      Go Back Home
    </router-link>
  </div>
</template>

<script setup>
// No logic needed for now
</script>

<style scoped>
h1 {
  letter-spacing: 4px;
}
</style>
