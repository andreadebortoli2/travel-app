<script>
import { RouterLink, RouterView } from 'vue-router';

export default {
    name: 'App',
}
</script>

<template>
    <header class="bg-info text-warning">
        <div class="d-flex justify-content-between px-4">
            <h2>header</h2>
            <RouterLink :to="{ name: 'home' }">
                <h2>BACK</h2>
            </RouterLink>
        </div>
    </header>
    <main>
        <RouterView />
    </main>
</template>

<style scoped></style>
