<template>
    <div v-if="modalChart" class="modal is-active">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Estadistica de ID {{info.id}}</p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <line-chart
                    v-if="loaded"
                    :chartdata="chartdata"
                    :options="options">
                </line-chart>
            </section>
                <footer class="modal-card-foot">
                <button class="button" @click="closeModal">Volver</button>
            </footer>
        </div>
    </div>
</template>

<script>
import LineChart from './Chart.vue'

export default {
    name: 'ChartProperty',
    components: { LineChart },
    props: {
        modalChart: {
            type: Boolean,
            default: false
        },
        info: Object
    },
    data: () => ({
        loaded: true,
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    }),
    computed:{
        chartdata(){
            var labels = this.info.charts.labels;
            var postulations = [];
            var schedules = [];
            var visits = [];
            labels.forEach(element => {
                
                var postulation = this.info.charts.postulations.filter(postulation => postulation.label == element)[0];
                console.log(postulation);
                if(postulation != undefined){
                    postulations.push(postulation.data);
                } else {
                    postulations.push(0);
                }

                var schedule = this.info.charts.schedules.filter(schedule => schedule.label == element)[0];
                if(schedule != undefined){
                    schedules.push(schedule.data);
                } else {
                    schedules.push(0);
                }

                var visit = this.info.charts.visits.filter(visit => visit.label == element)[0];
                if(visit != undefined){
                    visits.push(visit.data);
                } else {
                    visits.push(0);
                }
            });
            var datasets = [
                {
                    label: 'Postulaciones',
                    backgroundColor: '#00B1D9',
                    data: postulations
                },
                {
                    label: 'Visitas',
                    backgroundColor: '#947AFB',
                    data: visits
                },
                {
                    label: 'Agenda',
                    backgroundColor: '#F981F6',
                    data: schedules
                }
            ]

            var chartdata ={
                labels: labels,
                datasets: datasets
            }

            return chartdata;
        }
    },
    methods: {
        closeModal(){
            this.$emit('closeModalChart', false);
        },
        infoChart(){
            
        }
    },
    mounted(){
        this.infoChart();
    }
}
</script>