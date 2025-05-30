<template>
  <div class="pl-4 border-l border-gray-300">
    <div v-for="category in categories" :key="category.id">
      <div class="flex w-full items-center justify-between my-2">
        <div v-if="category.parent_id">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2.5"
            stroke="currentColor"
            class="h-4 w-4"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
          </svg>
        </div>
        <div class="w-1/2">
          <span>{{ category.name }}</span>
        </div>
        <div class="w-1/2 flex justify-end mr-4 space-x-2">
          <SecondaryButton class="ms-3" @click="$emit('edit', category)"> Edit </SecondaryButton>
          <DangerButton class="ms-3" @click="handleDeleteModal(category.id)"> Delete </DangerButton>
        </div>
      </div>

      <CategoryTreeAdmin
        v-if="category.children?.length"
        :categories="category.children"
        @edit="$emit('edit', $event)"
        @delete="$emit('delete', $event)"
      />
    </div>
  </div>

  <DeleteModal
    v-model="showDeleteModal"
    @confirm="
      $emit('delete', categoryId);
      showDeleteModal = false;
    "
    @cancel="showDeleteModal = false"
  ></DeleteModal>
</template>

<script setup>
  import { ref } from 'vue';
  import DangerButton from '@/Components/DangerButton.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import DeleteModal from '@/Components/DeleteModal.vue';

  defineProps({
    categories: Array,
  });

  const emit = defineEmits(['edit', 'delete']);

  const showDeleteModal = ref(false);
  const categoryId = ref(null);

  const handleDeleteModal = (id) => {
    showDeleteModal.value = true;
    categoryId.value = id;
  };
</script>
