<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";

const form = useForm({
  path: '',
  terms: false,
});

const submit = () => {
  axios.post(route('images.store'), { path: form.path }).then(resp => {
    Inertia.visit("/dashboard")
  }).catch(resp => {

  })
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Images"/>

    <div class="flex" style="max-width: 800px; max-height: 300px">
      <form @submit.prevent="submit" class="bg-white p-3 rounded shadow">
        <div>
          <InputLabel for="path" value="Path"/>
          <TextInput id="path" type="text" class="mt-1 block w-full" v-model="form.path" required autofocus
                     autocomplete="path"/>
          <InputError class="mt-2" :message="form.errors.path"/>
        </div>

        <div class="flex items-center justify-end mt-4">
          <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Submit
          </PrimaryButton>
        </div>
      </form>
      <div class="w-64">
      <img :src="form.path">
      </div>
    </div>
  </AuthenticatedLayout>
</template>
