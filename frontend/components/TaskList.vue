<template>
	<div>
		<div v-if="loading" class="b-task-list__skeleton">
			<div v-for="n in 3" :key="n" class="b-task-list__skeleton-item card">
				<div class="b-task-list__skeleton-line b-task-list__skeleton-line--title" />
				<div class="b-task-list__skeleton-line b-task-list__skeleton-line--text" />
				<div class="b-task-list__skeleton-line b-task-list__skeleton-line--meta" />
			</div>
		</div>

		<div v-else-if="tasks.length === 0" class="state-empty card">
			<div class="state-empty__icon">
				<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
					stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
					<line x1="12" y1="8" x2="12" y2="16" />
					<line x1="8" y1="12" x2="16" y2="12" />
				</svg>
			</div>
			<h3 class="state-empty__title">Нет задач</h3>
			<p class="state-empty__text">
				Создайте первую задачу, нажав на кнопку «Новая задача»
			</p>
		</div>

		<div v-else class="b-task-list__items">
			<TaskItem v-for="task in tasks" :key="task.id" :task="task" :currentUser="currentUser"
				@edit="$emit('edit', task)" @delete="$emit('delete', task.id)"
				@change-status="$emit('changeStatus', $event)" />
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
.b-task-list__items {
	display: flex;
	flex-direction: column;
	gap: 8px;
}

.b-task-list__skeleton {
	display: flex;
	flex-direction: column;
	gap: 8px;
}

.b-task-list__skeleton-item {
	padding: 18px 20px;
}

.b-task-list__skeleton-line {
	height: 14px;
	border-radius: 8px;
	background: linear-gradient(90deg, var(--color-border) 25%, #f1f5f9 50%, var(--color-border) 75%);
	background-size: 200% 100%;
	animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
	0% {
		background-position: 200% 0;
	}

	100% {
		background-position: -200% 0;
	}
}

.b-task-list__skeleton-line--title {
	width: 60%;
	height: 18px;
	margin-bottom: 10px;
}

.b-task-list__skeleton-line--text {
	width: 85%;
	margin-bottom: 8px;
}

.b-task-list__skeleton-line--meta {
	width: 35%;
	height: 12px;
}

@media (max-width: 767px) {
	.b-task-list__items {
		gap: 6px;
	}

	.b-task-list__skeleton-item {
		padding: 14px 16px;
	}
}

@media (min-width: 1640px) {
	.b-task-list__items {
		gap: 10px;
	}
}
</style>
