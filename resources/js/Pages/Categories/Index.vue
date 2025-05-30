<template>
  <Head title="Categories List" />

  <Layout>
    <template #end>
      <SecondaryButton class="ms-3" @click="handleCreate()"> + New Category </SecondaryButton>
    </template>

    <div class="py-10">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div v-if="loading" class="mb-4">Loading categories...</div>

            <div v-else-if="categories.length <= 0" class="text-center py-6 text-gray-500">
              No categories found.
            </div>

            <div v-else>
              <table class="w-full border">
                <thead>
                  <tr>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Actions</th>
                  </tr>
                </thead>
              </table>
            </div>

            <CategoryTreeAdmin :categories="categories" @edit="handleEdit" @delete="handleDelete" />
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
<script setup>
  import Layout from '@/Layouts/Layout.vue';
  import { Head, router } from '@inertiajs/vue3';
  import { onMounted, toRefs } from 'vue';
  import CategoryTreeAdmin from './CategoryTreeAdmin.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import { useCategoryStore } from '@/stores/categoryStore';

  const store = useCategoryStore();

  const { categories, loading } = toRefs(store);

  onMounted(() => {
    store.fetchCategories();
  });

  function handleCreate() {
    router.visit(route('categories.create'));
  }

  function handleEdit(id) {
    router.visit(route('categories.edit', id));
  }

  const handleDelete = async (id) => {
    store.deleteCategory(id);
  };
</script>
