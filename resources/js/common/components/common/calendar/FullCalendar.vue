<template>
    <a-card class="page-content-container mt-20 mb-20">
        <div class="calendar-container">
            <FullCalendar 
                :options="calendarOptions"
                class="ant-full-calendar"
            />
        </div>
        <a-spin v-if="loading" class="calendar-spinner" />
    </a-card>
</template>

<script>
import { ref, watch } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridWeek from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import esLocale from '@fullcalendar/core/locales/es';
import { useRouter } from "vue-router";

export default {
    props: {
        calendarData: {
            default: [],
        },
        loading: {
            default: false,
        },
    },
    components: {
        FullCalendar,
    },
    emits: ['changeDate'],
    setup(props, { emit }) {
        const router = useRouter();
        const calendarOptions = ref({
            plugins: [ dayGridPlugin, timeGridWeek, listPlugin, interactionPlugin],
            headerToolbar: {
                left: 'title',
                center: 'prev today next',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            initialView: 'dayGridMonth',
            dayMaxEvents: 3,
            events: props.calendarData,
            locale: esLocale,
            datesSet: (info) => {
                emit('changeDate', info.startStr, info.endStr);
            },
            eventClick: (info) => {
              if(info && info.event && info.event.extendedProps && info.event.extendedProps.route) {
                const route = info.event.extendedProps.route;
                router.push({ name: route.name, params: route.params, query: route.query });
              }
            },
            eventLimitText: 'mas'
        });

        watch(() => props.calendarData, (newVal) => {
            calendarOptions.value.events = newVal;
        });

        return {
            calendarOptions,
        }
    }
}
</script>

<style>
.ant-full-calendar .fc-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ant-full-calendar .fc-toolbar .fc-toolbar-chunk {
    display: flex;
    align-items: center;
}

.ant-full-calendar .fc-toolbar .fc-toolbar-chunk:first-child {
    flex: 1;
}

.ant-full-calendar .fc-toolbar .fc-toolbar-chunk:nth-child(2) {
    flex: 2;
    display: flex;
    justify-content: center;
}

.ant-full-calendar .fc-toolbar .fc-toolbar-chunk:last-child {
    flex: 1;
    display: flex;
    justify-content: flex-end;
}

.ant-full-calendar .fc-button {
  background-color: unset;
  border: 1px solid #d9d9d9;
  color: rgba(0, 0, 0, 0.88);
  padding-block: 8px;
  border-radius: 4px;
  cursor: pointer;
}

.ant-full-calendar .fc-button.fc-button-active {
  background-color: var(--primary-color) !important;
  border-color: var(--primary-color) !important;
}

.ant-full-calendar .fc-button:hover {
  background-color: var(--primary-color) !important;
  color: white !important;
  border-color: var(--primary-color) !important;
}

.ant-full-calendar .fc-button-group .fc-button {
  margin-right: 5px;
}

.ant-full-calendar .fc-button-group .fc-button:last-child {
  margin-right: 0;
}

.ant-full-calendar .fc-daygrid-day-number {
  color: #595959;
}

/* Loader styles */
.calendar-container {
  position: relative;
}

.calendar-spinner {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 10;
}
</style>