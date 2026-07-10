<template>
	<Teleport to="body">
		<Transition name="confirm">
			<div v-if="visible" class="b-confirm-dialog" @click.self="cancel">
				<div class="b-confirm-dialog__card card">
					<div class="b-confirm-dialog__icon">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
							stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path
								d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
							<line x1="12" y1="9" x2="12" y2="13" />
							<line x1="12" y1="17" x2="12.01" y2="17" />
						</svg>
					</div>
					<h3 class="b-confirm-dialog__title">{{ title }}</h3>
					<p class="b-confirm-dialog__message">{{ message }}</p>
					<div class="b-confirm-dialog__actions">
						<button class="btn btn--secondary" @click="cancel">Отмена</button>
						<button class="btn btn--danger" @click="confirm">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
								stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="3 6 5 6 21 6" />
								<path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" />
							</svg>
							Удалить
						</button>
					</div>
				</div>
			</div>
		</Transition>
	</Teleport>
</template>

<script setup>
const props = defineProps({
	visible: Boolean,
	title: { type: String, default: "Подтверждение" },
	message: { type: String, default: "Вы уверены?" },
});

const emit = defineEmits(["confirm", "cancel"]);

function confirm() {
	emit("confirm");
}

function cancel() {
	emit("cancel");
}
</script>

<style scoped>
.b-confirm-dialog {
	position: fixed;
	inset: 0;
	background: rgba(15, 23, 42, 0.5);
	backdrop-filter: blur(4px);
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 20000;
	padding: 16px;
}

.b-confirm-dialog__card {
	width: 100%;
	max-width: 380px;
	padding: 32px 28px 24px;
	text-align: center;
	animation: confirmIn 0.2s ease;
}

@keyframes confirmIn {
	from {
		transform: scale(0.95);
		opacity: 0;
	}

	to {
		transform: scale(1);
		opacity: 1;
	}
}

.b-confirm-dialog__icon {
	width: 48px;
	height: 48px;
	border-radius: 50%;
	background: #fef2f2;
	color: var(--color-danger);
	display: flex;
	align-items: center;
	justify-content: center;
	margin: 0 auto 16px;
}

.b-confirm-dialog__title {
	font-size: 18px;
	font-weight: 700;
	margin-bottom: 8px;
}

.b-confirm-dialog__message {
	font-size: 14px;
	color: var(--color-text-secondary);
	margin-bottom: 24px;
	line-height: 1.5;
}

.b-confirm-dialog__actions {
	display: flex;
	gap: 10px;
	justify-content: center;
}

.b-confirm-dialog__actions .btn {
	min-width: 110px;
	justify-content: center;
}

.confirm-enter-active {
	transition: opacity 0.2s ease;
}

.confirm-leave-active {
	transition: opacity 0.15s ease;
}

.confirm-enter-from,
.confirm-leave-to {
	opacity: 0;
}

@media (max-width: 767px) {
	.b-confirm-dialog {
		padding: 12px;
	}

	.b-confirm-dialog__card {
		max-width: 100%;
		padding: 24px 20px 20px;
	}

	.b-confirm-dialog__actions {
		flex-direction: column-reverse;
	}

	.b-confirm-dialog__actions .btn {
		width: 100%;
	}

	.b-confirm-dialog__title {
		font-size: 16px;
	}

	.b-confirm-dialog__message {
		font-size: 13px;
		margin-bottom: 20px;
	}
}

@media (min-width: 768px) and (max-width: 1023px) {
	.b-confirm-dialog__card {
		max-width: 360px;
		padding: 28px 24px 20px;
	}
}
</style>
