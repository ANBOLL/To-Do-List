<template>
  <div class="app-select" ref="wrapper" :class="{ focused, open: opened }" @click="toggle">
    <div class="trigger">
      <span class="value">{{ selectedLabel }}</span>
      <svg class="chevron" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
      </svg>
    </div>
    <Transition name="dropdown">
      <div v-if="opened" class="menu">
        <div
          v-for="opt in options"
          :key="opt.value"
          class="option"
          :class="{ selected: opt.value === modelValue, disabled: opt.disabled }"
          @click.stop="select(opt)"
        >
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
.app-select {
  position: relative;
  flex: 1;
  min-width: 140px;
  user-select: none;
}
.trigger {
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
.trigger:hover, .focused .trigger {
  border-color: var(--color-primary);
}
.focused .trigger {
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}
.value { flex: 1; }
.chevron {
  width: 18px;
  height: 18px;
  color: var(--color-text-secondary);
  transition: transform 0.2s ease;
}
.open .chevron { transform: rotate(180deg); }
.menu {
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
.option {
  padding: 8px 12px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.1s;
}
.option:hover { background: #f1f5f9; }
.option.selected { color: var(--color-primary); font-weight: 600; background: #eef2ff; }
.option.disabled { opacity: 0.4; cursor: default; }
.dropdown-enter-active, .dropdown-leave-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-4px); }
</style>
