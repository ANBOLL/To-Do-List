<template>
  <div>
    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Загрузка задач...</p>
    </div>

    <div v-else-if="tasks.length === 0" class="empty-state card">
      <h3>Нет задач</h3>
      <p>Создайте первую задачу!</p>
    </div>

    <div v-else class="task-list">
      <TaskItem
        v-for="task in tasks"
        :key="task.id"
        :task="task"
        :currentUser="currentUser"
        @edit="$emit('edit', task)"
        @delete="$emit('delete', task.id)"
        @change-status="$emit('changeStatus', $event)"
      />
    </div>
  </div>
</template>

<script setup>
defineProps({
  tasks: { type: Array, default: () => [] },
  loading: Boolean,
  currentUser: Object,
})

defineEmits(['edit', 'delete', 'changeStatus'])
</script>

<style scoped>
.loading-state { text-align: center; padding: 40px; color: var(--color-text-secondary); }
.loading-state p { margin-top: 12px; }
.task-list { display: flex; flex-direction: column; gap: 8px; }
</style>
