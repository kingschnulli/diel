<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Interesse bearbeiten
                </h2>
                <jet-button class="ml-auto" @click.prevent="deleteInterest">&times; Interesse löschen</jet-button>
            </div>

        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <edit-interest-form :interest="interest" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import EditInterestForm from './EditInterestForm'
    import JetButton from '@/Jetstream/Button'

    export default {
        components: {
            AppLayout,
            EditInterestForm,
            JetButton
        },
        props: [
            'interest'
        ],
        methods: {
            deleteInterest() {
                this.$inertia.delete(this.route('interests.destroy', {id: this.interest.id}));
            }
        }
    }
</script>
