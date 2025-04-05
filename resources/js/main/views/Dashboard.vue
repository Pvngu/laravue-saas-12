<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.dashboard`)" style="padding: 0px" />
        </template>
    </AdminPageHeader>

    <div class="dashboard-page-content-container">
        
    </div>
</template>

<script>
import { onMounted, reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import common from "../../common/composable/common";
import AdminPageHeader from "../../common/layouts/AdminPageHeader.vue";
import StateWidget from "../../common/components/common/card/StateWidget.vue";
import DateRangePicker from "../../common/components/common/calendar/DateRangePicker.vue";

export default {
    components: {
        AdminPageHeader,
        StateWidget,
        DateRangePicker,
    },
    setup() {
        const { formatTimeDuration } = common();
        const { t } = useI18n();
        const responseData = ref([]);
        const filters = reactive({
            dates: [],
        });

        onMounted(() => {
            getInitData();
        });

        const getInitData = () => {
            // const dashboardPromise = axiosAdmin.post("dashboard", filters);

            // Promise.all([dashboardPromise]).then(([dashboardResponse]) => {
            //     responseData.value = dashboardResponse.data;
            // });
        };

        watch([filters], (newVal, oldVal) => {
            getInitData();
        });

        return {
            formatTimeDuration,
            filters,
            responseData,
        };
    },
};
</script>

<style lang="less">
.ant-card-extra,
.ant-card-head-title {
    padding: 0px;
}

.ant-card-head-title {
    margin-top: 10px;
}
</style>
