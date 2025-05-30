<template>
  <Head :title="`${actionLabel} Task`" />

  <Layout>
    <template #middle>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ actionLabel }} Task</h2>
    </template>
    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <CategoryForm v-model="categoryStore"></CategoryForm>
      </div>
    </div>
  </Layout>
</template>

<script setup>
  import { onMounted, computed } from 'vue';
  import { usePage, Head } from '@inertiajs/vue3';
  import Layout from '@/Layouts/Layout.vue';
  import { useCategoryStore } from '@/stores/categoryStore';
  import CategoryForm from '../Categories/CategoryForm.vue';

  const props = defineProps({
    category: {
      type: Object,
    },
  });

  const task = usePage().props?.category ?? null;
  const actionLabel = computed(() => (isEdit.value ? 'Update' : 'Create'));
  const categoryStore = useCategoryStore();
  const isEdit = computed(() => !!task);

  onMounted(() => {
    categoryStore.fetchCategories();
  });
</script>
