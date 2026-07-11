export default defineNuxtConfig({
	modules: [...(process.env.NUXT_TEST ? ["@nuxt/test-utils/module"] : [])],
	devtools: { enabled: true },
	css: ["~/assets/css/main.css"],
	ssr: false,
	devServer: { port: 5173, host: "0.0.0.0" },
	app: {
		baseURL: "/",
		buildAssetsDir: "/_nuxt/",
		head: {
			title: "To-Do List",
			meta: [
				{ charset: "utf-8" },
				{ name: "viewport", content: "width=device-width, initial-scale=1" },
				{
					name: "description",
					content: "Управляйте своими задачами легко и удобно",
				},
			],
			link: [{ rel: "icon", type: "image/svg+xml", href: "/favicon.svg" }],
		},
	},
	runtimeConfig: {
		public: {
			apiBase: process.env.NUXT_PUBLIC_API_BASE || "/api",
		},
	},
	vite: {
		server: {
			origin: "http://localhost:8000",
			proxy: {
				"/api": {
					target: process.env.API_PROXY_TARGET || "http://localhost:8000",
				},
			},
		},
	},
	compatibilityDate: "2026-07-10",
});
