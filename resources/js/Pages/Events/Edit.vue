<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Aufgabe bearbeiten
                </h2>
                <jet-button class="ml-auto" @click.prevent="deleteEvent">&times; Aufgabe löschen</jet-button>
            </div>

        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <edit-event-form :event="event" :interests="interests" :groups="groups" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import EditEventForm from './EditEventForm'
    import JetButton from '@/Jetstream/Button'

    export default {
        components: {
            AppLayout,
            EditEventForm,
            JetButton
        },
        props: [
            'event',
            'interests',
            'groups'
        ],
        methods: {
            deleteEvent() {
                this.$inertia.delete(this.route('events.destroy', {id: this.event.id}));
            }
        }
    }
</script>
