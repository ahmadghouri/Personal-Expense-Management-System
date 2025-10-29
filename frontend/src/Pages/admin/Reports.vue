<template>
    <div class=" bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-2 rounded-md">
      

        <Filter />

      
        <TopCategory />


        <Drilldown />


    </div>
</template>

<script setup>
import Drilldown from '../../components/admin/reports/Drilldown.vue';
import Filter from '../../components/admin/reports/Filter.vue';
import TopCategory from '../../components/admin/reports/TopCategory.vue';
</script>