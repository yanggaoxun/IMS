<script setup>
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    roles: Array,
    permissions: Array,
});

const { t, te } = useI18n();
const toast = useToast();
const confirm = useConfirm();

const dialogVisible = ref(false);
const editingRole = ref(null);

// 权限按「模块.动作」转成树：父节点=模块，子节点=权限
const permissionTree = computed(() => {
    const groups = {};
    for (const p of props.permissions) {
        const dot = p.name.indexOf('.');
        const module = dot === -1 ? 'other' : p.name.slice(0, dot);
        (groups[module] ??= []).push(p);
    }

    return Object.entries(groups).map(([module, perms]) => ({
        key: `module:${module}`,
        label: te(`permissions.modules.${module}`) ? t(`permissions.modules.${module}`) : module,
        children: perms.map((p) => {
            const action = p.name.slice(p.name.indexOf('.') + 1);
            return {
                key: p.name,
                label: te(`permissions.actions.${action}`) ? t(`permissions.actions.${action}`) : p.name,
            };
        }),
    }));
});

// PrimeVue Tree checkbox 的绑定格式：{ [key]: { checked, partialChecked } }
const selectionKeys = ref({});

const form = useForm({
    name: '',
    permissions: [],
});

const openCreate = () => {
    editingRole.value = null;
    selectionKeys.value = {};
    form.defaults({ name: '', permissions: [] });
    form.reset();
    form.clearErrors();
    dialogVisible.value = true;
};

const openEdit = (role) => {
    editingRole.value = role;
    const selected = new Set(role.permissions.map((p) => p.name));
    const keys = Object.fromEntries([...selected].map((name) => [name, { checked: true, partialChecked: false }]));
    // 父节点状态：全选→checked，部分→partialChecked（Tree 不自动回填父级状态）
    for (const group of permissionTree.value) {
        const total = group.children.length;
        const count = group.children.filter((c) => selected.has(c.key)).length;
        if (count > 0) {
            keys[group.key] = { checked: count === total, partialChecked: count < total };
        }
    }
    selectionKeys.value = keys;
    form.defaults({
        name: role.name,
        permissions: [...selected],
    });
    form.reset();
    form.clearErrors();
    dialogVisible.value = true;
};

const submit = () => {
    // 只提交 checked 的叶子节点（权限名），父节点 key 带 module: 前缀需排除
    form.permissions = Object.entries(selectionKeys.value)
        .filter(([key, state]) => state.checked && !key.startsWith('module:'))
        .map(([key]) => key);

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
                <Tree
                    v-model:selectionKeys="selectionKeys"
                    :value="permissionTree"
                    selectionMode="checkbox"
                    class="w-full !p-2"
                />
                <small v-if="form.errors.permissions" class="text-red-500">{{ form.errors.permissions }}</small>
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" :label="t('common.cancel')" severity="secondary" outlined @click="dialogVisible = false" />
                <Button type="submit" :label="t('common.save')" :loading="form.processing" />
            </div>
        </form>
    </Dialog>
</template>
