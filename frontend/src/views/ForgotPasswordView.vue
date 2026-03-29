<script setup>
import { ref } from 'vue';
import { api } from '../api/client';
import { formatApiError } from '../utils/errors';

const email = ref('');
const submitting = ref(false);
const error = ref('');
const success = ref(false);

async function submit() {
  error.value = '';
  success.value = false;
  submitting.value = true;
  try {
    await api.post('password/forget', { email: email.value });
    success.value = true;
  } catch (e) {
    error.value = formatApiError(e);
  } finally {
    submitting.value = false;
  }
}
</script>

<template>
  <div class="page">
    <h1>Forgot password</h1>
    <p class="lead">
      Enter your account email. If it exists, you will receive an OTP to reset your
      password.
    </p>
    <form v-if="!success" class="card" @submit.prevent="submit">
      <label>
        <span>Email</span>
        <input v-model="email" type="email" required autocomplete="email" />
      </label>
      <p v-if="error" class="err">{{ error }}</p>
      <button type="submit" class="btn" :disabled="submitting">
        {{ submitting ? 'Sending…' : 'Send OTP' }}
      </button>
      <p class="footer">
        <router-link to="/login">Back to log in</router-link>
      </p>
    </form>
    <div v-else class="card ok">
      <p>OTP sent. Check your email, then continue to reset your password.</p>
      <router-link to="/reset-password" class="btn-link">Enter OTP &amp; new password</router-link>
    </div>
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

.card.ok {
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

.btn-link {
  display: inline-block;
  background: var(--accent);
  color: #0b1120 !important;
  padding: 0.65rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none !important;
  text-align: center;
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
