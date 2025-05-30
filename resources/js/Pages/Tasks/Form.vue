<template>
  <Head :title="isEdit ? 'Update Task' : 'Create Task'" />

  <Layout>
    <template #middle>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ actionLabel }} Task</h2>
    </template>
    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="submitForm" class="space-y-4">
              <div>
                <InputLabel for="title" value="Title" />

                <TextInput
                  id="title"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.title"
                  required
                  autofocus
                  autocomplete="title"
                />

                <InputError class="mt-2" :message="form.errors.title" />
              </div>

              <div>
                <InputLabel for="description" value="Description" />

                <TextAreaInput
                  id="description"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.description"
                  autocomplete="description"
                />

                <InputError class="mt-2" :message="form.errors.description" />
              </div>

              <div>
                <InputLabel for="status" value="Status" />
                <SelectInput
                  id="status"
                  class="mt-1 block w-full"
                  v-model="form.status"
                  required
                  :options="statusOptions"
                />

                <InputError class="mt-2" :message="form.errors.status" />
              </div>

              <div>
                <InputLabel for="priority" value="Priority" />
                <SelectInput
                  id="priority"
                  class="mt-1 block w-full"
                  v-model="form.priority"
                  required
                  :options="priorityOptions"
                />

                <InputError class="mt-2" :message="form.errors.priority" />
              </div>

              <div>
                <InputLabel for="categories" value="Categories" class="mb-2" />

                <label v-for="cat in categoryStore.categories" :key="cat.id">
                  <input
                    type="checkbox"
                    :value="cat.id"
                    v-model="form.category_ids"
                    class="ml-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                  />
                  {{ cat.name }}
                </label>
              </div>
              <div class="justify-between flex mx-2">
                <PrimaryButton :disabled="form.processing"> {{ actionLabel }} Task </PrimaryButton>
                <SecondaryButton class="justify-end flex" @click="showModal = true"
                  >+ Add Category</SecondaryButton
                >
              </div>
              <div v-if="showModal" class="modal">
                <Modal :show="showModal">
                  <CategoryForm @closeModal="showModal = false" is-modal></CategoryForm>
                </Modal>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
  import Layout from '@/Layouts/Layout.vue';
  import { onMounted, ref, computed } from 'vue';
  import { useForm, usePage, Link, Head, router } from '@inertiajs/vue3';
  import { useTaskStore } from '@/stores/taskStore';
  import { useCategoryStore } from '@/stores/categoryStore';
  import CategoryForm from '../Categories/CategoryForm.vue';
  import { priorityOptions, statusOptions } from './taskHelper';

  // Components
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import Modal from '@/Components/Modal.vue';
  import TextInput from '@/Components/TextInput.vue';
  import TextAreaInput from '@/Components/TextAreaInput.vue';
  import SelectInput from '@/Components/SelectInput.vue';

  const props = defineProps({
    task: {
      type: Object,
      default: () => ({
        title: '',
        description: '',
        status: 'pending',
        priority: 'medium',
        category_ids: [],
      }),
    },
  });

  const showModal = ref(false);
  const task = usePage().props.task?.data;
  const actionLabel = computed(() => (isEdit.value ? 'Update' : 'Create'));
  const store = useTaskStore();
  const categoryStore = useCategoryStore();
  const isEdit = computed(() => !!task);

  const form = useForm({
    title: task?.title || '',
    description: task?.description || '',
    status: task?.status || 'pending',
    priority: task?.priority || 'medium',
    category_ids: Array.isArray(task?.category_ids) ? task.category_ids : [],
  });

  onMounted(() => {
    categoryStore.fetchCategories();
  });

  const submitForm = async () => {
    try {
      if (isEdit.value) {
        await store.updateTask(task.id, { ...form });
      } else {
        await store.createTask({ ...form });
      }

      onSuccess();
    } catch (error) {
      throw error;
    }
  };

  function onSuccess() {
    router.visit('/tasks');
  }
</script>
