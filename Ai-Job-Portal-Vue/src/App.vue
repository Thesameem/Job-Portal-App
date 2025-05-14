<script setup>
import { RouterView } from 'vue-router'
import Header from './components/reusable/Header.vue'
import Footer from './components/reusable/Footer.vue'
</script>

<template>
  <Header />
  <RouterView />
  <Footer/>
</template>
