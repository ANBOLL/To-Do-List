<template>
	<div>
		<div class="b-dashboard__header">
			<div>
				<h2 class="b-dashboard__title">Задачи</h2>
				<p v-if="user" class="b-dashboard__greeting">
					Добро пожаловать, {{ user.name }}!
				</p>
			</div>
			<button ref="addBtnRef" class="btn btn--primary" @click="showCreateForm = true">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
					<path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
				</svg>
				<span class="b-dashboard__add-text">Новая задача</span>
			</button>
		</div>

		<TaskFilters :search="search" :statusFilter="statusFilter" :sortBy="sortBy" :ownerFilter="ownerFilter"
			:isAdmin="user?.is_admin" @update:search="onSearch" @update:statusFilter="onStatusFilter"
			@update:sortBy="onSortBy" @update:ownerFilter="onOwnerFilter" @reset="resetFilters" />

		<Transition name="fade">
			<div v-if="tasksError" class="state-error">
				{{ tasksError }}
			</div>
		</Transition>

		<TaskList :tasks="tasks" :loading="loading" :currentUser="user" @edit="openEdit" @delete="handleDelete"
			@change-status="handleStatusChange" />

		<Transition name="fade">
			<div v-if="meta.total > 0" class="b-dashboard__pagination">
				<span class="b-dashboard__page-info">
					{{ meta.total }} {{ pluralize(meta.total, ['задача', 'задачи', 'задач']) }}
					<template v-if="meta.last_page > 1">
						&middot; страница {{ meta.current_page }} из {{ meta.last_page }}
					</template>
				</span>
				<div v-if="meta.last_page > 1" class="b-dashboard__page-btns">
					<button class="btn btn--secondary" :disabled="meta.current_page <= 1"
						@click="goPage(meta.current_page - 1)">
						<svg width="14" height="14" viewBox="0 0 16 16" fill="none">
							<path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
						Назад
					</button>
					<button class="btn btn--secondary" :disabled="meta.current_page >= meta.last_page"
						@click="goPage(meta.current_page + 1)">
						Вперёд
						<svg width="14" height="14" viewBox="0 0 16 16" fill="none">
							<path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
					</button>
				</div>
			</div>
		</Transition>

		<TaskForm v-if="showCreateForm" :loading="formLoading" :error="formError" @submit="handleCreate"
			@cancel="showCreateForm = false" />

		<TaskForm v-if="editingTask" :task="editingTask" :loading="formLoading" :error="formError"
			@submit="handleUpdate" @cancel="editingTask = null" />

		<ConfirmDialog :visible="showDeleteConfirm" title="Удалить задачу?"
			message="Задача будет удалена без возможности восстановления." @confirm="confirmDelete"
			@cancel="showDeleteConfirm = false" />
	</div>
</template>

<script setup>
definePageMeta({ middleware: 'auth' })

const { user } = useAuth()
const {
	tasks, meta, loading, error: tasksError,
	fetchTasks, createTask, updateTask, deleteTask,
} = useTasks()

const route = useRoute()
const router = useRouter()

const search = ref(route.query.search || '')
const statusFilter = ref(route.query.filter_by_status || '')
const sortBy = ref(route.query.sort_by || '')
const ownerFilter = ref(route.query.owner_filter || '')
const showCreateForm = ref(false)
const editingTask = ref(null)
const formLoading = ref(false)
const formError = ref('')
const showDeleteConfirm = ref(false)
const deletingTaskId = ref(null)
let searchTimeout = null

function syncQuery(params) {
	const query = {}

	if (params.search) query.search = params.search
	if (params.filter_by_status) query.filter_by_status = params.filter_by_status
	if (params.sort_by) query.sort_by = params.sort_by
	if (params.owner_filter) query.owner_filter = params.owner_filter
	if (params.page > 1) query.page = params.page

	router.replace({ query })
}

async function loadTasks() {
	const params = {
		page: meta.value.current_page,
		per_page: meta.value.per_page,
		sort_by: sortBy.value || undefined,
		filter_by_status: statusFilter.value || undefined,
		search: search.value || undefined,
		owner_filter: ownerFilter.value || undefined,
	}

	syncQuery(params)
	await fetchTasks(params)
}

