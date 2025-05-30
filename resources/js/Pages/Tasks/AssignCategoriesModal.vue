<script setup>
  import { useTaskStore } from '@/stores/taskStore';
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';

  const props = defineProps({
    taskId: Number,
    allCategories: Array,
  });

  const taskStore = useTaskStore();

  const selectedCategories = ref([]);

  const form = useForm({
    task_ids: [props.taskId],
    category_ids: selectedCategories.value,
  });

  const assign = () => {};
</script>

<template>
  <div>
    <h3>Assign Categories</h3>
    <div v-for="cat in allCategories" :key="cat.id">
      <label>
        <input type="checkbox" :value="cat.id" v-model="selectedCategories" />
        {{ cat.name }}
      </label>
    </div>

    <button @click="assign">Assign Selected</button>
  </div>
</template>
