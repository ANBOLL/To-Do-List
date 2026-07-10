<template>
	<div class="b-layout">
		<header v-if="user" class="b-layout__header">
			<div class="container b-layout__header-inner">
				<div class="b-layout__brand">
					<div class="b-layout__logo-icon">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
							stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M13 5h8m-8 7h8m-8 7h8M3 17l2 2l4-4" />
							<rect width="6" height="6" x="3" y="4" rx="1" />
						</svg>
					</div>
					<h1 class="b-layout__logo">To-Do List</h1>
				</div>
				<nav class="b-layout__nav">
					<Transition name="fade">
						<button v-if="isSticky" class="btn btn--primary b-layout__sticky-btn" @click="triggerCreate">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
								<path d="M8 3v10M3 8h10" stroke="currentColor" stroke-width="2"
									stroke-linecap="round" />
							</svg>
							<span class="b-layout__add-text">Новая</span>
						</button>
					</Transition>
					<div class="b-layout__user-badge" @click="showProfileForm = true">
						<div class="b-layout__user-avatar">
							<span class="b-layout__avatar-letter">{{ user.name.charAt(0).toUpperCase() }}</span>
							<div class="b-layout__avatar-edit">
								<svg width="16" height="16" viewBox="0 0 24 24">
									<path fill="currentColor"
										d="M4 20v-2.8q0-.85.438-1.562T5.6 14.55q1.55-.775 3.15-1.162T12 13q.925 0 1.825.113t1.8.362l-1.675 1.7q-.5-.075-.975-.125T12 15q-1.4 0-2.775.338T6.5 16.35q-.225.125-.363.35T6 17.2v.8h6v2zm10 1v-3.075l5.525-5.5q.225-.225.5-.325t.55-.1q.3 0 .575.113t.5.337l.925.925q.2.225.313.5t.112.55t-.1.563t-.325.512l-5.5 5.5zm7.5-6.575l-.925-.925zm-6 5.075h.95l3.025-3.05l-.45-.475l-.475-.45l-3.05 3.025zm3.525-3.525l-.475-.45l.925.925zM12 12q-1.65 0-2.825-1.175T8 8t1.175-2.825T12 4t2.825 1.175T16 8t-1.175 2.825T12 12m0-2q.825 0 1.413-.587T14 8t-.587-1.412T12 6t-1.412.588T10 8t.588 1.413T12 10m0-2" />
								</svg>
							</div>
						</div>
						<div class="b-layout__user-meta">
							<span class="b-layout__user-name">{{ user.name }}</span>
							<span class="b-layout__user-role">
								{{ user.is_admin ? 'Администратор' : 'Пользователь' }}
							</span>
						</div>
					</div>

					<ProfileForm v-if="showProfileForm" :user="user" :loading="profileLoading" :error="profileError"
						@submit="handleProfileUpdate" @cancel="showProfileForm = false" />
					<button class="btn btn--secondary b-layout__logout-btn" @click="logout">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h3M11 11l3-3-3-3M14 8H6" stroke="currentColor"
								stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						<span class="b-layout__logout-text">Выйти</span>
					</button>
				</nav>
			</div>
		</header>
		<main class="b-layout__main">
			<div class="container">
				<slot />
			</div>
		</main>
	</div>
</template>

<script setup>
const { user, logout, updateProfile } = useAuth()
const { isSticky, triggerCreate } = useStickyButton()

const showProfileForm = ref(false)
const profileLoading = ref(false)
const profileError = ref('')

async function handleProfileUpdate(data) {
	profileLoading.value = true
	profileError.value = ''
	const result = await updateProfile(data)
	profileLoading.value = false
	if (result.success) {
		showProfileForm.value = false
	} else {
		profileError.value = result.error
	}
}
</script>

<style scoped>
.b-layout {
	min-height: 100vh;
	display: flex;
	flex-direction: column;
}

.b-layout__header {
	background: rgba(255, 255, 255, 0.85);
	backdrop-filter: blur(12px);
	-webkit-backdrop-filter: blur(12px);
	border-bottom: 1px solid var(--color-border);
	padding: 0;
	position: sticky;
	top: 0;
	z-index: 100;
}

.b-layout__header-inner {
	display: flex;
	align-items: center;
	justify-content: space-between;
	height: 64px;
}

.b-layout__brand {
	display: flex;
	align-items: center;
	gap: 12px;
}

.b-layout__logo-icon {
	width: 36px;
	height: 36px;
	border-radius: 10px;
	background: linear-gradient(135deg, var(--color-primary), #818cf8);
	color: white;
	font-size: 18px;
	font-weight: 800;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}

.b-layout__logo {
	font-size: 20px;
	font-weight: 700;
	color: var(--color-text);
}

.b-layout__nav {
	display: flex;
	align-items: center;
	gap: 16px;
}

.b-layout__user-badge {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 4px 12px 4px 4px;
	background: var(--color-bg);
	border-radius: 999px;
	cursor: pointer;
	transition: background 0.15s ease;
}

.b-layout__user-badge:hover {
	background: var(--color-border);
}

.b-layout__user-avatar {
	width: 32px;
	height: 32px;
	border-radius: 50%;
	background: linear-gradient(135deg, var(--color-primary), #818cf8);
	color: white;
	font-size: 14px;
	font-weight: 700;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
	position: relative;
	overflow: hidden;
}

.b-layout__avatar-letter {
	transition: opacity 0.2s ease;
}

.b-layout__avatar-edit {
	position: absolute;
	inset: 0;
	display: flex;
	align-items: center;
	justify-content: center;
	background: rgba(0, 0, 0, 0.5);
	opacity: 0;
	transition: opacity 0.2s ease;
}

.b-layout__user-badge:hover .b-layout__avatar-letter {
	opacity: 0;
}

.b-layout__user-badge:hover .b-layout__avatar-edit {
	opacity: 1;
}

.b-layout__user-meta {
	display: flex;
	flex-direction: column;
	line-height: 1.2;
}

.b-layout__user-name {
	font-size: 13px;
	font-weight: 600;
	color: var(--color-text);
}

.b-layout__user-role {
	font-size: 11px;
	color: var(--color-text-secondary);
}

.b-layout__logout-btn {
	padding: 8px 14px;
	font-size: 13px;
}

.b-layout__sticky-btn {
	padding: 8px 14px;
	font-size: 13px;
	white-space: nowrap;
}

.b-layout__main {
	flex: 1;
	padding: 28px 0;
}

@media (max-width: 767px) {
	.b-layout__header-inner {
		height: auto;
		padding: 10px 12px;
	}

	.b-layout__user-meta {
		display: none;
	}

	.b-layout__user-badge {
		padding: 4px;
	}

	.b-layout__logout-text {
		display: none;
	}

	.b-layout__logout-btn {
		padding: 8px 10px;
	}

	.b-layout__add-text {
		display: none;
	}

	.b-layout__main {
		padding: 16px 0;
	}
}

@media (min-width: 768px) and (max-width: 1023px) {
	.b-layout__header-inner {
		height: 60px;
	}

	.b-layout__main {
		padding: 20px 0;
	}
}

@media (min-width: 1640px) {
	.b-layout__header-inner {
		height: 72px;
	}

	.b-layout__main {
		padding: 32px 0;
	}
}
</style>
