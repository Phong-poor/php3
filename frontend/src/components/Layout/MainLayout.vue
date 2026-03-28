<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Chatbot from '../Web/Chatbot.vue'


const route = useRoute()

const showChatbot = computed(() =>
    ['/', '/products', '/news', '/contact'].includes(route.path)
)
</script>

<template>
    <Header />
    <router-view />
    <Footer />
    <Chatbot v-if="showChatbot" />
</template>