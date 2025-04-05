<template>
    <a-input-number
        v-model:value="inputNumber"
        :placeholder="$t('common.placeholder_default_text', [$t(placeholder)])"
        :formatter="value => `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
        :parser="value => value.replace(/\$\s?|(,*)/g, '')"
        :min="min"
        :max="max"
        style="width: 100%"
        @change="$emit('inputNumberChanged', inputNumber)"
        :disabled="disabled"
    />
</template>

<script>
import { defineComponent, ref, watch} from "vue";

export default defineComponent({
    props: {
        min: {
            default: 0
        },
        max: {
            default: null
        },
        disabled: {
            default: false
        },
        value: {
            default: null
        },
        placeholder: {
            default: 'common.amount'
        }
    },
    setup(props) {
        const inputNumber = ref(null);

        watch(() => props.value, (newValue) => {
            inputNumber.value = newValue;
        }, { immediate: true });

        return {
            inputNumber
        }
    }
});
</script>