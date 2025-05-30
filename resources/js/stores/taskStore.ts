import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';

export const useTaskStore = defineStore('taskStore', () => {
  const tasks = ref([]);
  const pagination = ref({});
  const loading = ref(true);
  const toast = useToast();

  async function fetchTasks(params = {}) {
    loading.value = true;

    try {
      const response = await axios.get('/api/tasks', { params });
      const { current_page, last_page, per_page, total } = response.data.meta;

      tasks.value = response.data.data;
      pagination.value = { current_page, last_page, per_page, total };
    } catch (error) {
      toast.error('Failed to load tasks');
    } finally {
      loading.value = false;
    }
  }

  async function createTask(data) {
    try {
      const response = await axios.post('/api/tasks', data);

      await fetchTasks();

      toast.success('Task created successfully!');

      return response.data;
    } catch (error) {
      toast.error(error.response?.data?.message || 'Something went wrong. Please try again.');
      throw error;
    }
  }

  async function updateTask(id, data) {
    try {
      const response = await axios.put(`/api/tasks/${id}`, data);
      await fetchTasks();
      toast.success('Task updated successfully!');
      return response.data;
    } catch (error) {
      toast.error(error.response?.data?.message || 'Failed to update task');
      throw error;
    }
  }

  async function deleteTask(id) {
    try {
      await axios.delete(`/api/tasks/${id}`);
      await fetchTasks();
      toast.success('Task deleted successfully!');
    } catch (error) {
      toast.error('Failed to delete task');
      throw error;
    }
  }

  return {
    tasks,
    pagination,
    loading,
    fetchTasks,
    createTask,
    updateTask,
    deleteTask,
  };
});
