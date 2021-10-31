<template>
    <div>
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid cols-1 sm:grid-cols-3">
                <div>
                    <h2 class="text-3xl mb-6">Aktivitäten</h2>
                    <div class="mb-3 text-sm">
                        <div>18.06.2021 - Ackerbuddy</div>
                        <div>Familie Meyer will sich beteiligen</div>
                    </div>
                    <div class="mb-3 text-sm">
                        <div>18.06.2021 - Ackerbuddy</div>
                        <div>Familie Meyer will sich beteiligen</div>
                    </div>
                    <div class="mb-3 text-sm">
                        <div>18.06.2021 - Ackerbuddy</div>
                        <div>Familie Meyer will sich beteiligen</div>
                    </div>
                    <div class="mb-3 text-sm">
                        <div>18.06.2021 - Ackerbuddy</div>
                        <div>Familie Meyer will sich beteiligen</div>
                    </div>
                    <div class="mb-3 text-sm">
                        <div>18.06.2021 - Ackerbuddy</div>
                        <div>Familie Meyer will sich beteiligen</div>
                    </div>
                    <div class="mb-3 text-sm">
                        <div>18.06.2021 - Ackerbuddy</div>
                        <div>Familie Meyer will sich beteiligen</div>
                    </div>

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
    export default {
        components: {
            Event,
            JetApplicationLogo
        },
        props: {
            'events': Array
        }
    }
</script>
