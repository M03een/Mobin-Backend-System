<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { api } from '../api/client';
import { useAuthStore } from '../stores/auth';
import { formatApiError } from '../utils/errors';

const router = useRouter();
const auth = useAuthStore();

const loading = ref(true);
const actionLoading = ref(false);
const message = ref('');
const err = ref('');

async function refreshProfile() {
  err.value = '';
  try {
    const { data } = await api.get('me');
    auth.setUser(data);
  } catch (e) {
    err.value = formatApiError(e);
    if (e.response?.status === 401) router.push('/login');
  } finally {
    loading.value = false;
  }
}

async function addPoints(type) {
  message.value = '';
  err.value = '';
  actionLoading.value = true;
  try {
    const { data } = await api.post('points', { type });
    message.value = data.message || 'Points added.';
    await refreshProfile();
  } catch (e) {
    err.value = formatApiError(e);
  } finally {
    actionLoading.value = false;
  }
}

onMounted(() => {
  refreshProfile();
});
</script>

<template>
  <div class="page">
    <h1>Dashboard</h1>
    <p v-if="loading" class="muted">Loading profile…</p>
    <template v-else-if="auth.user">
      <section class="profile card">
        <h2>Profile</h2>
        <dl>
          <div>
            <dt>Username</dt>
            <dd>{{ auth.user.username }}</dd>
          </div>
          <div>
            <dt>Email</dt>
            <dd>{{ auth.user.email }}</dd>
          </div>
          <div>
            <dt>Total points</dt>
            <dd>{{ auth.user.points ?? 0 }}</dd>
          </div>
          <div>
            <dt>Current streak</dt>
            <dd>{{ auth.user.current_streak ?? 0 }} days</dd>
          </div>
        </dl>
      </section>

      <section class="actions card">
        <h2>Earn points</h2>
        <p class="hint">
          Log a Zikr (+25 to your totals) or Tasbeh (+5 to weekly/monthly/daily
          aggregates as implemented by the API).
        </p>
        <div class="buttons">
          <button
            type="button"
            class="btn primary"
            :disabled="actionLoading"
            @click="addPoints('zikr')"
          >
            Zikr (+25)
          </button>
          <button
            type="button"
            class="btn secondary"
            :disabled="actionLoading"
            @click="addPoints('tasbeh')"
          >
            Tasbeh (+5)
          </button>
        </div>
        <p v-if="message" class="ok">{{ message }}</p>
        <p v-if="err" class="err">{{ err }}</p>
      </section>
    </template>
  </div>
</template>

<style scoped>
.page h1 {
  margin: 0 0 1.25rem;
}

.muted {
  color: var(--muted);
}

.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 1.25rem 1.5rem;
  margin-bottom: 1.25rem;
}

.card h2 {
  margin: 0 0 1rem;
  font-size: 1.1rem;
}

dl {
  margin: 0;
  display: grid;
  gap: 0.75rem;
}

dt {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--muted);
}

dd {
  margin: 0.15rem 0 0;
  font-size: 1.1rem;
  font-weight: 600;
}

.hint {
  color: var(--muted);
  font-size: 0.9rem;
  margin: 0 0 1rem;
}

.buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.btn {
  padding: 0.65rem 1.1rem;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  border: none;
}

.btn:disabled {
  opacity: 0.55;
  cursor: not-allowed;
}

.btn.primary {
  background: var(--accent);
  color: #0b1120;
}

.btn.secondary {
  background: var(--surface-2);
  color: var(--text);
  border: 1px solid var(--border);
}

.ok {
  color: var(--accent);
  margin: 1rem 0 0;
}

.err {
  color: var(--danger);
  margin: 1rem 0 0;
}
</style>
