<template>
    <a-dropdown v-model:open="visible" :trigger="['click']" :disabled="disabled">
        <a-button type="link">
            <template #icon>
                <a-badge :count="count" size="small">
                    <FilterFilled class="filter-icon" />
                </a-badge>
            </template>
        </a-button>
        <template #overlay>
            <a-row class="filter-dropdown">
                <a-col :span="24">
                    <a-row justify="space-between" align="center">
                        <a-col>
                            <a-typography-title :level="5">{{ $t('common.filters') }}</a-typography-title>
                        </a-col>
                        <a-col>                    
                            <a-button 
                                @click="$emit('onReset')" 
                                :disabled="!count" 
                                type="text" danger
                            >
                                {{ $t( 'common.reset') }}
                            </a-button>
                        </a-col>
                    </a-row>
                </a-col>
                <a-col :span="24">
                    <a-form layout="vertical" class="mt-20">
                        <slot />
                    </a-form>
                </a-col>
            </a-row>
        </template>
    </a-dropdown>
</template>

<script>
import { FilterFilled } from '@ant-design/icons-vue';
import { ref, watch } from 'vue';

export default {
    props: {
        filters: {
            type: Object,
        },
        extraFilters: {
            type: Object,
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    components: {
        FilterFilled
    },
    emits: ['onReset'],
    setup(props) {
        const visible = ref(false);
        const count = ref(0);

        watch(
            () => [props.filters, props.extraFilters],
            ([newFilters, newExtraFilters]) => {
            let filterCount = 0;

            if (newFilters) {
                filterCount += Object.keys(newFilters)
                .filter(key => newFilters[key] !== null && newFilters[key] !== undefined && newFilters[key] !== '')
                .length;
            }

            if (newExtraFilters) {
                filterCount += Object.keys(newExtraFilters)
                .filter(key => newExtraFilters[key] !== null && newExtraFilters[key] !== undefined && newExtraFilters[key] !== '')
                .length;
            }

            count.value = filterCount;
            },
            { deep: true, immediate: true }
        );
        return {
            visible,
            count
        }
    }
}
</script>

<style scoped>
.filter-icon {
    font-size: 1.1rem;
    color: #94a3b8;
}

.filter-icon:hover {
    color: #6b7280;
}

.filter-dropdown {
    padding: 1.5rem;
    width: 400px;
    border-radius: .5rem;
}
</style>