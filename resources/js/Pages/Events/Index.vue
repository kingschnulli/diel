<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Aufgaben
                </h2>
                <jet-button class="ml-auto" type="link" :href="route('events.create')">+ Neue Aufgabe</jet-button>
            </div>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                    <Table
                        :filters="queryBuilderProps.filters"
                        :search="queryBuilderProps.search"
                        :columns="queryBuilderProps.columns"
                        :on-update="setQueryBuilder"
                        :meta="events"
                    >
                        <template #head>
                            <tr>
                                <th @click.prevent="sortBy('name')">Name</th>
                                <th v-show="showColumn('start_date')" @click.prevent="sortBy('start_date')">Beginn</th>
                                <th v-show="showColumn('end_date')" @click.prevent="sortBy('end_date')">Ende</th>
                                <th v-show="showColumn('description')" @click.prevent="sortBy('description')">Beschreibung</th>
                            </tr>
                        </template>

                        <template #body>
                            <tr v-for="event in events.data" :key="event.id" @click="$inertia.visit(route('events.edit', {id: event.id}))">
                                <td>{{ event.name }}</td>
                                <td v-show="showColumn('start_date')">{{ event.start_date }}</td>
                                <td v-show="showColumn('end_date')">{{ event.end_date }}</td>
                                <td v-show="showColumn('description')">{{ event.description }}</td>
                            </tr>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from '@/Jetstream/Button'
import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';

export default {
    mixins: [InteractsWithQueryBuilder],

    components: {
        AppLayout,
        Table: Tailwind2.Table,
        JetButton
    },

    props: {
        events: Object
    }
}
</script>

<style scoped>

</style>
