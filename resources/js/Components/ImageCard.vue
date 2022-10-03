<script setup>
import Card from '@/Components/Card.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { computed, ref } from 'vue';
import { usePage } from "@inertiajs/inertia-vue3";

const processing = ref(false);
const imagesRef = ref(await fetch(`/api/images`).then((r) => r.json()));

const images = computed(() => imagesRef.value);

Echo.private('App.Models.User.' + usePage().props.value.auth.user)
    .notification(() => {
      axios.get(`/api/images`).then(r => {
        imagesRef.value = r.data;
      })
    });

const deleteImage = (id) => {
  processing.value = true;
  if (!confirm("Are you sure you want to delete this image")) {
    processing.value = false;
    return;
  }

  axios.post(`/api/images/${ id }`, { _method: "DELETE" }).then(resp => {
    remove(id);
  })
}

const remove = (id) => {
  imagesRef.value = imagesRef.value.filter(image => image.id !== id)
  processing.value = false;
}

</script>

<template>
  <div v-if="images">
    <Card v-for="image in images" :id="image.id" @remove="remove" :class="{ 'opacity-25': processing }">
      <div class="flex-grow flex items-center" >
        <img :src="'/storage/' + image.name">
      </div>
      <div class="p-2 bg-white">
        <PrimaryButton type="button" class="bg-prim" @click="deleteImage(image.id)">Delete</PrimaryButton>
      </div>
    </Card>
  </div>
  <div v-else class="">No images found</div>

</template>
