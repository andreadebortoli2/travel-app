<script>
export default {
    name: 'Header'
}
</script>

<template>
    <header>
        <div class="d-flex justify-content-between align-items-center container pt-2">
            <RouterLink :to="{ name: 'home' }">
                <h2>Travel-App</h2>
            </RouterLink>
            <RouterLink :to="{ name: 'home' }">
                <h5 class="btn btn-sm btn-outline-info text-success"><strong>Travels list</strong></h5>
            </RouterLink>
        </div>
    </header>
</template>

<style scoped>
h2 {
    font-size: 3rem;
    color: rgb(144, 218, 249);
}
</style>