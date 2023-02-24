<template>
    <Pie
        id="my-chart-id"
        :options="chartOptions"
        :data="chartData"
    />
</template>

<script>
import { Pie } from 'vue-chartjs'
import { Chart as ChartJS, ArcElement, Tooltip, Legend, LinearScale } from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend, LinearScale)

export default {
    name: "ParticipationChart",
    components: { Pie },
    data() {
        let chartData;
        if (this.$page.props.user.current_team.full_quota > this.$page.props.user.current_team.full_quota_target) {
            chartData = {
                labels: [ 'Über', 'Soll' ],
                datasets: [{
                    data: [
                        this.$page.props.user.current_team.full_quota - this.$page.props.user.current_team.full_quota_target,
                        this.$page.props.user.current_team.full_quota_target
                    ],
                    backgroundColor: [ '#ce003a', '#3cc700' ],
                }]
            }
        }else{
            chartData = {
                labels: [ 'Ist', 'Fehl' ],
                datasets: [{
                    data: [
                        this.$page.props.user.current_team.full_quota,
                        this.$page.props.user.current_team.full_quota_target - this.$page.props.user.current_team.full_quota
                    ],
                    backgroundColor: [ '#3cc700', '#ffbed1' ],
                }]
            }
        }
        return {
            chartData: chartData,
            chartOptions: {
                responsive: true
            }
        }
    }
}
</script>

<style scoped>

</style>
