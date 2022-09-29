<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Elternzeiterfassung
                </h2>
                <jet-button  class="ml-auto" type="link" :href="route('participations.create')">+ Zeit erfassen</jet-button>
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
                        :meta="participations"
                    >
                        <template #head>
                            <tr>
                                <th v-if="$page.props.user.admin">Benutzer</th>
                                <th>Aufgabe</th>
                                <th>Beschreibung</th>
                                <th>Datum</th>
                                <th>Zeitaufwand</th>
                                <th></th>
                            </tr>
                        </template>

                        <template #body>
                            <tr class="cursor-pointer" v-for="participation in participations.data" :key="participation.id" @click="$inertia.visit(route('participations.edit', {id: participation.id}))">
                                <td v-if="$page.props.user.admin">{{ participation.user.name }}</td>
                                <td>{{ participation.event ? participation.event.name : '-' }}</td>
                                <td>{{ participation.description }}</td>
                                <td>{{ participation.participation_date }}</td>
                                <td>{{ participation.minutes }}</td>
                                <td class="w-px">
                                    <div class="px-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="block w-6 h-6 fill-gray-400"><polygon points="12.95 10.707 13.657 10 8 4.343 6.586 5.757 10.828 10 6.586 14.243 8 15.657 12.95 10.707"></polygon></svg>
                                    </div>
                                </td>
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
        participations: Object
    }
}
</script>
