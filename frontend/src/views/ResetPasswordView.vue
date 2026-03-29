<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { api } from '../api/client';
import { formatApiError } from '../utils/errors';

const router = useRouter();

const email = ref('');
const otp = ref('');
const password = ref('');
const submitting = ref(false);
const error = ref('');
const success = ref(false);

async function submit() {
  error.value = '';
  success.value = false;
  submitting.value = true;
  try {
    await api.post('password/reset', {
      email: email.value,
      otp: otp.value,
      password: password.value,
    });
    success.value = true;
    setTimeout(() => router.push('/login'), 1500);
  } catch (e) {
    error.value = formatApiError(e);
  } finally {
    submitting.value = false;
  }
}
</script>

<template>
  <div class="page">
    <h1>Reset password</h1>
    <p class="lead">Use the OTP from your email and choose a new password (min 8 characters).</p>
    <form class="card" @submit.prevent="submit">
      <label>
        <span>Email</span>
        <input v-model="email" type="email" required autocomplete="email" />
      </label>
      <label>
        <span>OTP</span>
        <input v-model="otp" required inputmode="numeric" autocomplete="one-time-code" />
      </label>
      <label>
        <span>New password</span>
        <input
          v-model="password"
          type="password"
          required
          minlength="8"
          autocomplete="new-password"
        />
      </label>
      <p v-if="error" class="err">{{ error }}</p>
      <p v-if="success" class="ok">Password updated. Redirecting to log in…</p>
      <button type="submit" class="btn" :disabled="submitting || success">
        {{ submitting ? 'Saving…' : 'Reset password' }}
      </button>
      <p class="footer">
        <router-link to="/login">Back to log in</router-link>
      </p>
    </form>
  </div>
</template>

<style scoped>
.page h1 {
  margin: 0 0 0.5rem;
}

.lead {
  color: var(--muted);
  margin: 0 0 1.25rem;
  max-width: 32rem;
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

.ok {
  color: var(--accent);
  margin: 0;
}

.footer {
  margin: 0;
  font-size: 0.9rem;
  color: var(--muted);
  text-align: center;
}
</style>
