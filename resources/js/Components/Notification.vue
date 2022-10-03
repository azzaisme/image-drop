<script setup>

const props = defineProps({
  notification: Object
});

</script>

<template>
  <div class="text-black px-2 py-2 cursor-pointer overflow-x-auto border-b"
       :class="{ 'bg-red-100' : props.notification.error }"
  >
    <div class="">
      {{ props.notification.error ?? "Successfully fetched image: " }}
      <br>
      {{ props.notification.path }}
    </div>
  </div>
</template>
