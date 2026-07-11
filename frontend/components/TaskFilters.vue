<template>
	<div class="b-task-filters card">
		<div class="b-task-filters__row">
			<input :value="search" class="input" placeholder="Поиск по названию, описанию, владельцу..."
				@input="$emit('update:search', $event.target.value)" />
			<AppSelect :modelValue="statusFilter" :options="statusOptions"
				@update:modelValue="$emit('update:statusFilter', $event)" />
			<AppSelect :modelValue="sortBy" :options="sortFieldOptions"
				@update:modelValue="$emit('update:sortBy', $event)" />
			<AppSelect v-if="sortBy" :modelValue="sortDirection" :options="sortDirectionOptions"
				@update:modelValue="$emit('update:sortDirection', $event)" />
			<AppSelect v-if="isAdmin" :modelValue="ownerFilter" :options="ownerOptions"
				@update:modelValue="$emit('update:ownerFilter', $event)" />
			<button class="btn btn--secondary" @click="$emit('reset')">Сбросить</button>
		</div>
	</div>
</template>

<script setup>
defineProps({
	search: String,
	statusFilter: String,
	sortBy: String,
	sortDirection: String,
	ownerFilter: String,
	isAdmin: Boolean,
})

defineEmits(['update:search', 'update:statusFilter', 'update:sortBy', 'update:sortDirection', 'update:ownerFilter', 'reset'])

const statusOptions = [
	{ value: '', label: 'Все статусы' },
	{ value: 'pending', label: 'Ожидает' },
	{ value: 'in_progress', label: 'В работе' },
	{ value: 'completed', label: 'Завершена' },
]

const sortFieldOptions = [
	{ value: '', label: 'Новые' },
	{ value: 'created_at', label: 'По дате' },
	{ value: 'due_date', label: 'По сроку' },
	{ value: 'status', label: 'По статусу' },
]

const sortDirectionOptions = [
	{ value: 'asc', label: 'По возрастанию' },
	{ value: 'desc', label: 'По убыванию' },
]

const ownerOptions = [
	{ value: '', label: 'Все задачи' },
	{ value: 'mine', label: 'Только мои' },
	{ value: 'others', label: 'Чужие' },
]
</script>

<style scoped>
.b-task-filters {
	margin-bottom: 20px;
	padding: 14px 18px;
}

.b-task-filters__row {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
	align-items: center;
}

.b-task-filters__row .input {
	flex: 1;
	min-width: 180px;
}

@media (max-width: 767px) {
	.b-task-filters {
		padding: 12px 14px;
		margin-bottom: 14px;
	}

	.b-task-filters__row {
		flex-direction: column;
	}

	.b-task-filters__row .input,
	.b-task-filters__row .b-app-select {
		min-width: auto;
		width: 100%;
	}

	.b-task-filters__row .btn {
		width: 100%;
	}
}

@media (min-width: 768px) and (max-width: 1023px) {
	.b-task-filters {
		padding: 12px 16px;
	}

	.b-task-filters__row {
		gap: 8px;
	}

	.b-task-filters__row .input {
		min-width: 140px;
	}
}

@media (min-width: 1640px) {
	.b-task-filters {
		padding: 16px 20px;
		margin-bottom: 24px;
	}
}
</style>
