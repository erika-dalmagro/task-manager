<script setup>
  import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-100">
      <!-- Page Heading -->
      <header class="bg-white shadow" v-if="$slots.middle || $slots.end">
        <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between px-6">
            <div class="flex gap-6">
              <Link
                :href="route('tasks.index')"
                class="font-semibold text-xl text-gray-800 leading-tight"
              >
                Tasks
              </Link>
              <Link
                :href="route('categories.index')"
                class="font-semibold text-xl text-gray-800 leading-tight"
                >Categories</Link
              >
            </div>
            <div class="w-1/3">
              <slot name="middle" />
            </div>
            <div>
              <slot name="end" />
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
