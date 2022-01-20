<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ event.name }}
                </h2>
                <jet-button :post="true" :href="route('events.participate', event.id)" class="ml-auto">
                    <template v-if="event.is_participating">
                        Nicht mehr teilnehmen
                    </template>
                    <template v-else>
                        Teilnehmen
                    </template>

                </jet-button>
            </div>

        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    <img :src="event.image_url" class="sm:mr-12">
                    <small class="text-gray-300">
                        <span v-for="interest in event.interests">#{{ interest.name }}&nbsp;</span>
                    </small>
                    <div class="mt-6 text-sm" v-html="event.long_description">
                    </div>
                    <div class="text-sm">
                        Beginn: {{ $dateTime(event.start_date) }}
                    </div>
                    <div class="text-sm">
                        Ende: {{ $dateTime(event.end_date) }}
                    </div>
                </div>

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
            'event'
        ]
    }
</script>
