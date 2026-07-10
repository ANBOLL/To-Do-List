<template>
  <div>
    <div class="dashboard-header">
      <h2>Мои задачи</h2>
      <button class="btn btn-primary" @click="showCreateForm = true">+ Новая задача</button>
    </div>

    <TaskFilters
      :search="search"
      :statusFilter="statusFilter"
      :sortBy="sortBy"
      :ownerFilter="ownerFilter"
      :isAdmin="user?.is_admin"
      @update:search="onSearch"
      @update:statusFilter="onStatusFilter"
      @update:sortBy="onSortBy"
      @update:ownerFilter="onOwnerFilter"
      @reset="resetFilters"
    />

    <div v-if="tasksError" class="error-state">{{ tasksError }}</div>

    <TaskList
      :tasks="tasks"
      :loading="loading"
      :currentUser="user"
      @edit="openEdit"
      @delete="handleDelete"
      @change-status="handleStatusChange"
    />

    <div v-if="meta.total > 0" class="pagination">
      <span class="page-info">{{ meta.total }} задач (страница {{ meta.current_page }} из {{ meta.last_page }})</span>
      <div class="page-btns">
        <button class="btn btn-secondary" :disabled="meta.current_page <= 1" @click="goPage(meta.current_page - 1)">Назад</button>
        <button class="btn btn-secondary" :disabled="meta.current_page >= meta.last_page" @click="goPage(meta.current_page + 1)">Вперёд</button>
      </div>
    </div>

    <TaskForm
      v-if="showCreateForm"
      :loading="formLoading"
      @submit="handleCreate"
      @cancel="showCreateForm = false"
    />

    <TaskForm
      v-if="editingTask"
      :task="editingTask"
      :loading="formLoading"
      @submit="handleUpdate"
      @cancel="editingTask = null"
    />
  </div>
</template>

<script setup>
definePageMeta({ middleware: 'auth' })

const { user } = useAuth()
const {
  tasks, meta, loading, error: tasksError,
  fetchTasks, createTask, updateTask, deleteTask
} = useTasks()

const search = ref('')
const statusFilter = ref('')
const sortBy = ref('')
const ownerFilter = ref('')
const showCreateForm = ref(false)
const editingTask = ref(null)
const formLoading = ref(false)
let searchTimeout = null

async function loadTasks() {
  const params = {
    page: meta.value.current_page,
    per_page: meta.value.per_page,
    sort_by: sortBy.value || undefined,
    filter_by_status: statusFilter.value || undefined,
    search: search.value || undefined,
    owner_filter: ownerFilter.value || undefined,
  }
  await fetchTasks(params)
}

function onSearch(val) {
  search.value = val
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => { meta.value.current_page = 1; loadTasks() }, 400)
}

function onStatusFilter(val) {
  statusFilter.value = val
  meta.value.current_page = 1
  loadTasks()
}

function onSortBy(val) {
  sortBy.value = val
  loadTasks()
}

function onOwnerFilter(val) {
  ownerFilter.value = val
  meta.value.current_page = 1
  loadTasks()
}

function resetFilters() {
  search.value = ''
  statusFilter.value = ''
  sortBy.value = ''
  ownerFilter.value = ''
  meta.value.current_page = 1
  loadTasks()
}

function goPage(page) {
  meta.value.current_page = page
  loadTasks()
}

async function handleCreate(data) {
  formLoading.value = true
  const result = await createTask(data)
  formLoading.value = false
  if (result.success) {
    showCreateForm.value = false
    await loadTasks()
  } else {
    alert(result.error)
  }
}

function openEdit(task) {
  editingTask.value = { ...task }
}

async function handleUpdate(data) {
  formLoading.value = true
  const result = await updateTask(editingTask.value.id, data)
  formLoading.value = false
  if (result.success) {
    editingTask.value = null
    await loadTasks()
  } else {
    alert(result.error)
  }
}

async function handleStatusChange({ id, status }) {
  const result = await updateTask(id, { status })
  if (result.success) {
    await loadTasks()
  } else {
    alert(result.error)
  }
}

async function handleDelete(id) {
  if (!confirm('Вы уверены, что хотите удалить задачу?')) return
  const result = await deleteTask(id)
  if (result.success) {
    await loadTasks()
  } else {
    alert(result.error)
  }
}

onMounted(() => loadTasks())
</script>

<style scoped>
.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}
.dashboard-header h2 { font-size: 22px; }
.pagination {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 16px;
  padding: 12px 0;
}
.page-info { font-size: 13px; color: var(--color-text-secondary); }
.page-btns { display: flex; gap: 8px; }
</style>
