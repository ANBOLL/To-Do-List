<template>
	<div class="b-task-item card" :class="{ 'b-task-item--completed': task.status === 'completed' }">
		<div class="b-task-item__body">
			<div class="b-task-item__content">
				<div class="b-task-item__header">
					<h3 class="b-task-item__title">{{ task.title }}</h3>
					<span v-if="canModify" class="b-task-item__status">
						<span ref="triggerRef" class="b-task-item__badge" :class="'badge badge--' + task.status"
							@click="toggleStatus">
							{{ statusLabel }}
							<svg class="b-task-item__chevron" viewBox="0 0 20 20" fill="currentColor">
								<path fill-rule="evenodd"
									d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
									clip-rule="evenodd" />
							</svg>
						</span>
						<Teleport to="body">
							<Transition name="dropdown">
								<div v-if="showStatusMenu" class="b-task-item__menu" :style="menuStyle">
									<div v-for="s in statuses" :key="s.value" class="b-task-item__menu-option"
										:class="{ 'b-task-item__menu-option--active': s.value === task.status }"
										:style="{ color: dotColor(s.value) }" @click.stop="changeStatus(s.value)">
										<span class="b-task-item__dot" :style="{ background: dotColor(s.value) }" />
										{{ s.label }}
									</div>
								</div>
							</Transition>
						</Teleport>
					</span>
					<span v-else class="badge" :class="'badge--' + task.status">
						{{ statusLabel }}
					</span>
				</div>
				<p v-if="task.description" class="b-task-item__desc">
					{{ task.description }}
				</p>
				<div v-if="task.due_date" class="b-task-item__date-block">
					<span class="b-task-item__label">Срок исполнения:</span>
					<span class="b-task-item__date" :class="'b-task-item__date--' + urgencyClass">
						{{ formatDate(task.due_date) }}
						<span class="b-task-item__days">({{ daysUntilDue }}дн.)</span>
					</span>
				</div>
				<div class="b-task-item__meta">
					<span v-if="ownerInfo" class="b-task-item__owner">
						Владелец: {{ ownerInfo.email }} ({{ ownerInfo.name }})
					</span>
					<span class="b-task-item__created">
						Создана: {{ formatDate(task.created_at) }}
					</span>
				</div>
			</div>
			<div v-if="canModify" class="b-task-item__actions">
				<button class="btn btn--secondary btn--sm" @click="$emit('edit')">
					Изменить
				</button>
				<button class="btn btn--danger btn--sm" @click="$emit('delete', task.id)">
					Удалить
				</button>
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
		[spaceBelow < menuHeight ? 'bottom' : 'top']: spaceBelow < menuHeight
			? (window.innerHeight - rect.top + 4) + 'px'
			: rect.bottom + 4 + 'px',
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

const ownerInfo = computed(() => {
	if (!props.task.user) return null
	if (props.currentUser && props.currentUser.id === props.task.user_id) {
		return { email: props.currentUser.email, name: props.currentUser.name }
	}
	return props.task.user
})

const canModify = computed(() => {
	if (!props.currentUser) return false
	if (props.currentUser.is_admin) return true
	return props.currentUser.id === props.task.user_id
})

const urgencyClass = computed(() => {
	if (!props.task.due_date) return ''
	const diffDays = daysUntilDue.value
	if (diffDays <= 2) return 'danger'
	if (diffDays <= 5) return 'warning'
	return 'safe'
})

