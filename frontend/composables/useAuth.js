const user = ref(null)
const token = ref(null)
const loading = ref(false)
const error = ref(null)

export function useAuth() {
  const config = useRuntimeConfig()

  function initToken() {
    if (process.client && !token.value) {
      const stored = localStorage.getItem('token')
      if (stored) token.value = stored
    }
  }

  initToken()

  async function login(email, password) {
    loading.value = true
    error.value = null
    try {
      const res = await $fetch(`${config.public.apiBase}/auth/login`, {
        method: 'POST',
        body: { email, password },
      })
      token.value = res.token
      user.value = res.user
      if (process.client) {
        localStorage.setItem('token', res.token)
      }
      return true
    } catch (e) {
      error.value = e.data?.message || 'Login failed'
      return false
    } finally {
      loading.value = false
    }
  }

  async function fetchUser() {
    if (!token.value) return null
    try {
      const res = await $fetch(`${config.public.apiBase}/user`, {
        headers: { Authorization: `Bearer ${token.value}` },
      })
      user.value = res.user
      return res.user
    } catch {
      logout()
      return null
    }
  }

  function logout() {
    if (token.value) {
      $fetch(`${config.public.apiBase}/auth/logout`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token.value}` },
      }).catch(() => {})
    }
    token.value = null
    user.value = null
    if (process.client) {
      localStorage.removeItem('token')
    }
    navigateTo('/login')
  }

  return { user, token, loading, error, login, logout, fetchUser }
}
