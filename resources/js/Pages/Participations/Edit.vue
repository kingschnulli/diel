<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Zeiteintrag bearbeiten
                </h2>
                <jet-button class="ml-auto" @click.prevent="deleteParticipation">&times; Zeiteintrag löschen</jet-button>
            </div>

        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <edit-participation-form :participation="participation" :events="events" :users="users" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import EditParticipationForm from './EditParticipationForm'
    import JetButton from '@/Jetstream/Button'

    export default {
        components: {
            AppLayout,
            EditParticipationForm,
            JetButton
        },
        props: [
            'participation',
            'events',
            'users'
        ],
        methods: {
            deleteParticipation() {
                this.$inertia.delete(this.route('participations.destroy', {id: this.participation.id}));
            }
        }
    }
</script>
