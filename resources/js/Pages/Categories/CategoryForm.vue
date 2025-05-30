<template>
  <div class="block p-6 bg-white rounded-lg shadow-sm">
    <h3 class="mb-3">{{ actionLabel }} Category</h3>
    <form @submit.prevent="submitForm" class="flex flex-col">
      <div>
        <InputLabel for="name" value="Category Name" />
        <TextInput
          id="name"
          type="text"
          class="mt-1 block mb-4 w-full"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />

        <InputError class="mt-2" :message="form.errors.name" />
      </div>
      <div>
        <InputLabel for="parent" value="Parent" />

        <SelectInput
          id="parent"
          class="mt-1 block w-full"
          v-model="form.parent_id"
          :options="[{ value: '', title: 'No parent' }, ...categories]"
        />

        <InputError class="mt-2" :message="form.errors.parent_id" />
      </div>
      <div class="flex justify-end mt-6 gap-2">
        <SecondaryButton class="justify-end flex" @click="onSuccess()">Cancel</SecondaryButton>
        <PrimaryButton :disabled="form.processing"> Save </PrimaryButton>
      </div>
    </form>
  </div>
</template>

<script setup>
  import { computed, toRefs, reactive, ref } from 'vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  import { router } from '@inertiajs/vue3';
  import SelectInput from '@/Components/SelectInput.vue';
  import { useCategoryStore } from '@/stores/categoryStore';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import TextInput from '@/Components/TextInput.vue';

  const props = defineProps({
    isModal: {
      type: Boolean,
      default: false,
    },
  });

  const emit = defineEmits(['update:modelValue', 'closeModal']);

  const category = usePage().props.category?.data ?? null;
  const isEdit = computed(() => !!category);
  const actionLabel = computed(() => (isEdit.value ? 'Update' : 'Create'));
  const categoryStore = useCategoryStore();
  const { categories } = toRefs(categoryStore);

  const form = useForm({
    name: category?.name ?? '',
    parent_id: category?.parent_id ?? null,
  });

  const submitForm = async () => {
    try {
      if (isEdit.value) {
        await categoryStore.updateCategory(category.id, { ...form });
      } else {
        await categoryStore.createCategory({ ...form });
      }

      onSuccess();
    } catch (error) {
      throw error;
    }
  };

  function onSuccess() {
    props.isModal ? emit('closeModal') : router.visit('/categories');
  }
</script>
