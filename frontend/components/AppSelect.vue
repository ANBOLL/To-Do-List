<template>
	<div ref="wrapper" class="b-app-select" :class="{
		'b-app-select--focused': focused,
		'b-app-select--open': opened,
		'b-app-select--upward': upward,
	}" @click="toggle">
		<div class="b-app-select__trigger">
			<span class="b-app-select__value">{{ selectedLabel }}</span>
			<svg class="b-app-select__chevron" viewBox="0 0 20 20" fill="currentColor">
				<path fill-rule="evenodd"
					d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
					clip-rule="evenodd" />
			</svg>
		</div>
		<Transition name="dropdown">
			<div v-if="opened" class="b-app-select__menu">
				<div v-for="opt in options" :key="opt.value" class="b-app-select__option" :class="{
					'b-app-select__option--selected': opt.value === modelValue,
					'b-app-select__option--disabled': opt.disabled,
				}" @click.stop="select(opt)">
					{{ opt.label }}
				</div>
			</div>
		</Transition>
	</div>
</template>

<script setup>
const props = defineProps({
	modelValue: [String, Number],
	options: { type: Array, required: true },
	upward: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue'])

const opened = ref(false)
const focused = ref(false)
const wrapper = ref(null)

const selectedLabel = computed(() => {
	const opt = props.options.find(o => o.value === props.modelValue)
	return opt ? opt.label : '—'
})

function toggle() {
	opened.value = !opened.value
}

function select(opt) {
	if (opt.disabled) return
	emit('update:modelValue', opt.value)
	opened.value = false
}

function handleClickOutside(e) {
	if (wrapper.value && !wrapper.value.contains(e.target)) {
		opened.value = false
	}
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
.b-app-select {
	position: relative;
	flex: 1;
	min-width: 140px;
	user-select: none;
}

.b-app-select__trigger {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 8px 12px;
	border: 1px solid var(--color-border);
	border-radius: var(--radius);
	font-size: 14px;
	cursor: pointer;
	background: var(--color-surface);
	color: var(--color-text);
	transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.b-app-select__trigger:hover,
.b-app-select--focused .b-app-select__trigger {
	border-color: var(--color-primary);
}

.b-app-select--focused .b-app-select__trigger {
	box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.b-app-select__value {
	flex: 1;
}

.b-app-select__chevron {
	width: 18px;
	height: 18px;
	color: var(--color-text-secondary);
	transition: transform 0.2s ease;
}

.b-app-select--open .b-app-select__chevron {
	transform: rotate(180deg);
}

.b-app-select__menu {
	position: absolute;
	top: calc(100% + 4px);
	left: 0;
	right: 0;
	background: var(--color-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius);
	box-shadow: var(--shadow-lg);
	z-index: 9999;
	overflow: hidden;
}

.b-app-select--upward .b-app-select__menu {
	top: auto;
	bottom: calc(100% + 4px);
}

.b-app-select__option {
	padding: 8px 12px;
	font-size: 14px;
	cursor: pointer;
	transition: background 0.1s;
}

.b-app-select__option:hover {
	background: #f1f5f9;
}

.b-app-select__option--selected {
	color: var(--color-primary);
	font-weight: 600;
	background: #eef2ff;
}

.b-app-select__option--disabled {
	opacity: 0.4;
	cursor: default;
}

.dropdown-enter-active,
.dropdown-leave-active {
	transition: opacity 0.15s ease, transform 0.15s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
	opacity: 0;
	transform: translateY(-4px);
}
</style>
