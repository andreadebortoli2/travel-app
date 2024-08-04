<script>
export default {
    name: 'Header'
}
</script>

<template>
    <header class="bg-info text-warning">
        <div class="d-flex justify-content-between align-items-center px-4">
            <RouterLink :to="{ name: 'home' }">
                <h2 class="text-warning">Travel-App</h2>
            </RouterLink>
            <RouterLink :to="{ name: 'home' }">
                <h5 class="text-success">Travels list</h5>
            </RouterLink>
        </div>
    </header>
</template>

<style scoped></style>