<template>
  <div class="task-item card" :class="{ 'task-completed': task.status === 'completed' }">
    <div class="task-body">
      <div class="task-main">
        <div class="task-header">
          <h3 class="task-title">{{ task.title }}</h3>
          <span v-if="canModify" class="status-badge-wrap">
            <span ref="triggerRef" class="status-trigger" :class="'badge badge-' + task.status" @click="toggleStatus">
              {{ statusLabel }}
              <svg class="chevron" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
              </svg>
            </span>
            <Teleport to="body">
              <Transition name="dropdown">
                <div v-if="showStatusMenu" class="status-menu" :style="menuStyle">
                  <div
                    v-for="s in statuses"
                    :key="s.value"
                    class="status-option"
                    :class="{ active: s.value === task.status }"
                    :style="{ color: dotColor(s.value) }"
                    @click.stop="changeStatus(s.value)"
                  >
                    <span class="opt-dot" :style="{ background: dotColor(s.value) }"></span>
                    {{ s.label }}
                  </div>
                </div>
              </Transition>
            </Teleport>
          </span>
          <span v-else class="badge" :class="'badge-' + task.status">{{ statusLabel }}</span>
        </div>
        <p v-if="task.description" class="task-desc">{{ task.description }}</p>
        <div class="task-info">
          <span v-if="task.user" class="task-owner">Владелец: {{ task.user.email }}</span>
          <span v-if="task.due_date" class="task-date">Срок: {{ task.due_date }}</span>
          <span class="task-created">Создана: {{ formatDate(task.created_at) }}</span>
        </div>
      </div>
      <div v-if="canModify" class="task-actions">
        <button class="btn btn-secondary btn-sm" @click="$emit('edit')">Изменить</button>
        <button class="btn btn-danger btn-sm" @click="$emit('delete')">Удалить</button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  task: { type: Object, required: true },
  currentUser: { type: Object, default: null },
})

const emit = defineEmits(['edit', 'delete', 'changeStatus'])

const showStatusMenu = ref(false)
const triggerRef = ref(null)

const menuStyle = computed(() => {
  if (!showStatusMenu.value || !triggerRef.value) return {}
  const rect = triggerRef.value.getBoundingClientRect()
  const spaceBelow = window.innerHeight - rect.bottom
  const menuHeight = 120
  return {
    position: 'fixed',
    [spaceBelow < menuHeight ? 'bottom' : 'top']: spaceBelow < menuHeight ? (window.innerHeight - rect.top + 4) + 'px' : rect.bottom + 4 + 'px',
    left: rect.left + 'px',
    minWidth: rect.width + 'px',
  }
})

const statuses = [
  { value: 'pending', label: 'Ожидает' },
  { value: 'in_progress', label: 'В работе' },
  { value: 'completed', label: 'Завершена' },
]

const statusLabel = computed(() => {
  const s = statuses.find(s => s.value === props.task.status)
  return s ? s.label : '—'
})

const canModify = computed(() => {
  if (!props.currentUser) return false
  if (props.currentUser.is_admin) return true
  return props.currentUser.id === props.task.user_id
})

function toggleStatus() {
  showStatusMenu.value = !showStatusMenu.value
}

function changeStatus(status) {
  emit('changeStatus', { id: props.task.id, status })
  showStatusMenu.value = false
}

function handleClickOutside(e) {
  if (triggerRef.value && !triggerRef.value.contains(e.target)) {
    showStatusMenu.value = false
  }
}

function handleScroll() {
  if (showStatusMenu.value) showStatusMenu.value = false
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  window.addEventListener('scroll', handleScroll, true)
})
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
  window.removeEventListener('scroll', handleScroll, true)
})

function dotColor(status) {
  return {
    pending: '#9ca3af',
    in_progress: '#eab308',
    completed: '#22c55e',
  }[status] || '#9ca3af'
}

function formatDate(date) {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('ru-RU')
}
</script>

<style scoped>
.task-item { padding: 16px; }
.task-completed { opacity: 0.7; }
.task-body { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-start; }
.task-main { flex: 1; min-width: 250px; }
.task-header { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap; }
.task-title { font-size: 16px; font-weight: 600; }
.task-desc { font-size: 13px; color: var(--color-text-secondary); line-height: 1.4; margin-bottom: 8px; white-space: pre-wrap; }
.task-info { display: flex; flex-wrap: wrap; gap: 12px; font-size: 12px; color: var(--color-text-secondary); }
.task-actions { display: flex; gap: 6px; align-items: flex-start; padding-top: 4px; }
.btn-sm { padding: 4px 10px; font-size: 12px; }

.status-badge-wrap { display: inline-flex; }
.status-trigger {
  display: inline-flex;
  align-items: center;
  gap: 2px;
  cursor: pointer;
  padding: 2px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.status-trigger .chevron {
  width: 14px;
  height: 14px;
  transition: transform 0.2s;
}
.badge-pending { background: #f3f4f6; color: #6b7280; }
.badge-in_progress { background: #fef9c3; color: #854d0e; }
.badge-completed { background: #dcfce7; color: #166534; }

.status-menu {
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius);
  box-shadow: var(--shadow-lg);
  z-index: 99999;
  overflow: hidden;
  white-space: nowrap;
}
.status-option {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px 12px;
  font-size: 13px;
  cursor: pointer;
  transition: background 0.1s;
  text-transform: none;
  letter-spacing: normal;
  font-weight: 400;
}
.status-option:hover { background: #f1f5f9; }
.status-option.active { color: var(--color-primary); font-weight: 600; background: #eef2ff; }
.opt-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}

.dropdown-enter-active, .dropdown-leave-active { transition: opacity 0.15s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; }

@media (max-width: 640px) {
  .task-body { flex-direction: column; }
  .task-actions { width: 100%; justify-content: flex-end; }
}
</style>
