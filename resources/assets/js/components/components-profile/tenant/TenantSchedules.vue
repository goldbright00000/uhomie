<template>
    <div class="columns">
        <div class="column">
            <div class="columns">
                <div class="column is-half line-down">
                    <span>Mi Agenda</span>
                </div>
                <div class="column is-half" v-if="false">
                    <div class="buttons has-addons is-right">
                        <a :class="option == 1 ? 'button is-primary is-outlined is-active' : 'button is-primary is-outlined'" @click="option = 1">Calendario</a>
                        <a :class="option == 0 ? 'button is-primary is-outlined is-active' : 'button is-primary is-outlined'" @click="option = 0">Tabla</a>
                    </div>
                </div>
            </div>
            <div class="columns" v-if="option == 1">
                <div class="column">
                    <FullCalendar 
                        defaultView="dayGridMonth"
                        :plugins="calendarPlugins"
                        :locale="locale"
                        :header="header"
                        :events="events"
                        @eventClick="handleDateClick"
                        />
                </div>
            </div>
        </div>
        <div class="modal is-active" v-if="modal == true">
            <div class="modal-background" @click="modalClose"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{'Agenda del día '+ moment(modal_data.schedule_date).format('DD-MM-YYYY')}}</p>
                    <button class="delete" aria-label="close" @click="modalClose"></button>
                </header>
                <section class="modal-card-body">
                    <article class="media">
                        <figure class="media-left">
                            <p class="image is-96x96">
                                <a :href="modal_data.url" target="_blank">
                                    <img :src="modal_data.property.photo">
                                </a>
                            </p>
                        </figure>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <a :href="modal_data.url" target="_blank"><strong>{{modal_data.schedule_title}} ID {{modal_data.property.id}}</strong></a>
                                    <br>
                                    {{modal_data.property.description}}
                                    <br>
                                    <small><b>Horario:</b> <span class="tag is-info">{{modal_data.schedule_range}}</span></small>
                                    <br>
                                    <small><b>Estatus de la agenda:</b> <span :class="modal_data.schedule_style">{{modal_data.schedule_message}}</span></small>
                                </p>
                            </div>
                        </div>
                    </article>
                </section>
                <footer class="modal-card-foot">
                    <a class="button" @click="modalClose">Volver</a>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>

import TenantCore from './TenantCore';

import FullCalendar from '@fullcalendar/vue';
import dayGridPlugin from '@fullcalendar/daygrid';
import esLocale from '@fullcalendar/core/locales/es';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';


export default {
    name: 'TenantSchedules',
    extends: TenantCore,
    components: {
        FullCalendar, // make the <FullCalendar> tag available
        
    },
    data() {
        return {
            calendarPlugins: [ dayGridPlugin, listPlugin, interactionPlugin ],
            locale: esLocale,
            header: {
                left: 'title',
                center: '',
                right: 'dayGridMonth,listYear today prev,next'
            },
            events:'',
            option: 1,
            modal_data: {},
            modal: false
        }
    },
    methods:{
        getEvents(){
            var events = this.info.schedules;
            var events_array = [];
            events.forEach(element => {
                if(element.schedule_range == '9-12'){
                    const [year, month, day] = element.schedule_date.split('-');
                    events_array.push({
                        //end: moment(element.schedule_date.date).format('YYYY-MM-DD hh:mm:ss a'),
                        //start: moment(element.schedule_date.date).subtract(3, 'hours').add(1, 'days').format('YYYY-MM-DD hh:mm:ss a'),
                        start: new Date(year,month - 1,day,9),
                        end: new Date(year,month - 1,day,12),
                        title: element.schedule_title,
                        id: element.id,
                        color: element.schedule_color,
                        //url: element.url
                    });
                }
                if(element.schedule_range == '12-3'){
                    const [year, month, day] = element.schedule_date.split('-');
                    events_array.push({
                        start: new Date(year,month - 1,day,12),
                        end: new Date(year,month - 1,day,15),
                        title: element.schedule_title,
                        id: element.id, 
                        color: element.schedule_color,
                        //url: element.url
                    });
                }
                if(element.schedule_range == '3-7'){
                    const [year, month, day] = element.schedule_date.split('-');
                    events_array.push({
                        start: new Date(year,month - 1,day,15),
                        end: new Date(year,month - 1,day,19),
                        title: element.schedule_title,
                        id: element.id,
                        color: element.schedule_color,
                        //url: element.url
                    });
                }
                this.events = events_array;
            });
        },
        handleDateClick(arg) {
            //alert(arg.event.id)
            this.modal_data = {};
            var info = this.info.schedules;
            var event = info.filter(schedule => schedule.id == arg.event.id)[0];
            console.log(event);
            this.modal_data = event;
            this.modal = true;

            switch(this.modal_data.schedule_state){
                case '0':
                    this.modal_data.schedule_style = 'tag is-warning';
                    this.modal_data.schedule_message = 'En Espera';
                    break;
                case '1':
                    this.modal_data.schedule_style = 'tag is-danger';
                    this.modal_data.schedule_message = 'Rechazado';
                    break;
                case '2':
                    this.modal_data.schedule_style = 'tag is-success';
                    this.modal_data.schedule_message = 'Aprobado';
                    break;
                default:
                    this.modal_data.schedule_style = 'tag is-info';
                    this.modal_data.schedule_message = 'No ingreso a switch';
                    break;
            }
            //this.$set(this.modal, "data", event);//
            //this.event = this.info.schedules.filter(schedule => schedule.id == arg.event.id);

        },
        modalClose(){
            this.modal = false;
        }
    },
    mounted(){
        this.getEvents();
    }
}
</script>

<style lang='scss'>

@import '~@fullcalendar/core/main.css'; 
@import '~@fullcalendar/daygrid/main.css';
@import '~@fullcalendar/list/main.css';


</style>