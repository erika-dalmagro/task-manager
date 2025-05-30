<script setup>
  import { onMounted, ref } from 'vue';

  const props = defineProps({
    modelValue: {
      type: [String, Number, null],
      required: true,
    },
    options: {
      type: Array,
      required: true,
    },
  });

  const emit = defineEmits(['update:modelValue']);

  const input = ref(null);

  onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
      input.value.focus();
    }
  });

  defineExpose({
    focus: () => input.value?.focus(),
  });
</script>

<template>
  <select
    ref="input"
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    :value="modelValue"
    @input="emit('update:modelValue', $event.target.value)"
  >
    <option
      v-for="option in options"
      :key="option.value || option.id || ''"
      :value="option.value || option.id || ''"
    >
      {{ option.title || option.name }}
    </option>
  </select>
</template>
