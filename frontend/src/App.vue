<script setup>
import { RouterView } from 'vue-router';
import { onMounted } from 'vue';
import useAuth from './components/useAuth';
const { fetchUser } = useAuth();
onMounted(() => {
  fetchUser();
});

</script>

<template>
<RouterView/>
</template>

<style scoped>

</style>
