<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Familien
                </h2>
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
                        :meta="teams"
                        ref="table"
                    >
                        <template v-slot:tableGlobalSearch="slotProps">
                            <div class="block w-full">
                                <jet-label for="start_date" value="Zeitraum von" class="block w-full" />
                                <jet-input id="start_date" type="date" v-model="startFilterDate" class="block w-full" @change="slotProps.changeGlobalSearchValue(filterDate)" />
                            </div>
                            <div class="block w-full">
                                <jet-label for="end_date" value="Zeitraum bis" class="block w-full" />
                                <jet-input id="end_date" type="date" v-model="endFilterDate" class="block w-full" @change="slotProps.changeGlobalSearchValue(filterDate)" />
                            </div>
                        </template>

                        <template #head>
                            <tr>
                                <th v-show="showColumn('name')" @click.prevent="sortBy('name')">Name</th>
                                <th>Rating</th>
                                <th v-show="showColumn('quota_target')">Stunden Plan</th>
                                <th v-show="showColumn('quota')">Stunden ist</th>
                                <th v-show="showColumn('quota_delta')">Delta</th>
                                <th v-show="showColumn('active_kids')">Rel. Monate ges.</th>
                            </tr>
                        </template>

                        <template #body>
                            <tr v-for="team in teams.data" :key="team.id" @click="$inertia.visit(route('teams.show', {id: team.id}))">
                                <td>{{ team.name }}</td>
                                <td><div class="rounded-full w-4 h-4" :class="ratingClass(team)"></div></td>
                                <td v-show="showColumn('quota_target')">{{ toHourMinuteDisplay(team.quota_target) }}</td>
                                <td v-show="showColumn('quota')">{{ toHourMinuteDisplay(team.quota) }}</td>
                                <td v-show="showColumn('quota_delta')">{{ toHourMinuteDisplay(team.quota_delta) }}</td>
                                <td v-show="showColumn('active_kids')">{{ team.active_kids }}</td>
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

import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetButton from "@/Jetstream/Button.vue";

export default {
    mixins: [InteractsWithQueryBuilder],

    data: () => {
        return {
            startFilterDate: null,
            endFilterDate: null
        }
    },

    components: {
        AppLayout,
        Table: Tailwind2.Table,
        JetInput,
        JetInputError,
        JetLabel,
        JetButton
    },

    props: {
        teams: Object
    },

    mounted() {
        const value = this.queryBuilderProps.search.global.value;

        if (value) {
            const filterDate = JSON.parse(value);
            if (filterDate) {
                this.startFilterDate = filterDate.begin;
                this.endFilterDate = filterDate.end;
                return;
            }
        }

        const date = new Date();
        this.startFilterDate = new Date(date.getFullYear(), date.getMonth(), 1).toLocaleDateString('fr-CA');
        this.endFilterDate = new Date(date.getFullYear(), date.getMonth() + 1, 1).toLocaleDateString('fr-CA');
        this.$refs.table.changeGlobalSearchValue(this.filterDate)
    },

    computed: {
        filterDate () {
            return JSON.stringify({begin:this.startFilterDate, end: this.endFilterDate});
        }
    },

    methods: {
        ratingClass (team) {
            const factor = team.quota / team.quota_target;
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
            if (isNaN(decimalTime)) {
                return 'n.V.'
            }
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
