<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-8 rounded-md">
    <!-- Header Section with Gradient -->

    <div class="relative z-10">
      <h1 class="text-4xl font-bold text-gray-800 mb-2">Admin Dashboard</h1>
    </div>


    <DashboardOverall />


    <DashboardBreakdown />


    <Dashboardpercentage />


  </div>
</template>

<script setup>

import DashboardBreakdown from '../../components/admin/dashboard/DashboardBreakdown.vue';
import DashboardOverall from '../../components/admin/dashboard/DashboardOverall.vue';
import Dashboardpercentage from '../../components/admin/dashboard/Dashboardpercentage.vue';



</script>