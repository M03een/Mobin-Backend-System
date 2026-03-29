<script setup>
import { ref, watch } from 'vue';
import { api } from '../api/client';
import LeaderboardTable from '../components/LeaderboardTable.vue';
import { formatApiError } from '../utils/errors';

const tabs = [
  { id: 'all', label: 'All time', path: 'points/all' },
  { id: 'day', label: 'Today', path: 'points/day' },
  { id: 'week', label: 'This week', path: 'points/week' },
  { id: 'month', label: 'This month', path: 'points/month' },
];

const active = ref('all');
const rows = ref([]);
const loading = ref(false);
const error = ref('');

async function load() {
  const tab = tabs.find((t) => t.id === active.value);
  if (!tab) return;
  loading.value = true;
  error.value = '';
  try {
    const { data } = await api.get(tab.path);
    rows.value = Array.isArray(data) ? data : [];
  } catch (e) {
    rows.value = [];
    error.value = formatApiError(e);
  } finally {
    loading.value = false;
  }
}

watch(active, load, { immediate: true });
</script>

<template>
  <div class="page">
    <h1>Leaderboards</h1>
    <p class="lead">
      Top 10 users by points. Log in to earn points from Zikr and Tasbeh on your
      <router-link to="/dashboard">dashboard</router-link>.
    </p>

    <div class="tabs">
      <button
        v-for="t in tabs"
        :key="t.id"
        type="button"
        :class="['tab', { active: active === t.id }]"
        @click="active = t.id"
      >
        {{ t.label }}
      </button>
    </div>

    <p v-if="error" class="err">{{ error }}</p>
    <LeaderboardTable
      :rows="rows"
      :loading="loading"
      empty-message="No data for this period yet."
    />
  </div>
</template>

<style scoped>
.page h1 {
  margin: 0 0 0.5rem;
  font-size: 1.75rem;
}

.lead {
  color: var(--muted);
  margin: 0 0 1.5rem;
  max-width: 42rem;
}

.tabs {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.tab {
  background: var(--surface-2);
  border: 1px solid var(--border);
  color: var(--muted);
  padding: 0.45rem 0.9rem;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 500;
}

.tab:hover {
  color: var(--text);
}

.tab.active {
  background: var(--accent);
  color: #0b1120;
  border-color: var(--accent);
}

.err {
  color: var(--danger);
  margin-bottom: 1rem;
}
</style>
