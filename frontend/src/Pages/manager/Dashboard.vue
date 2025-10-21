<template>
  <div class="p-6 space-y-6">
    <!-- Welcome Message -->
    <div class="bg-white rounded-lg shadow p-6">
      <h1 class="text-3xl font-bold text-gray-800">👋 Welcome, Manager!</h1>
      <p class="text-gray-600 mt-2">
        Here's a quick overview of your recent expenses and donations.
      </p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-indigo-100 border-l-4 border-indigo-500 p-4 rounded shadow">
        <h2 class="text-lg font-semibold text-indigo-700">Total Expenses</h2>
        <p class="text-2xl font-bold text-indigo-900">Rs. 37,000</p>
      </div>
      <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded shadow">
        <h2 class="text-lg font-semibold text-green-700">Donations (Cash)</h2>
        <p class="text-2xl font-bold text-green-900">Rs. 10,000</p>
      </div>
      <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded shadow">
        <h2 class="text-lg font-semibold text-yellow-700">Donations (Food)</h2>
        <p class="text-2xl font-bold text-yellow-900">Rs. 5,000</p>
      </div>
    </div>

    <!-- Chart Placeholders -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white border rounded-lg p-4 text-center text-gray-400">
        📊 Monthly Expense Trend (Chart Coming Soon)
      </div>
      <div class="bg-white border rounded-lg p-4 text-center text-gray-400">
        🧾 Category Breakdown (Chart Coming Soon)
      </div>
    </div>
  </div>
</template>

<script setup>
// Future logic: fetch summary data, render charts
</script>

<style scoped>
/* Optional animations or transitions can be added here */
</style>
