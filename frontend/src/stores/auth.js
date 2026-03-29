import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { api, saveToken, getStoredToken, setAuthToken } from '../api/client';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const loading = ref(false);

  const isAuthenticated = computed(() => !!user.value);

  async function fetchMe() {
    const token = getStoredToken();
    if (!token) return;
    setAuthToken(token);
    loading.value = true;
    try {
      const { data } = await api.get('me');
      user.value = data;
    } catch {
      saveToken(null);
      user.value = null;
    } finally {
      loading.value = false;
    }
  }

  async function register(payload) {
    const { data } = await api.post('register', payload);
    saveToken(data.token);
    user.value = data.user;
    return data;
  }

  async function login(payload) {
    const { data } = await api.post('login', payload);
    saveToken(data.token);
    user.value = data.user;
    return data;
  }

  async function logout() {
    try {
      await api.post('logout');
    } catch {
      /* ignore */
    }
    saveToken(null);
    user.value = null;
  }

  function setUser(u) {
    user.value = u;
  }

  return {
    user,
    loading,
    isAuthenticated,
    fetchMe,
    register,
    login,
    logout,
    setUser,
  };
});
