import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';

export const useCategoryStore = defineStore('categoryStore', () => {
  const toast = useToast();
  const categories = ref([]);
  const categoriesForSelect = ref([]);
  const loading = ref(false);
  const errors = ref({});

  const categoryForm = ref({
    name: '',
    parent_id: null,
  });

  async function fetchCategories() {
    loading.value = true;
    try {
      const res = await axios.get('/api/categories');
      categories.value = res.data.data;
    } catch (e) {
      toast.error('Failed to load categories');
    } finally {
      loading.value = false;
    }
  }

  async function fetchCategoriesForSelect() {
    loading.value = true;
    try {
      const res = await axios.get('/api/all-categories');
      categoriesForSelect.value = res.data;
    } catch (e) {
      toast.error('Failed to load category list');
    } finally {
      loading.value = false;
    }
  }

  async function createCategory($form) {
    try {
      await axios.post('/api/categories', $form || categoryForm.value);
      toast.success('Category created!');
      await fetchCategories();
      categoryForm.value = { name: '', parent_id: null }; // reset form
    } catch (e) {
      errors.value = e.response?.data?.errors || {};
      toast.error('Failed to create category');
      throw e;
    }
  }

  async function updateCategory(id, data) {
    try {
      const response = await axios.put(`/api/categories/${id}`, data);
      await fetchCategories();
      toast.success('Category updated successfully!');
      return response.data;
    } catch (error) {
      toast.error(error.response?.data?.message || 'Failed to update category');
      throw error;
    }
  }

  async function deleteCategory(id) {
    try {
      await axios.delete(`/api/categories/${id}`);
      await fetchCategories();
      toast.success('Category deleted successfully!');
    } catch (error) {
      toast.error('Failed to delete category');
      throw error;
    }
  }

  return {
    categories,
    categoriesForSelect,
    loading,
    categoryForm,
    errors,
    deleteCategory,
    fetchCategories,
    fetchCategoriesForSelect,
    createCategory,
    updateCategory,
  };
});
