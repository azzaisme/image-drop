<script setup>
import { usePage } from "@inertiajs/inertia-vue3";
import Notification from "@/Components/Notification.vue";
import Dropdown from '@/Components/Dropdown.vue';
import { computed, ref } from 'vue'

let notificationsRef = ref(await fetch(`/notifications`).then((r) => r.json()));

const notifications = computed(() => notificationsRef.value);
const hasNotification = computed(() => notificationsRef.value.length > 0);


Echo.private('App.Models.User.' + usePage().props.value.auth.user)
    .notification((notification) => {
      notificationsRef.value.push(notification);
    });


const markRead = (id) => {
  axios.delete(`/notifications/${ id }`).then(async resp => {
    notificationsRef.value = await fetch(`/notifications`).then((r) => r.json());
  })
}
</script>

<template>
  <!-- Notifications -->
  <div class="" style="max-height: 500px; max-width: 400px">

    <div class="ml-3 relative">
      <Dropdown align="right" width="68">
        <template #trigger>
          <div class="bg-blue-900 p-3 shadow-lg rounded-full text-white cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 absolute"
                 :class="{'animate-ping' : hasNotification, 'hidden' : !hasNotification }">
              <path fill-rule="evenodd"
                    d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z"
                    clip-rule="evenodd"/>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
              <path fill-rule="evenodd"
                    d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </template>

        <template #content>
          <div v-if="notifications.length === 0" class="p-4">No notifications</div>
          <Notification v-for="notification in notifications" :notification="notification"
                        @click="markRead(notification.id)"></Notification>
        </template>
      </Dropdown>
    </div>

  </div>
</template>
