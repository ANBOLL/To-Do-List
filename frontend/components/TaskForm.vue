<template>
	<Teleport to="body">
		<Transition name="modal">
			<div class="b-task-form" @click.self="$emit('cancel')">
				<div class="b-task-form__overlay" @click.self="$emit('cancel')" />
				<div class="b-task-form__card card">
					<div class="b-task-form__header">
						<h3 class="b-task-form__title">
							{{ isEdit ? 'Редактировать задачу' : 'Новая задача' }}
						</h3>
						<button class="b-task-form__close" @click="$emit('cancel')" type="button">
							&times;
						</button>
					</div>
					<form @submit.prevent="submit" class="b-task-form__body">
						<div class="b-task-form__field">
							<label class="b-task-form__label">
								Название <span class="b-task-form__required">*</span>
							</label>
							<input v-model="form.title" class="input" :class="{ 'input--error': errors.title }"
								placeholder="Например: Исправить баг с авторизацией" required minlength="3"
								maxlength="255" />
							<span v-if="errors.title" class="b-task-form__error">
								{{ errors.title }}
							</span>
						</div>
						<div class="b-task-form__field">
							<label class="b-task-form__label">Описание</label>
							<textarea v-model="form.description" class="input" rows="4"
								placeholder="Подробное описание задачи..." />
						</div>
						<div class="b-task-form__row">
							<div class="b-task-form__field">
								<label class="b-task-form__label">Срок</label>
								<input v-model="form.due_date" type="date" class="input" />
							</div>
							<div class="b-task-form__field">
								<label class="b-task-form__label">Статус</label>
								<AppSelect :modelValue="form.status" :options="statusOptions" upward
									@update:modelValue="form.status = $event" />
							</div>
						</div>
						<div v-if="error" class="b-task-form__banner">
							{{ error }}
						</div>
						<div class="b-task-form__actions">
							<button type="button" class="btn btn--secondary" @click="$emit('cancel')">
								Отмена
							</button>
							<button type="submit" class="btn btn--primary" :disabled="loading">
								<span v-if="loading" class="spinner spinner--white"
									style="width:16px;height:16px;border-width:2px;margin:0" />
								{{ loading ? 'Сохранение...' : (isEdit ? 'Сохранить' : 'Создать') }}
							</button>
						</div>
					</form>
				</div>
			</div>
		</Transition>
	</Teleport>
</template>

<script setup>
const props = defineProps({
	task: { type: Object, default: null },
	loading: { type: Boolean, default: false },
	error: { type: String, default: '' },
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

	if (!form.value.title || form.value.title.length < 3) {
		e.title = 'Название должно быть не менее 3 символов'
	}

	if (form.value.title && form.value.title.length > 255) {
		e.title = 'Название должно быть не более 255 символов'
	}

	errors.value = e
	return Object.keys(e).length === 0
}

function submit() {
	if (!validate()) return
	emit('submit', { ...form.value })
}

watch(() => props.task, (val) => {
	if (val) {
		form.value = {
			title: val.title,
			description: val.description || '',
			due_date: val.due_date || '',
			status: val.status,
		}
	}
}, { immediate: true })

onMounted(() => {
	document.body.style.overflow = 'hidden'
})

onUnmounted(() => {
	document.body.style.overflow = ''
})
</script>

<style scoped>
.b-task-form {
	position: fixed;
	inset: 0;
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 10000;
	padding: 16px;
	background: rgba(15, 23, 42, 0.5);
	backdrop-filter: blur(4px);
}

.b-task-form__overlay {
	display: none;
}

.b-task-form__card {
	z-index: 1;
}

.b-task-form__card {
	width: 100%;
	max-width: 500px;
	padding: 28px;
	animation: formIn 0.25s ease;
	max-height: 90vh;
	overflow-y: auto;
}

@keyframes formIn {
	from {
		transform: translateY(20px) scale(0.96);
		opacity: 0;
	}

	to {
		transform: translateY(0) scale(1);
		opacity: 1;
	}
}

.b-task-form__header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 24px;
}

.b-task-form__title {
	font-size: 20px;
	font-weight: 700;
}

.b-task-form__close {
	width: 32px;
	height: 32px;
	display: flex;
	align-items: center;
	justify-content: center;
	border: none;
	background: var(--color-bg);
	border-radius: 50%;
	font-size: 22px;
	line-height: 1;
	color: var(--color-text-secondary);
	cursor: pointer;
	transition: all 0.15s ease;
}

.b-task-form__close:hover {
	background: var(--color-border);
	color: var(--color-text);
}

.b-task-form__body {
	display: flex;
	flex-direction: column;
	gap: 16px;
}

.b-task-form__field {
	display: flex;
	flex-direction: column;
	gap: 6px;
}

.b-task-form__label {
	font-size: 13px;
	font-weight: 600;
	color: var(--color-text);
}

.b-task-form__required {
	color: var(--color-danger);
}

.b-task-form__row {
	display: flex;
	gap: 12px;
}

.b-task-form__row .b-task-form__field {
	flex: 1;
}

.b-task-form__error {
	font-size: 12px;
	color: var(--color-danger);
	margin-top: 2px;
}

.b-task-form__banner {
	padding: 12px 14px;
	background: #fef2f2;
	border: 1px solid #fecaca;
	border-radius: var(--radius);
	color: #b91c1c;
	font-size: 13px;
}

.b-task-form__actions {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
	margin-top: 4px;
}

.modal-enter-active,
.modal-leave-active {
	transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
	opacity: 0;
}

@media (max-width: 767px) {
	.b-task-form {
		padding: 0;
		align-items: flex-end;
	}

	.b-task-form {
		background: rgba(15, 23, 42, 0.5);
		backdrop-filter: blur(4px);
	}

	.b-task-form__card {
		position: relative;
		max-width: 100%;
		max-height: 100vh;
		height: auto;
		min-height: 50vh;
		border-radius: var(--radius) var(--radius) 0 0;
		padding: 20px;
		overflow-y: auto;
		-webkit-overflow-scrolling: touch;
	}

	.modal-enter-active .b-task-form__card {
		animation: formInMobile 0.3s ease;
	}

	.modal-leave-active .b-task-form__card {
		animation: formOutMobile 0.25s ease;
	}

	@keyframes formInMobile {
		from {
			transform: translateY(100%);
		}

		to {
			transform: translateY(0);
		}
	}

	@keyframes formOutMobile {
		from {
			transform: translateY(0);
		}

		to {
			transform: translateY(100%);
		}
	}

	.b-task-form__row {
		flex-direction: column;
	}

	.b-task-form__title {
		font-size: 18px;
	}

	.b-task-form__actions {
		flex-direction: column-reverse;
	}

	.b-task-form__actions .btn {
		width: 100%;
		justify-content: center;
	}
}

@media (min-width: 768px) and (max-width: 1023px) {
	.b-task-form__card {
		max-width: 480px;
		padding: 24px;
	}
}

@media (min-width: 1640px) {
	.b-task-form__card {
		max-width: 560px;
		padding: 32px;
	}

	.b-task-form__title {
		font-size: 22px;
	}
}
</style>
