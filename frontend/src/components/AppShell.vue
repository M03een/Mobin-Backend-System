<script setup>
import { RouterView, useRoute, useRouter } from 'vue-router';
import { computed } from 'vue';
import { useAuthStore } from '../stores/auth';

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const hideNav = computed(() => route.meta.hideNav === true);

async function logout() {
  await auth.logout();
  router.push('/');
}
</script>

<template>
  <div class="layout">
    <header v-if="!hideNav" class="header">
      <router-link to="/" class="brand">Mobin</router-link>
      <nav class="nav">
        <router-link to="/">Leaderboards</router-link>
        <template v-if="auth.isAuthenticated">
          <router-link to="/dashboard">Dashboard</router-link>
          <button type="button" class="link-btn" @click="logout">
            Log out
          </button>
        </template>
        <template v-else>
          <router-link to="/login">Log in</router-link>
          <router-link to="/register" class="cta">Sign up</router-link>
        </template>
      </nav>
    </header>
    <main class="main">
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
.layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--border);
  background: var(--surface);
}

.brand {
  font-weight: 700;
  font-size: 1.25rem;
  color: var(--text);
  text-decoration: none;
}

.brand:hover {
  color: var(--accent);
  text-decoration: none;
}

.nav {
  display: flex;
  align-items: center;
  gap: 1.25rem;
}

.nav a {
  color: var(--muted);
  font-weight: 500;
}

.nav a.router-link-active {
  color: var(--accent);
}

.cta {
  background: var(--accent);
  color: #0b1120 !important;
  padding: 0.4rem 0.9rem;
  border-radius: 8px;
  font-weight: 600;
}

.cta:hover {
  text-decoration: none;
  filter: brightness(1.05);
}

.link-btn {
  background: none;
  border: none;
  color: var(--muted);
  font-weight: 500;
  cursor: pointer;
  padding: 0;
}

.link-btn:hover {
  color: var(--accent);
}

.main {
  flex: 1;
  padding: 1.5rem;
  max-width: 960px;
  width: 100%;
  margin: 0 auto;
}
</style>
