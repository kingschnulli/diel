<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Aufgabe erstellen
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <create-event-form :interests="interests" :groups="groups"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import CreateEventForm from './CreateEventForm'

    export default {
        props: ['interests', 'groups'],
        components: {
            AppLayout,
            CreateEventForm,
        },
    }
</script>
