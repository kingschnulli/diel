<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Benutzer
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
                        :meta="users"
                    >
                        <template #head>
                            <tr>
                                <th @click.prevent="sortBy('name')">Name</th>
                                <th v-show="showColumn('email')" @click.prevent="sortBy('email')">Email</th>
                                <th v-show="showColumn('current_team')" @click.prevent="sortBy('current_team')">Familie</th>
                            </tr>
                        </template>

                        <template #body>
                            <tr v-for="user in users.data" :key="user.id"  @click="$inertia.visit(route('users.edit', {id: user.id}))">
                                <td>{{ user.name }}</td>
                                <td v-show="showColumn('email')">{{ user.email }}</td>
                                <td v-show="showColumn('current_team')">{{ user.current_team ? user.current_team.name : 'N/A' }}</td>
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
        users: Object
    }
}
</script>

<style scoped>

</style>
