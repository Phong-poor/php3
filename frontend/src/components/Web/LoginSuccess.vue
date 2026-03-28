<script setup>
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

onMounted(() => {
  const token = route.query.token

  if (token) {
    localStorage.setItem('token', token)

    // 👉 gọi API lấy user
    fetchUser(token)
  } else {
    router.push('/login')
  }
})

const fetchUser = async (token) => {
  try {
    const res = await fetch('http://127.0.0.1:8000/api/user/profile', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    const user = await res.json()

    localStorage.setItem('user', JSON.stringify(user))

    router.push('/')
  } catch (e) {
    router.push('/login')
  }
}
</script>