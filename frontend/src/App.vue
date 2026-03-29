<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from './stores/auth';
import { initAuthHeader } from './api/client';
import AppShell from './components/AppShell.vue';

const auth = useAuthStore();

onMounted(() => {
  initAuthHeader();
  if (localStorage.getItem('mobin_token')) {
    auth.fetchMe();
  }
});
</script>

<template>
  <AppShell />
</template>
