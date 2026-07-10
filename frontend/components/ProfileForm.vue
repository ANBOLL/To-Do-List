<template>
	<Teleport to="body">
		<Transition name="modal">
			<div class="b-profile-form" @click.self="$emit('cancel')">
				<div class="b-profile-form__card card">
					<div class="b-profile-form__header">
						<h3 class="b-profile-form__title">Редактировать профиль</h3>
						<button class="b-profile-form__close" @click="$emit('cancel')" type="button">
							&times;
						</button>
					</div>
					<form @submit.prevent="submit" class="b-profile-form__body">
						<div class="b-profile-form__field">
							<label class="b-profile-form__label">Имя</label>
							<input v-model="form.name" class="input" :class="{ 'input--error': errors.name }"
								placeholder="Ваше имя" required />
							<span v-if="errors.name" class="b-profile-form__error">
								{{ errors.name }}
							</span>
						</div>
						<div class="b-profile-form__field">
							<label class="b-profile-form__label">Email</label>
							<input v-model="form.email" type="email" class="input"
								:class="{ 'input--error': errors.email }" placeholder="email@example.com" required />
							<span v-if="errors.email" class="b-profile-form__error">
								{{ errors.email }}
							</span>
						</div>
						<div class="b-profile-form__divider" />
						<div class="b-profile-form__field">
							<label class="b-profile-form__label">Новый пароль</label>
							<div class="b-profile-form__password-wrap">
								<input v-model="form.password" :type="showPassword ? 'text' : 'password'" class="input"
									:class="{ 'input--error': errors.password }"
									placeholder="Оставьте пустым, чтобы не менять" minlength="6" />
								<button type="button" class="b-profile-form__toggle-pass"
									@click="showPassword = !showPassword" tabindex="-1">
									<svg v-if="showPassword" width="20" height="20" viewBox="0 0 24 24" fill="none">
										<path fill="currentColor"
											d="M8 17a1 1 0 1 0 0-2a1 1 0 0 0 0 2m4 0a1 1 0 1 0 0-2a1 1 0 0 0 0 2m5-1a1 1 0 1 1-2 0a1 1 0 0 1 2 0" />
										<path fill="currentColor" fill-rule="evenodd"
											d="M6.75 8a5.25 5.25 0 0 1 10.335-1.313a.75.75 0 0 0 1.452-.374A6.75 6.75 0 0 0 5.25 8v1.303a10 10 0 0 0-.642.064c-.9.12-1.658.38-2.26.981-.602.602-.86 1.36-.981 2.26-.117.867-.117 1.97-.117 3.337v.11c0 1.367 0 2.47.117 3.337.12.9.38 1.658.981 2.26.602.602 1.36.86 2.26.982.867.116 1.97.116 3.337.116h8.11c1.367 0 2.47 0 3.337-.116.9-.122 1.658-.38 2.26-.982s.86-1.36.982-2.26c.116-.867.116-1.97.116-3.337v-.11c0-1.367 0-2.47-.116-3.337-.122-.9-.38-1.658-.982-2.26s-1.36-.86-2.26-.981a10 10 0 0 0-.642-.064V8zm-1.942 2.853c-.734.099-1.122.28-1.399.556-.277.277-.457.665-.556 1.4-.101.755-.103 1.756-.103 3.191s.002 2.436.103 3.192c.099.734.28 1.122.556 1.399.277.277.665.457 1.4.556.754.101 1.756.103 3.191.103h8c1.435 0 2.436-.002 3.192-.103.734-.099 1.122-.28 1.399-.556.277-.277.457-.665.556-1.4.101-.755.103-1.756.103-3.191s-.002-2.437-.103-3.192c-.099-.734-.28-1.122-.556-1.399-.277-.277-.665-.457-1.4-.556-.755-.101-1.756-.103-3.191-.103H8c-1.435 0-2.437.002-3.192.103"
											clip-rule="evenodd" />
									</svg>
									<svg v-else width="20" height="20" viewBox="0 0 24 24">
										<path fill="currentColor"
											d="M9 16a1 1 0 1 1-2 0a1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0a1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2a1 1 0 0 0 0 2" />
										<path fill="currentColor" fill-rule="evenodd"
											d="M5.25 8v1.303a10 10 0 0 0-.642.064c-.9.12-1.658.38-2.26.981-.602.602-.86 1.36-.981 2.26-.117.867-.117 1.97-.117 3.337v.11c0 1.367 0 2.47.117 3.337.12.9.38 1.658.981 2.26.602.602 1.36.86 2.26.982.867.116 1.97.116 3.337.116h8.11c1.367 0 2.47 0 3.337-.116.9-.122 1.658-.38 2.26-.982s.86-1.36.982-2.26c.116-.867.116-1.97.116-3.337v-.11c0-1.367 0-2.47-.116-3.337-.122-.9-.38-1.658-.982-2.26s-1.36-.86-2.26-.981a10 10 0 0 0-.642-.064V8a6.75 6.75 0 0 0-13.5 0M12 2.75A5.25 5.25 0 0 0 6.75 8v1.253q.56-.004 1.195-.003h8.11q.635 0 1.195.003V8c0-2.9-2.35-5.25-5.25-5.25m-7.192 8.103c-.734.099-1.122.28-1.399.556-.277.277-.457.665-.556 1.4-.101.755-.103 1.756-.103 3.191s.002 2.436.103 3.192c.099.734.28 1.122.556 1.399.277.277.665.457 1.4.556.754.101 1.756.103 3.191.103h8c1.435 0 2.436-.002 3.192-.103.734-.099 1.122-.28 1.399-.556.277-.277.457-.665.556-1.4.101-.755.103-1.756.103-3.191s-.002-2.437-.103-3.192c-.099-.734-.28-1.122-.556-1.399-.277-.277-.665-.457-1.4-.556-.755-.101-1.756-.103-3.191-.103H8c-1.435 0-2.437.002-3.192.103"
											clip-rule="evenodd" />
									</svg>
								</button>
							</div>
							<span v-if="errors.password" class="b-profile-form__error">
								{{ errors.password }}
							</span>
						</div>
						<div v-if="formError" class="b-profile-form__banner">
							{{ formError }}
						</div>
						<div class="b-profile-form__actions">
							<button type="button" class="btn btn--secondary" @click="$emit('cancel')">
								Отмена
							</button>
							<button type="submit" class="btn btn--primary" :disabled="loading">
								<span v-if="loading" class="spinner spinner--white"
									style="width:16px;height:16px;border-width:2px;margin:0" />
								{{ loading ? 'Сохранение...' : 'Сохранить' }}
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
	user: { type: Object, required: true },
	loading: { type: Boolean, default: false },
	error: { type: String, default: '' },
})

