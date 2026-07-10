import { ref } from 'vue'

const isSticky = ref(false)
const createSignal = ref(0)

export function useStickyButton() {
	function setSticky(val) {
		isSticky.value = val
	}

	function triggerCreate() {
		createSignal.value++
	}

	return {
		isSticky,
		createSignal,
		setSticky,
		triggerCreate,
	}
}
