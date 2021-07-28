<template>
    <jet-action-section>
        <template #title>
            Familie löschen
        </template>

        <template #description>
            Familie dauerhaft löschen.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Sobald eine Familie gelöscht wurde, werden alle zugehörigen Daten mit gelöscht. Bitte sichern Sie sich
                vorher alle Daten die Sie behalten möchten.
            </div>

            <div class="mt-5">
                <jet-danger-button @click="confirmTeamDeletion">
                    Familie löschen
                </jet-danger-button>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <jet-confirmation-modal :show="confirmingTeamDeletion" @close="confirmingTeamDeletion = false">
                <template #title>
                    Familie löschen
                </template>

                <template #content>
                    Wollen Sie diese Familie wirklich löschen. Alle zugehörigen Daten werden dauerhaft gelöscht..
                </template>

                <template #footer>
                    <jet-secondary-button @click="confirmingTeamDeletion = false">
                        Abbruch
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click="deleteTeam" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Familie löschen
                    </jet-danger-button>
                </template>
            </jet-confirmation-modal>
        </template>
    </jet-action-section>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        props: ['team'],

        components: {
            JetActionSection,
            JetConfirmationModal,
            JetDangerButton,
            JetSecondaryButton,
        },

        data() {
            return {
                confirmingTeamDeletion: false,
                deleting: false,

                form: this.$inertia.form()
            }
        },

        methods: {
            confirmTeamDeletion() {
                this.confirmingTeamDeletion = true
            },

            deleteTeam() {
                this.form.delete(route('teams.destroy', this.team), {
                    errorBag: 'deleteTeam'
                });
            },
        },
    }
</script>
