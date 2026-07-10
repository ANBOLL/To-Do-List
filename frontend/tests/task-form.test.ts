import { describe, it, expect } from "vitest";
import { mount } from "@vue/test-utils";
import TaskForm from "../components/TaskForm.vue";

describe("TaskForm", () => {
	it("renders create mode by default", () => {
		const wrapper = mount(TaskForm, {
			props: { loading: false },
			attachTo: document.body,
		});
		expect(document.body.querySelector("h3")?.textContent).toBe("Новая задача");
		expect(
			document.body.querySelector('button[type="submit"]')?.textContent
		).toContain("Создать");
		wrapper.unmount();
	});

	it("renders edit mode when task prop provided", () => {
		const wrapper = mount(TaskForm, {
			props: {
				task: {
					id: 1,
					title: "Test",
					description: "Desc",
					due_date: "2026-07-20",
					status: "in_progress",
				},
				loading: false,
			},
			attachTo: document.body,
		});
		expect(document.body.querySelector("h3")?.textContent).toBe(
			"Редактировать задачу"
		);
		expect(
			document.body.querySelector('button[type="submit"]')?.textContent
		).toContain("Сохранить");
		wrapper.unmount();
	});

	it("shows error message when error prop is set", () => {
		const wrapper = mount(TaskForm, {
			props: { error: "Something went wrong", loading: false },
			attachTo: document.body,
		});
		expect(document.body.textContent).toContain("Something went wrong");
		wrapper.unmount();
	});

	it("emits submit with form data", async () => {
		const wrapper = mount(TaskForm, {
			props: { loading: false },
			attachTo: document.body,
		});
		const input = document.body.querySelector("input") as HTMLInputElement;
		input.value = "New Task Title";
		await input.dispatchEvent(new Event("input"));
		(document.body.querySelector("form") as HTMLFormElement).dispatchEvent(
			new Event("submit")
		);

		const emitted = wrapper.emitted("submit");
		expect(emitted).toHaveLength(1);
		expect(emitted![0]![0]).toMatchObject({
			title: "New Task Title",
		});
		wrapper.unmount();
	});

	it("emits cancel when cancel is clicked", async () => {
		const wrapper = mount(TaskForm, {
			props: { loading: false },
			attachTo: document.body,
		});
		const cancelBtn = document.body.querySelector(
			".btn--secondary"
		) as HTMLButtonElement;
		cancelBtn.click();
		expect(wrapper.emitted("cancel")).toBeTruthy();
		wrapper.unmount();
	});
});
