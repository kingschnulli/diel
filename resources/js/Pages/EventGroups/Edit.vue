<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Aufgabengruppe bearbeiten
                </h2>
                <jet-button class="ml-auto" @click.prevent="deleteEventGroup">&times; Gruppe löschen</jet-button>
            </div>

        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <edit-event-group-form :event-group="eventGroup" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import EditEventGroupForm from './EditEventGroupForm'
    import JetButton from '@/Jetstream/Button'

    export default {
        components: {
            AppLayout,
            EditEventGroupForm,
            JetButton
        },
        props: [
            'eventGroup'
        ],
        methods: {
            deleteEventGroup() {
                this.$inertia.delete(this.route('eventgroups.destroy', {id: this.eventGroup.id}));
            }
        }
    }
</script>
