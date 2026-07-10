<template>
	<div class="b-login-page">
		<div class="b-login-page__shapes">
			<div class="b-login-page__shape b-login-page__shape--1" />
			<div class="b-login-page__shape b-login-page__shape--2" />
			<div class="b-login-page__shape b-login-page__shape--3" />
		</div>
		<div class="b-login-page__card card">
			<div class="b-login-page__logo">
				<div class="b-login-page__logo-icon">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
						stroke-linecap="round" stroke-linejoin="round">
						<path d="M13 5h8m-8 7h8m-8 7h8M3 17l2 2l4-4" />
						<rect width="6" height="6" x="3" y="4" rx="1" />
					</svg>
				</div>
			</div>
			<h1 class="b-login-page__title">To-Do List</h1>
			<p class="b-login-page__subtitle">
				Войдите в систему для управления задачами
			</p>
			<form @submit.prevent="handleLogin" class="b-login-page__form">
				<div class="b-login-page__field">
					<label class="b-login-page__label" for="email">Email</label>
					<input id="email" v-model="email" type="email" class="input" :class="{ 'input--error': fieldError }"
						placeholder="you@example.com" required autocomplete="email" />
				</div>
				<div class="b-login-page__field">
					<label class="b-login-page__label" for="password">Пароль</label>
					<div class="b-login-page__password-wrap">
						<input id="password" v-model="password" :type="showPassword ? 'text' : 'password'" class="input"
							:class="{ 'input--error': fieldError }" placeholder="••••••••" required
							autocomplete="current-password" />
						<button type="button" class="b-login-page__toggle-pass" @click="showPassword = !showPassword"
							tabindex="-1">
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
				</div>
				<div v-if="authError" class="state-error b-login-page__error--shake">
					{{ authError }}
				</div>
				<button type="submit" class="btn btn--primary b-login-page__btn" :disabled="loading">
					<span v-if="loading" class="spinner spinner--white"
						style="width:18px;height:18px;border-width:2px;margin:0" />
					{{ loading ? 'Вход...' : 'Войти' }}
				</button>
			</form>
		</div>
	</div>
</template>

<script setup>
definePageMeta({ layout: false })

const showPassword = ref(false)
const email = ref('')
const password = ref('')
const { login, loading, error: authError } = useAuth()
const router = useRouter()
const fieldError = ref(false)

async function handleLogin() {
	fieldError.value = false
	const success = await login(email.value, password.value)
	if (success) {
		router.push('/dashboard')
	} else {
		fieldError.value = true
	}
}
</script>

<style scoped>
.b-login-page {
	min-height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 60%, #6366f1 100%);
	padding: 16px;
	position: relative;
	overflow: hidden;
}

.b-login-page__shapes {
	position: absolute;
	inset: 0;
	pointer-events: none;
	overflow: hidden;
}

.b-login-page__shape {
	position: absolute;
	border-radius: 50%;
	opacity: 0.1;
}

.b-login-page__shape--1 {
	width: 400px;
	height: 400px;
	background: radial-gradient(circle, #818cf8, transparent);
	top: -100px;
	right: -100px;
	animation: float 8s ease-in-out infinite;
}

.b-login-page__shape--2 {
	width: 300px;
	height: 300px;
	background: radial-gradient(circle, #a78bfa, transparent);
	bottom: -80px;
	left: -80px;
	animation: float 10s ease-in-out infinite reverse;
}

.b-login-page__shape--3 {
	width: 200px;
	height: 200px;
	background: radial-gradient(circle, #c4b5fd, transparent);
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	animation: float 12s ease-in-out infinite 2s;
}

@keyframes float {

	0%,
	100% {
		transform: translate(0, 0);
	}

	50% {
		transform: translate(30px, -30px);
	}
}

.b-login-page__card {
	width: 100%;
	max-width: 400px;
	padding: 40px 36px;
	position: relative;
	animation: cardIn 0.4s ease;
}

@keyframes cardIn {
	from {
		transform: translateY(30px);
		opacity: 0;
	}

	to {
		transform: translateY(0);
		opacity: 1;
	}
}

.b-login-page__logo {
	display: flex;
	justify-content: center;
	margin-bottom: 16px;
}

.b-login-page__logo-icon {
	width: 56px;
	height: 56px;
	border-radius: 16px;
	background: linear-gradient(135deg, var(--color-primary), #818cf8);
	color: white;
	font-size: 28px;
	font-weight: 800;
	display: flex;
	align-items: center;
	justify-content: center;
	box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.b-login-page__title {
	font-size: 26px;
	text-align: center;
	color: var(--color-text);
	font-weight: 700;
	margin-bottom: 4px;
}

.b-login-page__subtitle {
	text-align: center;
	color: var(--color-text-secondary);
	margin-bottom: 28px;
	font-size: 14px;
}

.b-login-page__form {
	display: flex;
	flex-direction: column;
	gap: 18px;
}

.b-login-page__field {
	display: flex;
	flex-direction: column;
	gap: 6px;
}

.b-login-page__label {
	font-size: 14px;
	font-weight: 600;
	color: var(--color-text);
}

.b-login-page__btn {
	justify-content: center;
	padding: 12px;
	font-size: 15px;
	margin-top: 4px;
	border-radius: var(--radius);
}

.b-login-page__password-wrap {
	position: relative;
	display: flex;
	align-items: center;
}

.b-login-page__password-wrap .input {
	padding-right: 40px;
}

.b-login-page__toggle-pass {
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

.b-login-page__toggle-pass:hover {
	background: var(--color-bg);
	color: var(--color-text);
}

.b-login-page__error--shake {
	animation: shake 0.4s ease;
}

@keyframes shake {

	0%,
	100% {
		transform: translateX(0);
	}

	20% {
		transform: translateX(-8px);
	}

	40% {
		transform: translateX(8px);
	}

	60% {
		transform: translateX(-4px);
	}

	80% {
		transform: translateX(4px);
	}
}

@media (max-width: 767px) {
	.b-login-page__card {
		padding: 28px 20px;
	}

	.b-login-page__title {
		font-size: 22px;
	}

	.b-login-page__subtitle {
		font-size: 13px;
		margin-bottom: 24px;
	}

	.b-login-page__logo-icon {
		width: 48px;
		height: 48px;
		border-radius: 14px;
		font-size: 22px;
	}

	.b-login-page__logo-icon svg {
		width: 24px;
		height: 24px;
	}

	.b-login-page__btn {
		padding: 10px;
		font-size: 14px;
	}

	.b-login-page__shape--1 {
		width: 250px;
		height: 250px;
		top: -60px;
		right: -60px;
	}

	.b-login-page__shape--2 {
		width: 200px;
		height: 200px;
		bottom: -50px;
		left: -50px;
	}

	.b-login-page__shape--3 {
		display: none;
	}
}

@media (min-width: 768px) and (max-width: 1023px) {
	.b-login-page__card {
		max-width: 420px;
		padding: 36px 32px;
	}
}

@media (min-width: 1640px) {
	.b-login-page__card {
		max-width: 440px;
		padding: 48px 40px;
	}

	.b-login-page__title {
		font-size: 28px;
	}
}
</style>
