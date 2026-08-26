<script setup>
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    users: Object,
    roles: Array,
    filters: Object,
});

const { t } = useI18n();
const toast = useToast();
const confirm = useConfirm();

// 搜索
const search = ref(props.filters.search || '');
let searchTimer = null;
watch(search, (value) => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        router.get('/users', { search: value || undefined }, { preserveState: true, replace: true });
    }, 300);
});

// 新建 / 编辑对话框
const dialogVisible = ref(false);
const editingUser = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: null,
});

const openCreate = () => {
    editingUser.value = null;
    form.reset();
    form.clearErrors();
    dialogVisible.value = true;
};

const openEdit = (user) => {
    editingUser.value = user;
    form.defaults({ name: user.name, email: user.email, password: '', password_confirmation: '', role: user.roles[0]?.name ?? null });
    form.reset();
    form.clearErrors();
    dialogVisible.value = true;
};

const submit = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            dialogVisible.value = false;
            toast.add({ severity: 'success', summary: t(editingUser.value ? 'users.updated' : 'users.created'), life: 3000 });
        },
    };
    if (editingUser.value) {
        form.put(`/users/${editingUser.value.id}`, options);
    } else {
        form.post('/users', options);
    }
};

// 删除
const confirmDelete = (user) => {
    confirm.require({
        message: t('users.deleteConfirm', { name: user.name }),
        header: t('common.delete'),
        icon: 'pi pi-exclamation-triangle',
        rejectProps: { label: t('common.cancel'), severity: 'secondary', outlined: true },
        acceptProps: { label: t('common.delete'), severity: 'danger' },
        accept: () => {
            router.delete(`/users/${user.id}`, {
                preserveScroll: true,
                onSuccess: () => toast.add({ severity: 'success', summary: t('users.deleted'), life: 3000 }),
                onError: () => toast.add({ severity: 'error', summary: t('users.cannotDeleteSelf'), life: 3000 }),
            });
        },
    });
};

// 分页
const onPage = (event) => {
    router.get('/users', { page: event.page + 1, search: search.value || undefined }, { preserveState: true });
};
</script>

<template>
    <div class="card !mb-0">
        <div class="card-header">
            <h3>{{ t('users.title') }}</h3>
        </div>
        <div class="card-body">
            <div class="flex items-center justify-between gap-4 mb-4">
                <IconField class="w-full sm:w-80">
                    <InputIcon class="pi pi-search" />
                    <InputText v-model="search" :placeholder="t('users.searchPlaceholder')" class="w-full" />
                </IconField>
                <Button :label="t('users.create')" icon="pi pi-plus" @click="openCreate" />
            </div>

            <DataTable
                :value="users.data"
                :rows="users.per_page"
                :totalRecords="users.total"
                :first="(users.current_page - 1) * users.per_page"
                lazy
                paginator
                @page="onPage"
                stripedRows
                :emptyMessage="t('common.noData')"
            >
                <Column field="id" header="ID" style="width: 5rem" />
                <Column field="name" :header="t('users.name')" />
                <Column field="email" :header="t('users.email')" />
                <Column :header="t('users.role')">
                    <template #body="{ data }">
                        <Tag v-if="data.roles.length" :value="data.roles[0].name" :severity="data.roles[0].name === 'admin' ? 'warn' : 'info'" />
                    </template>
                </Column>
                <Column field="created_at" :header="t('users.createdAt')">
                    <template #body="{ data }">{{ data.created_at?.slice(0, 10) }}</template>
                </Column>
                <Column :header="t('common.actions')" style="width: 10rem">
                    <template #body="{ data }">
                        <div class="flex gap-2">
                            <Button icon="pi pi-pencil" severity="secondary" outlined rounded @click="openEdit(data)" />
                            <Button icon="pi pi-trash" severity="danger" outlined rounded @click="confirmDelete(data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>

    <Dialog
        v-model:visible="dialogVisible"
        :header="t(editingUser ? 'users.edit' : 'users.create')"
        modal
        class="w-full sm:w-[28rem]"
    >
        <form @submit.prevent="submit" class="flex flex-col gap-4 pt-1">
            <div class="flex flex-col gap-1.5">
                <label for="user-name" class="text-sm font-medium">{{ t('users.name') }}</label>
                <InputText id="user-name" v-model="form.name" :invalid="!!form.errors.name" />
                <small v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</small>
            </div>
            <div class="flex flex-col gap-1.5">
                <label for="user-email" class="text-sm font-medium">{{ t('users.email') }}</label>
                <InputText id="user-email" v-model="form.email" type="email" :invalid="!!form.errors.email" />
                <small v-if="form.errors.email" class="text-red-500">{{ form.errors.email }}</small>
            </div>
            <div class="flex flex-col gap-1.5">
                <label for="user-password" class="text-sm font-medium">
                    {{ t('users.password') }}
                    <span v-if="editingUser" class="text-muted-color font-normal">({{ t('users.passwordKeepHint') }})</span>
                </label>
                <Password id="user-password" v-model="form.password" :feedback="false" toggleMask :invalid="!!form.errors.password" class="w-full" inputClass="w-full" />
                <small v-if="form.errors.password" class="text-red-500">{{ form.errors.password }}</small>
            </div>
            <div class="flex flex-col gap-1.5">
                <label for="user-password-confirm" class="text-sm font-medium">{{ t('users.passwordConfirm') }}</label>
                <Password id="user-password-confirm" v-model="form.password_confirmation" :feedback="false" toggleMask :invalid="!!form.errors.password_confirmation" class="w-full" inputClass="w-full" />
                <small v-if="form.errors.password_confirmation" class="text-red-500">{{ form.errors.password_confirmation }}</small>
            </div>
            <div class="flex flex-col gap-1.5">
                <label for="user-role" class="text-sm font-medium">{{ t('users.role') }}</label>
                <Select id="user-role" v-model="form.role" :options="roles" optionLabel="name" optionValue="name" :placeholder="t('users.rolePlaceholder')" :invalid="!!form.errors.role" class="w-full" />
                <small v-if="form.errors.role" class="text-red-500">{{ form.errors.role }}</small>
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" :label="t('common.cancel')" severity="secondary" outlined @click="dialogVisible = false" />
                <Button type="submit" :label="t('common.save')" :loading="form.processing" />
            </div>
        </form>
    </Dialog>
</template>
