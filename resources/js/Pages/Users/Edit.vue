<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Benutzer bearbeiten
                </h2>
                <jet-button class="ml-auto" @click.prevent="deleteUser">&times; Benutzer löschen</jet-button>
            </div>

        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <edit-user-form :user="edit_user" :all-interests="all_interests" :all-teams="all_teams" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import JetButton from '@/Jetstream/Button'
    import UpdateProfileInformationForm from '../Profile/UpdateProfileInformationForm'
    import EditUserForm from "@/Pages/Users/EditUserForm.vue";

    export default {
        components: {
            EditUserForm,
            AppLayout,
            UpdateProfileInformationForm,
            JetButton
        },
        props: [
            'edit_user',
            'all_interests',
            'all_teams'
        ],
        methods: {
            deleteUser() {
                this.$inertia.delete(this.route('users.destroy', {id: this.edit_user.id}));
            }
        }
    }
</script>
