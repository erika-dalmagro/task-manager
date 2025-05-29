<template>
    <Head :title="isEdit ? 'Update Task' : 'Create Task'" />

    <Layout>
        <template #header>
          <div class="flex justify-between px-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ isEdit ? 'Update' : 'Create' }} Task</h2>
            
            <NavLink :href="route('tasks.index')">
                List
            </NavLink>
          </div>
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
                    autofocus
                    autocomplete="description"
                />

                <InputError class="mt-2" :message="form.errors.description" />
              </div>

              <div>
                <InputLabel for="status" value="Status"/>
                <SelectInput
                    id="status"
                    class="mt-1 block w-full"
                    v-model="form.status"
                    required
                    autofocus
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
                    autofocus
                    :options="priorityOptions"
                  />

                <InputError class="mt-2" :message="form.errors.priority" />
              </div>
              
              <PrimaryButton :disabled="form.processing">{{ isEdit ? 'Update' : 'Create' }} Task</PrimaryButton>
            </form>
                  </div>
                  </div></div>
                  </div>
          
    </Layout>
</template>

<script setup>
import NavLink from '@/Components/NavLink.vue';
import { reactive, computed } from 'vue'
import { useTaskStore } from '@/stores/taskStore'
import Layout from '@/Layouts/Layout.vue';
import { Head, router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { priorityOptions, statusOptions } from './taskHelper';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import SelectInput from '@/Components/SelectInput.vue';

import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
  task: {
    type: Object,
    default: () => ({
      title: '',
      description: '',
      status: 'pending',
      priority: 'medium',
    }),
  },
})


const task = usePage().props.task;

const form = useForm({
    title: task ? task.title : '',
    description: task ? task.description : '',
    status: task ? task.status : '',
    priority: task ? task.priority : '',
});

const errors = reactive({})
const store = useTaskStore()
const isEdit = computed(() => !!task)

const submitForm = async () => {
  try {
    if (isEdit.value) {
      await store.updateTask(task.id, { ...form })
    } else {
      await store.createTask({ ...form })
    }

    onSuccess()
  } catch (error) {
    console.log(error)
    if (error.response?.status === 422) {
      const serverErrors = error.response.data.errors
      Object.assign(errors, serverErrors)
    } else {
      alert('Something went wrong. Please try again.')
    }
  }
  
}
function onSuccess() {
  router.visit('/tasks')
}
</script>
