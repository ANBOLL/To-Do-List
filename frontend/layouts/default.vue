<template>
  <div class="layout">
    <header v-if="user" class="header">
      <div class="container header-inner">
        <h1 class="logo">Todo App</h1>
        <nav class="nav">
          <span class="user-info">{{ user.name }} ({{ user.role === 'admin' ? 'Администратор' : 'Пользователь' }})</span>
          <button class="btn btn-danger" @click="logout">Выйти</button>
        </nav>
      </div>
    </header>
    <main class="main">
      <div class="container">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
const { user, logout } = useAuth()
</script>

<style scoped>
.layout { min-height: 100vh; display: flex; flex-direction: column; }
.header {
  background: var(--color-surface);
  border-bottom: 1px solid var(--color-border);
  padding: 12px 0;
  position: sticky;
  top: 0;
  z-index: 100;
}
.header-inner { display: flex; align-items: center; justify-content: space-between; }
.logo { font-size: 20px; font-weight: 700; color: var(--color-primary); }
.nav { display: flex; align-items: center; gap: 12px; }
.user-info { font-size: 14px; color: var(--color-text-secondary); }
.main { flex: 1; padding: 24px 0; }
</style>
