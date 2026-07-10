<template>
  <div class="filters card">
    <div class="filter-row">
      <input
        :value="search"
        class="input"
        placeholder="Поиск по названию..."
        @input="$emit('update:search', $event.target.value)"
      />
      <AppSelect
        :modelValue="statusFilter"
        :options="statusOptions"
        @update:modelValue="$emit('update:statusFilter', $event)"
      />
      <AppSelect
        :modelValue="sortBy"
        :options="sortOptions"
        @update:modelValue="$emit('update:sortBy', $event)"
      />
      <AppSelect
        v-if="isAdmin"
        :modelValue="ownerFilter"
        :options="ownerOptions"
        @update:modelValue="$emit('update:ownerFilter', $event)"
      />
      <button class="btn btn-secondary" @click="$emit('reset')">Сбросить</button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  search: String,
  statusFilter: String,
  sortBy: String,
  ownerFilter: String,
  isAdmin: Boolean,
})

defineEmits(['update:search', 'update:statusFilter', 'update:sortBy', 'update:ownerFilter', 'reset'])

const statusOptions = [
  { value: '', label: 'Все статусы' },
  { value: 'pending', label: 'Ожидает' },
  { value: 'in_progress', label: 'В работе' },
  { value: 'completed', label: 'Завершена' },
]

const sortOptions = [
  { value: '', label: 'Новые' },
  { value: 'due_date', label: 'По сроку' },
  { value: 'status', label: 'По статусу' },
]

const ownerOptions = [
  { value: '', label: 'Все задачи' },
  { value: 'mine', label: 'Только мои' },
  { value: 'others', label: 'Чужие' },
]
</script>

<style scoped>
.filters { margin-bottom: 16px; padding: 12px 16px; }
.filter-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
.filter-row .input { flex: 1; min-width: 160px; }
@media (max-width: 640px) {
  .filter-row { flex-direction: column; }
  .filter-row .input, .filter-row .app-select { min-width: auto; width: 100%; }
}
</style>
