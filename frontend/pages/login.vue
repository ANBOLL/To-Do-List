<template>
  <div class="login-page">
    <div class="login-card card">
      <h1 class="login-title">Todo App</h1>
      <p class="login-subtitle">Войдите в систему</p>
      <form @submit.prevent="handleLogin" class="login-form">
        <div class="field">
          <label for="email">Email</label>
          <input id="email" v-model="email" type="email" class="input" :class="{ 'input-error': fieldError }" placeholder="you@example.com" required />
        </div>
        <div class="field">
          <label for="password">Пароль</label>
          <input id="password" v-model="password" type="password" class="input" :class="{ 'input-error': fieldError }" placeholder="••••••••" required />
        </div>
        <div v-if="authError" class="error-state">{{ authError }}</div>
        <button type="submit" class="btn btn-primary login-btn" :disabled="loading">
          <span v-if="loading" class="spinner" style="width:16px;height:16px;border-width:2px;margin:0"></span>
          {{ loading ? 'Вход...' : 'Войти' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
definePageMeta({ layout: false })

const email = ref('')
const password = ref('')
const { login, loading, error: authError } = useAuth()
const router = useRouter()
const fieldError = ref(false)

async function handleLogin() {
  fieldError.value = false
  const success = await login(email.value, password.value)
  if (success) {
    router.push('/dashboard')
  } else {
    fieldError.value = true
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 16px;
}
.login-card {
  width: 100%;
  max-width: 400px;
  padding: 32px;
}
.login-title { font-size: 28px; text-align: center; color: var(--color-primary); margin-bottom: 4px; }
.login-subtitle { text-align: center; color: var(--color-text-secondary); margin-bottom: 24px; font-size: 14px; }
.login-form { display: flex; flex-direction: column; gap: 16px; }
.field { display: flex; flex-direction: column; gap: 4px; }
.field label { font-size: 14px; font-weight: 500; color: var(--color-text); }
.login-btn { justify-content: center; padding: 10px; font-size: 15px; margin-top: 8px; }
</style>
