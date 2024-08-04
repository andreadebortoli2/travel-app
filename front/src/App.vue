<script>
import { RouterLink, RouterView } from 'vue-router';
import Header from './components/partials/Header.vue';

export default {
    name: 'App',
    components: {
        Header
    }
}
</script>

<template>
    <Header />
    <main>
        <RouterView />
    </main>
</template>

<style scoped></style>
