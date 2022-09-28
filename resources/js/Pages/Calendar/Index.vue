<template>
    <app-layout>
        <template #header>
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Kalender
                </h2>
            </div>

        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    <calendar :attributes="attributes" :columns="2" :rows="2" is-expanded />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import { Calendar } from 'v-calendar';
import 'v-calendar/dist/style.css';

export default {
    components: {
        AppLayout,
        Calendar
    },
    props: [
        'events'
    ],
    computed: {
        attributes () {
            return this.events.map(ev => {
                return {
                    key: ev.id,
                    highlight: ev.is_participating === 1,
                    dot: ev.is_participating !== 1,
                    popover: {
                        label: ev.description,
                    },
                    dates: {
                        start: ev.start_date,
                        end: ev.end_date
                    }
                }
            })
        }
    }
}
</script>
