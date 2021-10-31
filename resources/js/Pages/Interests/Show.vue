<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ eventGroup.name }}
                </h2>
                <jet-button :post="true" :href="route('eventgroups.participate', eventGroup.id)" class="ml-auto">
                    <template v-if="eventGroup.is_participating">
                        Gruppe verlassen
                    </template>
                    <template v-else>
                        Gruppe beitreten
                    </template>

                </jet-button>
            </div>

        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    <img src="http://via.placeholder.com/500x300" class="sm:mr-12">
                    <div class="mt-6 text-sm">
                        {{ eventGroup.description }}
                    </div>
                </div>

            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import JetButton from '@/Jetstream/Button'

    export default {
        components: {
            AppLayout,
            JetButton
        },
        props: [
            'eventGroup'
        ]
    }
</script>