const daysUntilDue = computed(() => {
	if (!props.task.due_date) return 0
	const now = new Date()
	const due = new Date(props.task.due_date)
	const diffMs = due.getTime() - now.getTime()
	return Math.ceil(diffMs / (1000 * 60 * 60 * 24))
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
	const d = new Date(date)
	const now = new Date()
	const opts = { day: 'numeric', month: 'long' }
	if (d.getFullYear() !== now.getFullYear()) {
		opts.year = 'numeric'
	}
	return d.toLocaleDateString('ru-RU', opts)
}
</script>

<style scoped>
.b-task-item {
	padding: 18px 20px;
	border-left: 3px solid transparent;
	transition: all 0.2s ease;
}

.b-task-item:hover {
	border-left-color: var(--color-primary);
	box-shadow: var(--shadow-md);
}

.b-task-item--completed {
	opacity: 0.65;
}

.b-task-item--completed .b-task-item__title {
	text-decoration: line-through;
	color: var(--color-text-secondary);
}

.b-task-item__body {
	display: flex;
	flex-wrap: wrap;
	gap: 12px;
	align-items: flex-start;
}

.b-task-item__content {
	flex: 1;
	width: 100%;
}

.b-task-item__header {
	display: flex;
	align-items: center;
	gap: 10px;
	margin-bottom: 8px;
	flex-wrap: wrap;
}

.b-task-item__title {
	font-size: 16px;
	font-weight: 600;
	color: var(--color-text);
}

.b-task-item__desc {
	font-size: 13px;
	color: var(--color-text-secondary);
	line-height: 1.5;
	margin-bottom: 10px;
	white-space: pre-wrap;
	padding: 10px 12px;
	background: var(--color-bg);
	border-radius: var(--radius-sm);
}

.b-task-item__meta {
	display: flex;
	flex-wrap: wrap;
	gap: 16px;
	font-size: 12px;
	color: var(--color-text-secondary);
}

.b-task-item__meta span {
	display: inline-flex;
	align-items: center;
	gap: 4px;
}

.b-task-item__date {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	padding: 2px 8px;
	border-radius: 999px;
	font-size: 11px;
	font-weight: 600;
}

.b-task-item__date--safe {
	background: var(--color-success-bg);
	color: #15803d;
}

.b-task-item__date--warning {
	background: var(--color-warning-bg);
	color: #a16207;
}

.b-task-item__date--danger {
	background: #fef2f2;
	color: #b91c1c;
}

.b-task-item__days {
	font-weight: 400;
	opacity: 0.75;
}

.b-task-item__label {
	font-size: 12px;
	color: var(--color-text-secondary);
}

.b-task-item__date-block {
	display: flex;
	align-items: center;
	gap: 6px;
	margin-bottom: 8px;
}

.b-task-item__actions {
	display: flex;
	flex-direction: column;
	gap: 5px;
	align-items: flex-end;
}

.b-task-item__actions .btn {
	width: 100%;
}

.b-task-item__status {
	display: inline-flex;
}

.b-task-item__badge {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	cursor: pointer;
	padding: 4px 12px;
	border-radius: 999px;
	font-size: 12px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	transition: all 0.15s ease;
	border: 1.5px solid transparent;
}

.b-task-item__badge:hover {
	filter: brightness(0.95);
	border-color: var(--color-border);
}

.b-task-item__chevron {
	width: 14px;
	height: 14px;
	transition: transform 0.2s;
}

.b-task-item__menu {
	background: var(--color-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius);
	box-shadow: var(--shadow-xl);
	z-index: 99999;
	overflow: hidden;
	white-space: nowrap;
	animation: menuIn 0.12s ease;
}

@keyframes menuIn {
	from {
		opacity: 0;
		transform: translateY(-4px);
	}

	to {
		opacity: 1;
		transform: translateY(0);
	}
}

.b-task-item__menu-option {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 8px 14px;
	font-size: 13px;
	cursor: pointer;
	transition: background 0.1s;
	text-transform: none;
	letter-spacing: normal;
	font-weight: 500;
}

.b-task-item__menu-option:hover {
	background: var(--color-primary-light);
}

.b-task-item__menu-option--active {
	color: var(--color-primary);
	font-weight: 600;
	background: var(--color-primary-light);
}

.b-task-item__dot {
	width: 8px;
	height: 8px;
	border-radius: 50%;
	flex-shrink: 0;
}

.dropdown-enter-active,
.dropdown-leave-active {
	transition: opacity 0.12s ease, transform 0.12s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
	opacity: 0;
	transform: translateY(-4px);
}

@media (max-width: 767px) {
	.b-task-item {
		padding: 14px 16px;
	}

	.b-task-item__body {
		flex-direction: column;
	}

	.b-task-item__actions {
		flex-direction: row;
		width: 100%;
	}

	.b-task-item__actions .btn {
		width: auto;
	}

	.b-task-item__title {
		font-size: 15px;
	}

	.b-task-item__meta {
		gap: 10px;
		font-size: 11px;
	}

	.b-task-item__desc {
		font-size: 12px;
		padding: 8px 10px;
	}
}

@media (min-width: 768px) and (max-width: 1023px) {
	.b-task-item {
		padding: 16px 18px;
	}
}

@media (min-width: 1640px) {
	.b-task-item {
		padding: 20px 24px;
	}

	.b-task-item__title {
		font-size: 17px;
	}
}
</style>
