<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    roles: Array,
    permissions: Array,
});

const { t } = useI18n();
const toast = useToast();
const confirm = useConfirm();

const dialogVisible = ref(false);
const editingRole = ref(null);

const form = useForm({
    name: '',
    permissions: [],
});

const openCreate = () => {
    editingRole.value = null;
    form.defaults({ name: '', permissions: [] });
    form.reset();
    form.clearErrors();
    dialogVisible.value = true;
};

const openEdit = (role) => {
    editingRole.value = role;
    form.defaults({
        name: role.name,
        permissions: role.permissions.map((p) => p.name),
    });
    form.reset();
    form.clearErrors();
    dialogVisible.value = true;
};

const submit = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            dialogVisible.value = false;
            toast.add({ severity: 'success', summary: t(editingRole.value ? 'roles.updated' : 'roles.created'), life: 3000 });
        },
    };
    if (editingRole.value) {
        form.put(`/roles/${editingRole.value.id}`, options);
    } else {
        form.post('/roles', options);
    }
};

const confirmDelete = (role) => {
    confirm.require({
        message: t('roles.deleteConfirm', { name: role.name }),
        header: t('common.delete'),
        icon: 'pi pi-exclamation-triangle',
        rejectProps: { label: t('common.cancel'), severity: 'secondary', outlined: true },
        acceptProps: { label: t('common.delete'), severity: 'danger' },
        accept: () => {
            router.delete(`/roles/${role.id}`, {
                preserveScroll: true,
                onSuccess: () => toast.add({ severity: 'success', summary: t('roles.deleted'), life: 3000 }),
                onError: () => toast.add({ severity: 'error', summary: t('roles.adminLocked'), life: 3000 }),
            });
        },
    });
};
</script>

<template>
    <div class="card !mb-0">
        <div class="card-header">
            <h3>{{ t('roles.title') }}</h3>
        </div>
        <div class="card-body">
            <div class="flex justify-end mb-4">
                <Button :label="t('roles.create')" icon="pi pi-plus" @click="openCreate" />
            </div>

            <DataTable :value="roles" stripedRows :emptyMessage="t('common.noData')">
                <Column field="id" header="ID" style="width: 5rem" />
                <Column field="name" :header="t('roles.name')" />
                <Column :header="t('roles.permissions')">
                    <template #body="{ data }">
                        <div class="flex flex-wrap gap-1">
                            <Tag v-for="p in data.permissions" :key="p.id" :value="p.name" severity="info" />
                            <span v-if="!data.permissions.length" class="text-muted-color">{{ t('roles.noPermissions') }}</span>
                        </div>
                    </template>
                </Column>
                <Column :header="t('common.actions')" style="width: 10rem">
                    <template #body="{ data }">
                        <div class="flex gap-2">
                            <Button icon="pi pi-pencil" severity="secondary" outlined rounded @click="openEdit(data)" />
                            <Button icon="pi pi-trash" severity="danger" outlined rounded :disabled="data.name === 'admin'" @click="confirmDelete(data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>

    <Dialog
        v-model:visible="dialogVisible"
        :header="t(editingRole ? 'roles.edit' : 'roles.create')"
        modal
        class="w-full sm:w-[28rem]"
    >
        <form @submit.prevent="submit" class="flex flex-col gap-4 pt-1">
            <div class="flex flex-col gap-1.5">
                <label for="role-name" class="text-sm font-medium">{{ t('roles.name') }}</label>
                <InputText id="role-name" v-model="form.name" :invalid="!!form.errors.name" :disabled="editingRole?.name === 'admin'" />
                <small v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</small>
            </div>
            <div class="flex flex-col gap-1.5">
                <span class="text-sm font-medium">{{ t('roles.permissions') }}</span>
                <div class="flex flex-col gap-2">
                    <div v-for="p in permissions" :key="p.id" class="flex items-center gap-2">
                        <Checkbox v-model="form.permissions" :inputId="`perm-${p.id}`" :value="p.name" />
                        <label :for="`perm-${p.id}`">{{ p.name }}</label>
                    </div>
                </div>
                <small v-if="form.errors.permissions" class="text-red-500">{{ form.errors.permissions }}</small>
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" :label="t('common.cancel')" severity="secondary" outlined @click="dialogVisible = false" />
                <Button type="submit" :label="t('common.save')" :loading="form.processing" />
            </div>
        </form>
    </Dialog>
</template>
