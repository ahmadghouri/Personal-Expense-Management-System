<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-8 rounded-md">


    <Filter />
    <TopCategory />
    <Drilldown />


  </div>
</template>

<script setup>
import Drilldown from '../../components/admin/reports/Drilldown.vue';
import Filter from '../../components/manager/reports/Filter.vue';
import TopCategory from '../../components/manager/reports/TopCategory.vue';







</script>