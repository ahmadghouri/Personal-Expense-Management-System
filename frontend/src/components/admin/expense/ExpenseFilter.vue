<template>
  <div class="bg-white p-4 rounded-lg shadow mb-4">
    <h2 class="text-lg font-bold mb-4">Filter Expenses</h2>
    <div class="flex flex-wrap gap-3">
      <select v-model="filters.category" class="border p-2 rounded">
        <option value="">All Categories</option>
        <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
      </select>

      <input v-model="filters.startDate" type="date" class="border p-2 rounded" />
      <input v-model="filters.endDate" type="date" class="border p-2 rounded" />

      <button
        @click="$emit('apply', filters)"
        class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700"
      >
        Apply
      </button>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
defineProps({ categories: Array })
const emits = defineEmits(['apply'])
const filters = reactive({
  category: '',
  startDate: '',
  endDate: ''
})
</script>
