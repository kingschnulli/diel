<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Aufgabe bearbeiten
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <edit-event-form :event="event" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import EditEventForm from './EditEventForm'

    export default {
        components: {
            AppLayout,
            EditEventForm,
        },
        props: [
            'event'
        ],
    }
</script>
