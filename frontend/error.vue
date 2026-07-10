<template>
	<div class="b-error-page">
		<div class="b-error-page__shapes">
			<div class="b-error-page__shape b-error-page__shape--1" />
			<div class="b-error-page__shape b-error-page__shape--2" />
			<div class="b-error-page__shape b-error-page__shape--3" />
		</div>
		<div class="b-error-page__card card">
			<div class="b-error-page__code">404</div>
			<div class="b-error-page__icon">
				<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"
					stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="10" />
					<path d="M16 16s-1.5-2-4-2-4 2-4 2" />
					<line x1="9" y1="9" x2="9.01" y2="9" />
					<line x1="15" y1="9" x2="15.01" y2="9" />
				</svg>
			</div>
			<h1 class="b-error-page__title">Страница не найдена</h1>
			<p class="b-error-page__text">
				Возможно, она была удалена или вы перешли по неверной ссылке.
			</p>
			<div class="b-error-page__actions">
				<button class="btn btn--primary b-error-page__btn" @click="goHome">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
						<path d="M1 8l7-6 7 6M3 6.5V14a1 1 0 001 1h2v-4a1 1 0 011-1h2a1 1 0 011 1v4h2a1 1 0 001-1V6.5"
							stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
					На главную
				</button>
				<button class="btn btn--secondary b-error-page__btn" @click="goBack">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
						<path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
							stroke-linejoin="round" />
					</svg>
					Назад
				</button>
			</div>
		</div>
	</div>
</template>

<script setup>
definePageMeta({ layout: false })

const router = useRouter()

function goHome() {
	router.push('/dashboard')
}

function goBack() {
	router.back()
}
</script>

<style scoped>
.b-error-page {
	min-height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 60%, #6366f1 100%);
	padding: 16px;
	position: relative;
	overflow: hidden;
}

.b-error-page__shapes {
	position: absolute;
	inset: 0;
	pointer-events: none;
	overflow: hidden;
}

.b-error-page__shape {
	position: absolute;
	border-radius: 50%;
	opacity: 0.08;
}

.b-error-page__shape--1 {
	width: 500px;
	height: 500px;
	background: radial-gradient(circle, #818cf8, transparent);
	top: -150px;
	right: -150px;
	animation: float 8s ease-in-out infinite;
}

.b-error-page__shape--2 {
	width: 350px;
	height: 350px;
	background: radial-gradient(circle, #a78bfa, transparent);
	bottom: -100px;
	left: -100px;
	animation: float 10s ease-in-out infinite reverse;
}

.b-error-page__shape--3 {
	width: 250px;
	height: 250px;
	background: radial-gradient(circle, #c4b5fd, transparent);
	top: 40%;
	left: 60%;
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

.b-error-page__card {
	width: 100%;
	max-width: 440px;
	padding: 48px 40px 40px;
	text-align: center;
	position: relative;
	animation: cardIn 0.5s ease;
}

@keyframes cardIn {
	from {
		transform: translateY(30px) scale(0.96);
		opacity: 0;
	}

	to {
		transform: translateY(0) scale(1);
		opacity: 1;
	}
}

.b-error-page__code {
	font-size: 100px;
	font-weight: 900;
	line-height: 1;
	background: linear-gradient(135deg, var(--color-primary), #a78bfa);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
	background-clip: text;
	margin-bottom: 8px;
	letter-spacing: -4px;
}

.b-error-page__icon {
	color: var(--color-text-secondary);
	opacity: 0.3;
	margin-bottom: 20px;
}

.b-error-page__title {
	font-size: 22px;
	font-weight: 700;
	color: var(--color-text);
	margin-bottom: 10px;
}

.b-error-page__text {
	font-size: 14px;
	color: var(--color-text-secondary);
	line-height: 1.6;
	margin-bottom: 32px;
	max-width: 300px;
	margin-left: auto;
	margin-right: auto;
}

.b-error-page__actions {
	display: flex;
	gap: 12px;
	justify-content: center;
}

.b-error-page__btn {
	padding: 11px 22px;
	font-size: 14px;
	gap: 8px;
}
</style>
