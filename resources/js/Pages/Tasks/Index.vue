<template>
  <Head title="List" />
  <Layout>
    <template #end>
      <SecondaryButton class="ms-3" @click="handleCreate()"> + New Task </SecondaryButton>
    </template>

    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex items-center justify-end gap-4 mb-4">
              <SelectInput
                id="status"
                class="mt-1 block"
                v-model="filters.status"
                required
                autofocus
                :options="[{ value: '', title: 'All Statuses' }, ...statusOptions]"
              />

              <SelectInput
                id="priority"
                class="mt-1 block"
                v-model="filters.priority"
                required
                autofocus
                :options="[{ value: '', title: 'All Priorities' }, ...priorityOptions]"
              />

              <PrimaryButton class="bg-gray-800 text-white" @click="applyFilters">
                Filter
              </PrimaryButton>
            </div>

            <div v-if="loading" class="mb-4">Loading tasks...</div>
            <div v-else-if="tasks.length > 0">
              <table class="w-full border">
                <thead>
                  <tr>
                    <th class="border px-4 py-2">Title</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Priority</th>
                    <th class="border px-4 py-2">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="task in tasks" :key="task.id">
                    <td class="border px-4 py-2">{{ task.title }}</td>
                    <td class="border px-4 py-2">{{ formatStatus(task.status) }}</td>
                    <td class="border px-4 py-2">{{ formatPriority(task.priority) }}</td>
                    <td class="flex justify-center border px-4 py-2 space-x-2">
                      <SecondaryButton class="ms-3" @click="handleEdit(task.id)">
                        Edit
                      </SecondaryButton>
                      <DangerButton class="ms-3" @click="handleDeleteModal(task.id)">
                        Delete
                      </DangerButton>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="flex w-full items-center justify-end mt-6 gap-5">
                <Pagination
                  :pagination="pagination"
                  @updatePage="handleUpdatePage"
                  @nextPage="handleNextPage"
                  @previousPage="handlePreviousPage"
                />

                <SelectInput
                  id="priority"
                  v-model="filters.per_page"
                  required
                  autofocus
                  :options="perPageOptions"
                  @change="applyFilters"
                />
              </div>
            </div>
            <div v-else class="text-center py-6 text-gray-500">No tasks found.</div>
          </div>
        </div>
      </div>
    </div>
  </Layout>

  <DeleteModal
    v-model="showDeleteModal"
    @confirm="handleDelete(taskId)"
    @cancel="showDeleteModal = false"
  ></DeleteModal>
</template>

<script setup>
  import Layout from '@/Layouts/Layout.vue';
  import { toRefs, ref, onMounted, reactive } from 'vue';
  import { useTaskStore } from '@/stores/taskStore';
  import { Link, Head, router } from '@inertiajs/vue3';
  import { priorityOptions, statusOptions, perPageOptions } from './taskHelper';

  // Components
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import DangerButton from '@/Components/DangerButton.vue';
  import Pagination from '@/Components/Pagination.vue';
  import DeleteModal from '@/Components/DeleteModal.vue';
  import SelectInput from '@/Components/SelectInput.vue';

  const store = useTaskStore();
  const currentPage = ref(1);
  const showDeleteModal = ref(false);
  const taskId = ref(null);
  const { tasks, pagination, loading } = toRefs(store);

  const filters = reactive({
    status: '',
    priority: '',
    per_page: 5,
  });

  onMounted(() => {
    store.fetchTasks({ ...filters });
  });

  function handleDelete(id) {
    store.deleteTask(id);
    showDeleteModal.value = false;
  }

  function handleEdit(id) {
    router.visit(route('tasks.edit', id));
  }

  function handleCreate() {
    router.visit(route('tasks.create'));
  }

  function formatStatus(status) {
    const match = statusOptions.find((i) => i.value === status);
    return match ? match.title : status;
  }

  function formatPriority(priority) {
    const match = priorityOptions.find((i) => i.value === priority);
    return match ? match.title : priority;
  }

  const handleDeleteModal = (id) => {
    showDeleteModal.value = true;
    taskId.value = id;
  };

  // Filters
  function applyFilters() {
    store.fetchTasks({ ...filters });
  }

  // Pagination
  function goToPage(page) {
    store.fetchTasks({ ...filters, page });
  }

  const handleUpdatePage = (page) => {
    currentPage.value = page;
    goToPage(currentPage.value);
  };

  const handleNextPage = () => {
    if (currentPage.value < pagination.value.last_page) {
      currentPage.value++;
      goToPage(currentPage.value);
    }
  };

  const handlePreviousPage = () => {
    if (currentPage.value > 1) {
      currentPage.value--;
      goToPage(currentPage.value);
    }
  };
</script>
