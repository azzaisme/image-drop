<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ImageCard from '@/Components/ImageCard.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';</script>

<template>
  <Head title="Dashboard"/>

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="flex flex-col" style="width: 1200px; max-width: 95%;">
      <div style="height: 800px; max-height: 60vh; overflow-y: auto" class="p-2 bg-white">
        <Suspense>
          <ImageCard class="grid grid-cols-4 gap-3"></ImageCard>
        </Suspense>
      </div>
      <div class="p-6 bg-white border-t border-gray-200 flex justify-end">
        <Link :href="route('image')"
              class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">Add more!
        </Link>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
