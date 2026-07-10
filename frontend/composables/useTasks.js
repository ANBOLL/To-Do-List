export function useTasks() {
	const config = useRuntimeConfig()
	const { logout } = useAuth()
	const tasks = ref([])
	const meta = ref({ total: 0, per_page: 10, current_page: 1, last_page: 1 })
	const loading = ref(false)
	const error = ref(null)

	function getHeaders() {
		const token = process.client ? localStorage.getItem('token') : null
		return token ? { Authorization: `Bearer ${token}` } : {}
	}

	function handleError(e) {
		if (e?.response?.status === 401) {
			logout()
		}
		return e.data?.message || e.message || 'An error occurred'
	}

	async function fetchTasks(params = {}) {
		loading.value = true
		error.value = null
		try {
			const query = new URLSearchParams()
			if (params.page) query.set('page', params.page)
			if (params.per_page) query.set('per_page', params.per_page)
			if (params.sort_by) query.set('sort_by', params.sort_by)
			if (params.sort_direction) query.set('sort_direction', params.sort_direction)
			if (params.filter_by_status) query.set('filter_by_status', params.filter_by_status)
			if (params.search) query.set('search', params.search)
			if (params.owner_filter) query.set('owner_filter', params.owner_filter)

			const res = await $fetch(`${config.public.apiBase}/tasks?${query}`, {
				headers: getHeaders(),
			})
			tasks.value = res.data
			meta.value = res.meta
		} catch (e) {
			error.value = handleError(e)
		} finally {
			loading.value = false
		}
	}

	async function createTask(data) {
		error.value = null
		try {
			const res = await $fetch(`${config.public.apiBase}/tasks`, {
				method: 'POST',
				headers: getHeaders(),
				body: data,
			})
			return { success: true, task: res.data }
		} catch (e) {
			return { success: false, error: handleError(e) }
		}
	}

	async function updateTask(id, data) {
		error.value = null
		try {
			const res = await $fetch(`${config.public.apiBase}/tasks/${id}`, {
				method: 'PUT',
				headers: getHeaders(),
				body: data,
			})
			return { success: true, task: res.data }
		} catch (e) {
			return { success: false, error: handleError(e) }
		}
	}

	async function deleteTask(id) {
		error.value = null
		try {
			await $fetch(`${config.public.apiBase}/tasks/${id}`, {
				method: 'DELETE',
				headers: getHeaders(),
			})
			return { success: true }
		} catch (e) {
			return { success: false, error: handleError(e) }
		}
	}

	return { tasks, meta, loading, error, fetchTasks, createTask, updateTask, deleteTask }
}
