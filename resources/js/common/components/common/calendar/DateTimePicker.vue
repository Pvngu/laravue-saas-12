<template>
    <a-date-picker
        v-model:value="dateTimeValue"
        :format="props.onlyDate ? formatOrderDate : formatOrderDateTime"
        :disabled-date="isFutureDateDisabled ? disabledDate : (isPastDateDisabled ? disabledPastDate : undefined)"
        :show-time="props.showTime"
        :placeholder="onlyDate ? $t('common.date') : $t('common.date_time')"
        style="width: 100%"
        @change="dateTimeChanged"
        :disabled="disabled"
    />
</template>

<script>
import { defineComponent, onMounted, ref, watch } from "vue";
import common from "../../../composable/common";

export default defineComponent({
    props: {
        dateTime: {
            default: undefined,
        },
        disabled: {
            default: false,
        },
        isFutureDateDisabled: {
            default: false,
        },
        isPastDateDisabled: {
            default: false,
        },
        showTime: {
            default: true,
        },
        onlyDate: {
            default: false,
        },
    },
    emits: ["dateTimeChanged"],
    setup(props, { emit }) {
        const dateTimeValue = ref(undefined);
        const { disabledDate, disabledPastDate, formatDateTime,formatDate, dayjs } = common();

        onMounted(() => {
            if (props.dateTime) {
                dateTimeValue.value = dayjs(props.dateTime);
            }
        });

        const formatOrderDate = (newValue) => {
            return newValue ? formatDate(newValue.format()) : undefined;
        };

        const formatOrderDateTime = (newValue) => {
            return newValue ? formatDateTime(newValue.format()) : undefined;
        };

        const dateTimeChanged = (newValue) => {
            const emitValue = newValue
                ? newValue.utc().format("YYYY-MM-DDTHH:mm:ssZ")
                : undefined;
            emit("dateTimeChanged", emitValue);
        };

        watch(
            () => props.dateTime,
            (newValue) => {
                if (newValue) {
                    dateTimeValue.value = dayjs(newValue);
                } else {
                    dateTimeValue.value = undefined;
                }
            }
        );

        return {
            dateTimeValue,
            disabledDate,
            disabledPastDate,
            formatOrderDate,
            formatOrderDateTime,
            dateTimeChanged,
            props,
        };
    },
});
</script>

<style></style>
