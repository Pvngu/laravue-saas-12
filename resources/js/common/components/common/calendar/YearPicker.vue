<template>
    <a-date-picker
        v-model:value="yearValue"
        picker="year"
        :format="formatYear"
        :disabled-date="isFutureDateDisabled ? disabledDate : undefined"
        :placeholder="$t('common.year')"
        style="width: 100%"
        @change="yearChanged"
        :disabled="disabled"
    />
</template>

<script>
import { defineComponent, onMounted, ref, watch } from "vue";
import common from "../../../composable/common";

export default defineComponent({
    props: {
        year: {
            default: undefined,
        },
        disabled: {
            default: false,
        },
        isFutureDateDisabled: {
            default: true,
        },
    },
    emits: ["yearChanged"],
    setup(props, { emit }) {
        const yearValue = ref(undefined);
        const { disabledDate, formatYear, dayjs } = common();

        onMounted(() => {
            if (props.year) {
                yearValue.value = dayjs(props.year);
            }
        });

        const yearChanged = (newValue) => {
            const emitValue = newValue
                ? newValue.utc().format("YYYY")
                : undefined;
            emit("yearChanged", emitValue);
        };

        watch(
            () => props.year,
            (newValue) => {
                if (newValue) {
                    yearValue.value = dayjs(newValue);
                } else {
                    yearValue.value = undefined;
                }
            }
        );

        return {
            yearValue,
            disabledDate,
            formatYear,
            yearChanged,
            props
        };
    },
});
</script>

<style></style>