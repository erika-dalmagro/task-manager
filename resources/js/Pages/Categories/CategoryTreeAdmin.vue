<template>
  <div class="pl-4 border-l border-gray-200">
    <div v-for="category in categories" :key="category.id" class="mb-2">
      <div class="flex items-center justify-between">
        <span>{{ category.name }}</span>
        <div class="space-x-2">
          <PrimaryButton class="ms-3" @click="$emit('edit', category)"> Edit </PrimaryButton>
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
    @confirm="$emit('delete', categoryId)"
    @cancel="showDeleteModal = false"
  ></DeleteModal>
</template>

<script setup>
  import { ref } from 'vue';
  import DangerButton from '@/Components/DangerButton.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
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
