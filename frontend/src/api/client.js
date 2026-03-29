import axios from 'axios';

const baseURL =
  import.meta.env.VITE_API_BASE_URL?.trim() || '/api/v1';

export const api = axios.create({
  baseURL,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
});

export function setAuthToken(token) {
  if (token) {
    api.defaults.headers.common.Authorization = `Bearer ${token}`;
  } else {
    delete api.defaults.headers.common.Authorization;
  }
}

const TOKEN_KEY = 'mobin_token';

export function getStoredToken() {
  return localStorage.getItem(TOKEN_KEY);
}

export function saveToken(token) {
  if (token) {
    localStorage.setItem(TOKEN_KEY, token);
    setAuthToken(token);
  } else {
    localStorage.removeItem(TOKEN_KEY);
    setAuthToken(null);
  }
}

export function initAuthHeader() {
  const t = getStoredToken();
  if (t) setAuthToken(t);
}