const emit = defineEmits(['submit', 'cancel'])

const form = ref({
	name: props.user.name || '',
	email: props.user.email || '',
	password: '',
})

const showPassword = ref(false)
const errors = ref({})
const formError = ref('')

watch(() => props.error, (val) => {
	formError.value = val
})

watch(() => props.user, (val) => {
	if (val) {
		form.value = { name: val.name || '', email: val.email || '', password: '' }
	}
}, { immediate: true })

function validate() {
	const e = {}

	if (!form.value.name || form.value.name.length < 1) {
		e.name = 'Имя обязательно для заполнения'
	}

	if (!form.value.email) {
		e.email = 'Email обязателен для заполнения'
	} else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
		e.email = 'Введите корректный email адрес'
	}

	if (form.value.password && form.value.password.length < 6) {
		e.password = 'Пароль должен содержать минимум 6 символов'
	}

	errors.value = e
	return Object.keys(e).length === 0
}

function submit() {
	if (!validate()) return

	const data = { name: form.value.name, email: form.value.email }
	if (form.value.password) {
		data.password = form.value.password
	}

	emit('submit', data)
}
</script>

<style scoped>
.b-profile-form {
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

.b-profile-form__card {
	width: 100%;
	max-width: 440px;
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

.b-profile-form__header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 24px;
}

.b-profile-form__title {
	font-size: 20px;
	font-weight: 700;
}

.b-profile-form__close {
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

.b-profile-form__close:hover {
	background: var(--color-border);
	color: var(--color-text);
}

.b-profile-form__body {
	display: flex;
	flex-direction: column;
	gap: 16px;
}

.b-profile-form__field {
	display: flex;
	flex-direction: column;
	gap: 6px;
}

.b-profile-form__label {
	font-size: 13px;
	font-weight: 600;
	color: var(--color-text);
}

.b-profile-form__error {
	font-size: 12px;
	color: var(--color-danger);
	margin-top: 2px;
}

.b-profile-form__banner {
	padding: 12px 14px;
	background: #fef2f2;
	border: 1px solid #fecaca;
	border-radius: var(--radius);
	color: #b91c1c;
	font-size: 13px;
}

.b-profile-form__divider {
	height: 1px;
	background: var(--color-border);
	margin: 4px 0;
}

.b-profile-form__password-wrap {
	position: relative;
	display: flex;
	align-items: center;
}

.b-profile-form__password-wrap .input {
	padding-right: 40px;
}

.b-profile-form__toggle-pass {
	position: absolute;
	right: 4px;
	width: 32px;
	height: 32px;
	display: flex;
	align-items: center;
	justify-content: center;
	border: none;
	background: none;
	border-radius: 6px;
	color: var(--color-text-secondary);
	cursor: pointer;
	transition: all 0.15s ease;
}

.b-profile-form__toggle-pass:hover {
	background: var(--color-bg);
	color: var(--color-text);
}

.b-profile-form__actions {
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
	.b-profile-form {
		padding: 0;
		align-items: flex-end;
	}

	.b-profile-form__card {
		max-width: 100%;
		max-height: 100vh;
		min-height: 50vh;
		border-radius: var(--radius) var(--radius) 0 0;
		padding: 20px;
		overflow-y: auto;
		-webkit-overflow-scrolling: touch;
	}

	.modal-enter-active .b-profile-form__card {
		animation: formInMobile 0.3s ease;
	}

	.modal-leave-active .b-profile-form__card {
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

	.b-profile-form__actions {
		flex-direction: column-reverse;
	}

	.b-profile-form__actions .btn {
		width: 100%;
		justify-content: center;
	}
}

@media (min-width: 768px) and (max-width: 1023px) {
	.b-profile-form__card {
		max-width: 420px;
		padding: 24px;
	}
}

@media (min-width: 1640px) {
	.b-profile-form__card {
		max-width: 480px;
		padding: 32px;
	}
}
</style>
