<template>
    <h2 class="text-3xl mb-6">Aktivitäten</h2>
    <div class="mb-3 text-sm" v-for="eventUser in eventUsers">
        <div>{{ formatDateString(eventUser.created_at) }} - {{ eventUser.event.name }}</div>
        <div>{{ eventUser.user.name }} will sich beteiligen</div>
    </div>
</template>

<script>
export default {
    name: "Latest",
    props: {
        'eventUsers': Array
    },
    methods: {
        formatDateString(dateString) {
            return new Date(dateString).toLocaleDateString();
        }
    }
}
</script>

<style scoped>

</style>
