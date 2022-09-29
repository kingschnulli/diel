<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Zeit erfassen
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <create-participation-form :events="events" :users="users"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import CreateParticipationForm from './CreateParticipationForm'

    export default {
        props: ['events', 'users'],
        components: {
            AppLayout,
            CreateParticipationForm,
        },
    }
</script>
