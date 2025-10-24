<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-8 rounded-md">
    <!-- Header Section with Gradient -->

    <div class="relative z-10">
      <h1 class="text-4xl font-bold text-gray-800 mb-2">Manager Dashboard</h1>
    </div>


    <!-- Summary Cards with Glassmorphism -->
    <DashboardOverall/>

    <!-- Filters Section with Modern Design -->

    <!-- Charts Grid -->
    <DashboardBreakdown />


    <!-- Monthly Trends Table -->
    <DashboardMonthly/>

    <!-- Expense Distribution Cards -->


    <!-- Export Buttons with Animations -->

  </div>
</template>

<script setup>

import DashboardBreakdown from '../../components/manager/dashboard/DashboardBreakdown.vue';
import DashboardOverall from '../../components/manager/dashboard/DashboardOverall.vue';
import DashboardMonthly from '../../components/manager/dashboard/DashboardMonthly.vue';



</script>