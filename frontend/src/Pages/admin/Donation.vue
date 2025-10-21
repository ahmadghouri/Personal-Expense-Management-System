<template>
  <div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Categories</h1>
        <button
          @click="openModal()"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200"
        >
          + Add Category
        </button>
      </div>

      <!-- Categories Table -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-3 text-left text-gray-700">ID</th>
              <th class="px-4 py-3 text-left text-gray-700">Name</th>
              <th class="px-4 py-3 text-left text-gray-700">Description</th>
              <th class="px-4 py-3 text-left text-gray-700">Status</th>
              <th class="px-4 py-3 text-center text-gray-700">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="category in categories"
              :key="category.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="px-4 py-3">{{ category.id }}</td>
              <td class="px-4 py-3 font-medium">{{ category.name }}</td>
              <td class="px-4 py-3 text-gray-600">
                {{ category.description || 'N/A' }}
              </td>
              <td class="px-4 py-3">
                <span
                  :class="category.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  class="px-3 py-1 rounded-full text-sm font-semibold"
                >
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">
                <button
                  @click="openModal(category)"
                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded mr-2 transition duration-200"
                >
                  Edit
                </button>
                <button
                  @click="deleteCategory(category.id)"
                  class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded transition duration-200"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="categories.length === 0" class="text-center py-8 text-gray-500">
          No categories found
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">
          {{ isEditing ? 'Edit Category' : 'Add Category' }}
        </h2>
        
        <form @submit.prevent="saveCategory">
          <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Name *</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Description</label>
            <textarea
              v-model="form.description"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              rows="3"
            ></textarea>
          </div>

          <div v-if="isEditing" class="mb-4">
            <label class="flex items-center">
              <input
                v-model="form.is_active"
                type="checkbox"
                class="mr-2"
              />
              <span class="text-gray-700 font-medium">Active</span>
            </label>
          </div>

          <div class="flex justify-end gap-2">
            <button
              type="button"
              @click="closeModal"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition duration-200"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200"
            >
              {{ isEditing ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'CategoryManagement',
  setup() {
    const categories = ref([]);
    const showModal = ref(false);
    const isEditing = ref(false);
    const form = ref({
      id: null,
      name: '',
      description: '',
      is_active: true
    });

    // API base URL - apni Laravel API ka URL yahan set karein
    const API_URL = 'http://localhost:8000/api/categories';

    // Fetch all categories
    const fetchCategories = async () => {
      try {
        const response = await axios.get(API_URL);
        categories.value = response.data;
      } catch (error) {
        console.error('Error fetching categories:', error);
        alert('Failed to load categories');
      }
    };

    // Open modal for create/edit
    const openModal = (category = null) => {
      if (category) {
        isEditing.value = true;
        form.value = { ...category };
      } else {
        isEditing.value = false;
        form.value = {
          id: null,
          name: '',
          description: '',
          is_active: true
        };
      }
      showModal.value = true;
    };

    // Close modal
    const closeModal = () => {
      showModal.value = false;
      form.value = {
        id: null,
        name: '',
        description: '',
        is_active: true
      };
    };

    // Save category (create or update)
    const saveCategory = async () => {
      try {
        if (isEditing.value) {
          await axios.put(`${API_URL}/${form.value.id}`, form.value);
          alert('Category updated successfully');
        } else {
          await axios.post(API_URL, form.value);
          alert('Category created successfully');
        }
        closeModal();
        fetchCategories();
      } catch (error) {
        console.error('Error saving category:', error);
        alert('Failed to save category');
      }
    };

    // Delete category
    const deleteCategory = async (id) => {
      if (confirm('Are you sure you want to delete this category?')) {
        try {
          await axios.delete(`${API_URL}/${id}`);
          alert('Category deleted successfully');
          fetchCategories();
        } catch (error) {
          console.error('Error deleting category:', error);
          alert('Failed to delete category');
        }
      }
    };

    // Load categories on component mount
    onMounted(() => {
      fetchCategories();
    });

    return {
      categories,
      showModal,
      isEditing,
      form,
      openModal,
      closeModal,
      saveCategory,
      deleteCategory
    };
  }
};
</script>

<style scoped>
/* Additional custom styles if needed */
</style>  