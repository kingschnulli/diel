<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Benutzer anlegen
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <create-user-form :all-interests="all_interests" :all-teams="all_teams" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import CreateUserForm from './CreateUserForm'

    export default {
        props: ['all_interests', 'all_teams'],
        components: {
            AppLayout,
            CreateUserForm,
        },
    }
</script>
