export default defineNuxtRouteMiddleware(async (to, from) => {
  if (process.server) return

  const { token, fetchUser } = useAuth()

  if (!token.value) {
    return navigateTo('/login')
  }

  const user = await fetchUser()
  if (!user) {
    return navigateTo('/login')
  }
})