function onSearch(val) {
	search.value = val
	clearTimeout(searchTimeout)
	searchTimeout = setTimeout(() => {
		meta.value.current_page = 1
		loadTasks()
	}, 400)
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
	router.replace({ query: {} })
	loadTasks()
}

function goPage(page) {
	meta.value.current_page = page
	loadTasks()
}

async function handleCreate(data) {
	formLoading.value = true
	formError.value = ''
	const result = await createTask(data)
	formLoading.value = false

	if (result.success) {
		showCreateForm.value = false
		await loadTasks()
	} else {
		formError.value = result.error
	}
}

function openEdit(task) {
	formError.value = ''
	editingTask.value = { ...task }
}

async function handleUpdate(data) {
	formLoading.value = true
	formError.value = ''
	const result = await updateTask(editingTask.value.id, data)
	formLoading.value = false

	if (result.success) {
		editingTask.value = null
		await loadTasks()
	} else {
		formError.value = result.error
	}
}

async function handleStatusChange({ id, status }) {
	const result = await updateTask(id, { status })

	if (result.success) {
		await loadTasks()
	} else {
		tasksError.value = result.error
	}
}

function handleDelete(id) {
	deletingTaskId.value = id
	showDeleteConfirm.value = true
}

async function confirmDelete() {
	showDeleteConfirm.value = false
	const result = await deleteTask(deletingTaskId.value)
	deletingTaskId.value = null

	if (result.success) {
		await loadTasks()
	} else {
		tasksError.value = result.error
	}
}

const addBtnRef = ref(null)
const { setSticky, createSignal } = useStickyButton()

watch(createSignal, () => {
	showCreateForm.value = true
})

function pluralize(n, forms) {
	const mod10 = n % 10
	const mod100 = n % 100
	if (mod10 === 1 && mod100 !== 11) return forms[0]
	if (mod10 >= 2 && mod10 <= 4 && (mod100 < 10 || mod100 >= 20)) return forms[1]
	return forms[2]
}

onMounted(() => {
	loadTasks()

	const observer = new IntersectionObserver(
		([entry]) => setSticky(!entry.isIntersecting),
		{ threshold: 0, rootMargin: '-64px 0px 0px 0px' }
	)

	if (addBtnRef.value) {
		observer.observe(addBtnRef.value)
	}
})
</script>

<style scoped>
.b-dashboard__header {
	display: flex;
	justify-content: space-between;
	align-items: flex-start;
	margin-bottom: 20px;
}

.b-dashboard__title {
	font-size: 24px;
	font-weight: 700;
}

.b-dashboard__greeting {
	font-size: 14px;
	color: var(--color-text-secondary);
	margin-top: 2px;
}

.b-dashboard__pagination {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-top: 20px;
	padding: 16px 0;
	border-top: 1px solid var(--color-border);
}

.b-dashboard__page-info {
	font-size: 13px;
	color: var(--color-text-secondary);
}

.b-dashboard__page-btns {
	display: flex;
	gap: 8px;
}

.b-dashboard__page-btns .btn svg {
	flex-shrink: 0;
}

@media (max-width: 767px) {
	.b-dashboard__header {
		flex-direction: column;
		gap: 12px;
	}

	.b-dashboard__title {
		font-size: 20px;
	}

	.b-dashboard__header .btn {
		font-size: 12px;
		padding: 8px 12px;
		white-space: nowrap;
	}

	.b-dashboard__pagination {
		flex-direction: column;
		gap: 10px;
		align-items: flex-start;
	}
}

@media (min-width: 768px) and (max-width: 1023px) {
	.b-dashboard__title {
		font-size: 22px;
	}
}

@media (min-width: 1640px) {
	.b-dashboard__header {
		margin-bottom: 24px;
	}

	.b-dashboard__title {
		font-size: 26px;
	}

	.b-dashboard__pagination {
		margin-top: 24px;
	}
}
</style>
