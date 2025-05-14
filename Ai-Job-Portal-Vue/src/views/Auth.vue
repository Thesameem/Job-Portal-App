<script setup>
import { useRouter } from 'vue-router'
import { onMounted, ref } from 'vue'
import Cookie from '@/scripts/Cookie'

let LoginPage = ref(true)
let router = useRouter()

import LogIn from '@/components/Auth/LogIn.vue'
import SignUp from '@/components/Auth/SignUp.vue'

const ShowSignupPage = () => {
  LoginPage.value = false
}

onMounted(() => {
  let Token = Cookie.getCookie('job-app')
  if (Token)
    router.push({
      path: '/',
    })
})
</script>

<template>
  <div>
    <LogIn v-if="LoginPage" @signup="LoginPage = false" />
    <SignUp v-else @login="LoginPage = true" />
  </div>

  <!-- <LogIn />
  <SignUp /> -->
</template>
