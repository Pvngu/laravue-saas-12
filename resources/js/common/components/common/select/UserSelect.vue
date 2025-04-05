<template>
    <a-select
        v-model:value="selectOption"
        :placeholder="$t('common.select_default_text', [$t('user.user')])"
        :allowClear="true"
        style="width: 100%"
        :dropdown-match-select-width="false"
        optionFilterProp="title"
        show-search
        @change="onChange"
        :disabled="disabled"
        :mode="mode"
    >
        <a-select-opt-group v-for="( users, role ) in allUsers" :key="role" :label="role">
            <a-select-option
                v-for="user in users"
                :key="user.xid"
                :title="`${user.lastname1} ${user.lastname2} ${user.name}`"
                :value="user.xid"
                :disabled="disableDisabledUsers && user.status === 'disabled' || user.xid === currentUserId"
                :class="{ warningSelect: showDisabledUserWarning && user.status === 'disabled' }"
            >
                <a-row :gutter="12">
                    <a-col flex="20px">
                        <a-avatar :src="user.profile_image_url" :size="20" />
                    </a-col>
                    <a-col flex="1" class="ellipsis" style="width: 100%;">
                        {{ user.lastname1 }} {{ user.lastname2 }} {{ user.name }}
                        <!--    {{ (showUnidad && user.unidad_administrativa) ? `(${user.unidad_administrativa})` : '' }} -->
                    </a-col>
                </a-row>
            </a-select-option>
        </a-select-opt-group>
    </a-select>
</template>

<script>
import { defineComponent, onMounted, ref, watch } from 'vue';

export default defineComponent({
    props: {
        value: {
            default: null
        },
        disabled: {
            default: false
        },
        showDisabledUserWarning: {
            default: false
        },
        disableDisabledUsers: {
            default: false
        },
        currentUserId: {
            default: undefined
        },
        data: {
            default: null
        },
        mode: {
            default: null
        },
        roles: {
            default: ""
        },
        showUnidad: {
            default: false
        },
        userType: {
            default: ""
        }
    },
    setup(props, { emit }) {
        const usersUrl = 'all-users';
        const allUsers = ref({});
        const selectOption = ref(null);

        const onChange = (id) => {
            emit('onChange', id);
        }

        onMounted(()=> {
            if(props.value) {
                selectOption.value = props.value;
            }

            if(props.data) {
                allUsers.value = props.data;
            } else {
                console.log('Fetching users from API');
                console.log(props.userType)           
                const url = usersUrl + (props.roles ? `?roles=${props.roles}` : '') + (props.userType ? `?user_type=${props.userType}` : '');

                axiosAdmin.get(url).then((res) => {
                    allUsers.value = res.data.users;
                })
            }
        })

        // Reset select option when value is null
        watch(() => props.value, (newValue) => {
            if(newValue === null) {
                selectOption.value = newValue;
            }
        })

        watch(() => props.data, (newValue) => {
            if(newValue) {
                allUsers.value = newValue;
            }
        })

        watch(() => props.value, (newValue) => {
            if(newValue) {
                selectOption.value = newValue;
            }
        })

        return {
            allUsers,
            onChange,
            selectOption
        }
    }
});
</script>

<style>
    .warningSelect {
        background-color: #fffbe6;
        color: #faad14 !important
    }
    .ellipsis {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>