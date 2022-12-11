<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Familien
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                    <Table
                        :filters="queryBuilderProps.filters"
                        :search="queryBuilderProps.search"
                        :columns="queryBuilderProps.columns"
                        :on-update="setQueryBuilder"
                        :meta="teams"
                    >
                        <template #head>
                            <tr>
                                <th v-show="showColumn('name')" @click.prevent="sortBy('name')">Name</th>
                                <th>Rating</th>
                                <th v-show="showColumn('quota_month_target')">Stunden Plan</th>
                                <th v-show="showColumn('quota_month')">Stunden ist</th>
                                <th v-show="showColumn('quota_year_target')">Stunden Jahr Plan</th>
                                <th v-show="showColumn('quota_year')">Stunden Jahr ist</th>
                            </tr>
                        </template>

                        <template #body>
                            <tr v-for="team in teams.data" :key="team.id" @click="$inertia.visit(route('teams.edit', {id: team.id}))">
                                <td>{{ team.name }}</td>
                                <td><div class="rounded-full w-4 h-4" :class="ratingClass(team)"></div></td>
                                <td v-show="showColumn('quota_month_target')">{{ toHourMinuteDisplay(team.quota_month_target) }}</td>
                                <td v-show="showColumn('quota_month')">{{ toHourMinuteDisplay(team.quota_month) }}</td>
                                <td v-show="showColumn('quota_year_target')">{{ toHourMinuteDisplay(team.quota_year_target) }}</td>
                                <td v-show="showColumn('quota_year')">{{ toHourMinuteDisplay(team.quota_year) }}</td>
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
import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';

export default {
    mixins: [InteractsWithQueryBuilder],

    components: {
        AppLayout,
        Table: Tailwind2.Table
    },

    props: {
        teams: Object
    },

    methods: {
        ratingClass (team) {
            const factor = team.quota_month / team.quota_month_target;
            if (factor >= 1) {
                return 'bg-green-500'
            }else if(factor >= 0.5) {
                return 'bg-yellow-500'
            }else{
                return 'bg-red-500'
            }
        },
        toHourMinuteDisplay (decimalHours) {
            let decimalTime = parseFloat(decimalHours);
            decimalTime = decimalTime * 60 * 60;
            const hours = Math.floor((decimalTime / (60 * 60)));
            decimalTime = decimalTime - (hours * 60 * 60);
            const minutes = Math.floor((decimalTime / 60));

            return String(hours).padStart(2,'0') + ':' + String(minutes).padStart(2,'0')
        }
    }
}
</script>

<style scoped>

</style>
