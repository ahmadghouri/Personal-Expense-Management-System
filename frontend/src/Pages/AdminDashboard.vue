<template>
    <div>
        <h1>Admin Dashboard</h1>
        <p v-if="user">Welcome, {{ user.name }}</p>
        <p v-else>Loading...</p>
    </div>
</template>
<script setup>
import useAuth from '../components/useAuth';

const {user , loading} = useAuth();
</script>