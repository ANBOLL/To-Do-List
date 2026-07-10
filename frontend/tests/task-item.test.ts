import { describe, it, expect } from "vitest";
import { mount } from "@vue/test-utils";
import TaskItem from "../components/TaskItem.vue";

describe("TaskItem", () => {
	const baseTask = {
		id: 1,
		user_id: 2,
		title: "Test Task",
		description: "Description text",
		due_date: "2026-07-20",
		status: "pending",
		created_at: "2026-07-10T16:55:36.000000Z",
		updated_at: "2026-07-10T16:55:36.000000Z",
		user: { id: 2, email: "user@test.com", is_admin: false },
	};

	it("renders task title", () => {
		const wrapper = mount(TaskItem, {
			props: { task: baseTask, currentUser: { id: 2, is_admin: false } },
		});
		expect(wrapper.text()).toContain("Test Task");
	});

	it("shows edit/delete buttons for owner", () => {
		const wrapper = mount(TaskItem, {
			props: { task: baseTask, currentUser: { id: 2, is_admin: false } },
		});
		expect(wrapper.text()).toContain("Изменить");
		expect(wrapper.text()).toContain("Удалить");
	});

	it("hides edit/delete buttons for non-owner", () => {
		const wrapper = mount(TaskItem, {
			props: { task: baseTask, currentUser: { id: 3, is_admin: false } },
		});
		expect(wrapper.text()).not.toContain("Изменить");
		expect(wrapper.text()).not.toContain("Удалить");
	});

	it("shows edit/delete for admin even if not owner", () => {
		const wrapper = mount(TaskItem, {
			props: { task: baseTask, currentUser: { id: 1, is_admin: true } },
		});
		expect(wrapper.text()).toContain("Изменить");
		expect(wrapper.text()).toContain("Удалить");
	});

	it("shows due date when present", () => {
		const wrapper = mount(TaskItem, {
			props: { task: baseTask, currentUser: { id: 2, is_admin: false } },
		});
		expect(wrapper.text()).toContain("июля");
	});

	it("emits delete event", async () => {
		const wrapper = mount(TaskItem, {
			props: { task: baseTask, currentUser: { id: 2, is_admin: false } },
		});
		await wrapper.find("button.btn--danger").trigger("click");
		expect(wrapper.emitted("delete")).toBeTruthy();
	});

	it("emits edit event", async () => {
		const wrapper = mount(TaskItem, {
			props: { task: baseTask, currentUser: { id: 2, is_admin: false } },
		});
		await wrapper.find("button.btn--secondary").trigger("click");
		expect(wrapper.emitted("edit")).toBeTruthy();
	});
});
