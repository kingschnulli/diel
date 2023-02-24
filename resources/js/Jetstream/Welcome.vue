<template>
    <div>
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid cols-1 sm:grid-cols-3">
                <div>
                    <Latest :event-users="eventUsers" />
                    <ParticipationChart />
                </div>

                <div class="sm:col-span-2">
                    <h2 class="text-3xl mb-6">Aktuelle Aufgaben</h2>
                    <template v-if="events && events.length">
                        <Event v-for="ev in events" :key="ev.id" :event="ev" />
                    </template>
                    <div v-else>Es stehen aktuell keine Aufgaben an, schau doch später noch einmal vorbei.</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo'
    import Event from "@/Pages/Events/Event";
    import Latest from "../Pages/EventUsers/Latest.vue";
    import ParticipationChart from "@/Pages/Teams/ParticipationChart.vue";
    export default {
        components: {
            ParticipationChart,
            Latest,
            Event,
            JetApplicationLogo
        },
        props: {
            'events': Array,
            'eventUsers': Array
        }
    }
</script>
