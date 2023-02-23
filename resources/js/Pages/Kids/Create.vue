<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kind hinzufügen
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <create-kid-form :team="team" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import CreateKidForm from './CreateKidForm'

    export default {
        props: ['team'],
        components: {
            AppLayout,
            CreateKidForm,
        },
    }
</script>
