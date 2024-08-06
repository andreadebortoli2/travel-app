<script>
export default {
    name: 'Header'
}
</script>

<template>
    <header>
        <div class="d-flex justify-content-between align-items-center container pt-2">
            <RouterLink :to="{ name: 'home' }">
                <h2 class="text-danger">Travel-App</h2>
            </RouterLink>
            <RouterLink :to="{ name: 'home' }">
                <h5 class="btn btn-sm btn-outline-success text-success">Travels list</h5>
            </RouterLink>
        </div>
    </header>
</template>

<style scoped></style>