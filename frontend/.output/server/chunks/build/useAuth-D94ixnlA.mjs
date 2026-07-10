import { ref } from 'vue';
import { n as navigateTo, b as useRuntimeConfig } from './server.mjs';

const user = ref(null);
const token = ref(null);
const loading = ref(false);
const error = ref(null);
function useAuth() {
  const config = useRuntimeConfig();
  async function login(email, password) {
    loading.value = true;
    error.value = null;
    try {
      const res = await $fetch(`${config.public.apiBase}/auth/login`, {
        method: "POST",
        body: { email, password }
      });
      token.value = res.token;
      user.value = res.user;
      if (false) ;
      return true;
    } catch (e) {
      error.value = e.data?.message || "Login failed";
      return false;
    } finally {
      loading.value = false;
    }
  }
  async function fetchUser() {
    if (!token.value) return null;
    try {
      const res = await $fetch(`${config.public.apiBase}/user`, {
        headers: { Authorization: `Bearer ${token.value}` }
      });
      user.value = res.user;
      return res.user;
    } catch {
      logout();
      return null;
    }
  }
  function logout() {
    if (token.value) {
      $fetch(`${config.public.apiBase}/auth/logout`, {
        method: "POST",
        headers: { Authorization: `Bearer ${token.value}` }
      }).catch(() => {
      });
    }
    token.value = null;
    user.value = null;
    navigateTo("/login");
  }
  return { user, token, loading, error, login, logout, fetchUser };
}

export { useAuth as u };
//# sourceMappingURL=useAuth-D94ixnlA.mjs.map
