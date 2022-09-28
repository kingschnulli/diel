<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-8 mb-4 pb-4 border-gray-100 border-b">
        <img :src="event.image_url" class="sm:mr-12">
        <div class="flex">
            <div class="flex flex-col">
                <h3>{{ event.name }}</h3>
                <small class="text-gray-300">
                    <span v-for="interest in event.interests">#{{ interest.name }}&nbsp;</span>
                </small>
                <div class="mt-6 text-sm">
                    {{ event.description }}
                </div>
                <jet-button class="ml-auto mt-3" type="link" :href="route('events.show', event.id)">Ansehen</jet-button>
            </div>
            <div class="flex w-20 justify-end">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                </svg>
                <span :class="event.users.length > event.quota ? 'text-red-300' : 'text-green-300'" class="ml-2">{{ event.users.length }}/{{ event.quota }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import JetButton from '@/Jetstream/Button'

export default {
    name: "Event",
    components: {
        JetButton
    },
    props: {
        event: Object
    }
}
</script>

<style scoped>

</style>
