<template>
  <div class="modal-overlay" @click.self="$emit('cancel')">
    <div class="modal card">
      <h3>{{ isEdit ? 'Редактировать задачу' : 'Новая задача' }}</h3>
      <form @submit.prevent="submit" class="form">
        <div class="field">
          <label>Название *</label>
          <input v-model="form.title" class="input" :class="{ 'input-error': errors.title }" placeholder="Название задачи" required minlength="3" maxlength="255" />
          <span v-if="errors.title" class="field-error">{{ errors.title }}</span>
        </div>
        <div class="field">
          <label>Описание</label>
          <textarea v-model="form.description" class="input" rows="3" placeholder="Описание задачи"></textarea>
        </div>
        <div class="field-row">
          <div class="field">
            <label>Срок</label>
            <input v-model="form.due_date" type="date" class="input" />
          </div>
          <div class="field">
            <label>Статус</label>
            <AppSelect
              :modelValue="form.status"
              :options="statusOptions"
              @update:modelValue="form.status = $event"
            />
          </div>
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-secondary" @click="$emit('cancel')">Отмена</button>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            {{ loading ? 'Сохранение...' : (isEdit ? 'Сохранить' : 'Создать') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  task: { type: Object, default: null },
  loading: { type: Boolean, default: false },
})

const emit = defineEmits(['submit', 'cancel'])

const isEdit = computed(() => !!props.task)

const form = ref({
  title: props.task?.title || '',
  description: props.task?.description || '',
  due_date: props.task?.due_date || '',
  status: props.task?.status || 'pending',
})

const statusOptions = [
  { value: 'pending', label: 'Ожидает' },
  { value: 'in_progress', label: 'В работе' },
  { value: 'completed', label: 'Завершена' },
]

const errors = ref({})

function validate() {
  const e = {}
  if (!form.value.title || form.value.title.length < 3) e.title = 'Название должно быть не менее 3 символов'
  if (form.value.title && form.value.title.length > 255) e.title = 'Название должно быть не более 255 символов'
  errors.value = e
  return Object.keys(e).length === 0
}

function submit() {
  if (!validate()) return
  emit('submit', { ...form.value })
}

watch(() => props.task, (val) => {
  if (val) {
    form.value = { title: val.title, description: val.description || '', due_date: val.due_date || '', status: val.status }
  }
}, { immediate: true })
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10000;
  padding: 16px;
}
.modal {
  width: 100%;
  max-width: 500px;
  padding: 24px;
}
.modal h3 { margin-bottom: 20px; font-size: 18px; }
.form { display: flex; flex-direction: column; gap: 14px; }
.field { display: flex; flex-direction: column; gap: 4px; }
.field label { font-size: 13px; font-weight: 500; color: var(--color-text); }
.field-row { display: flex; gap: 12px; }
.field-row .field { flex: 1; }
.field-error { font-size: 12px; color: var(--color-danger); }
.form-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px; }
@media (max-width: 640px) {
  .field-row { flex-direction: column; }
}
</style>
