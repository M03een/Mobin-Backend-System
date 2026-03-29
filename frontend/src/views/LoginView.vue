<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { formatApiError } from '../utils/errors';

const router = useRouter();
const route = useRoute();
const auth = useAuthStore();

const email = ref('');
const password = ref('');
const submitting = ref(false);
const error = ref('');

async function submit() {
  error.value = '';
  submitting.value = true;
  try {
    await auth.login({ email: email.value, password: password.value });
    const redirect = route.query.redirect || '/dashboard';
    router.push(typeof redirect === 'string' ? redirect : '/dashboard');
  } catch (e) {
    error.value = formatApiError(e);
  } finally {
    submitting.value = false;
  }
}
</script>

<template>
  <div class="page">
    <h1>Log in</h1>
    <form class="card" @submit.prevent="submit">
      <label>
        <span>Email</span>
        <input v-model="email" type="email" required autocomplete="email" />
      </label>
      <label>
        <span>Password</span>
        <input
          v-model="password"
          type="password"
          required
          autocomplete="current-password"
        />
      </label>
      <p v-if="error" class="err">{{ error }}</p>
      <button type="submit" class="btn" :disabled="submitting">
        {{ submitting ? 'Signing in…' : 'Sign in' }}
      </button>
      <p class="footer">
        <router-link to="/forgot-password">Forgot password?</router-link>
        ·
        <router-link to="/register">Create an account</router-link>
      </p>
    </form>
  </div>
</template>

<style scoped>
.page h1 {
  margin: 0 0 1.25rem;
}

.card {
  max-width: 400px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

label span {
  display: block;
  font-size: 0.85rem;
  color: var(--muted);
  margin-bottom: 0.35rem;
}

input {
  width: 100%;
  padding: 0.55rem 0.65rem;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: var(--bg);
  color: var(--text);
}

.btn {
  background: var(--accent);
  color: #0b1120;
  border: none;
  padding: 0.65rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.err {
  color: var(--danger);
  margin: 0;
  font-size: 0.9rem;
}

.footer {
  margin: 0;
  font-size: 0.9rem;
  color: var(--muted);
  text-align: center;
}
</style>
