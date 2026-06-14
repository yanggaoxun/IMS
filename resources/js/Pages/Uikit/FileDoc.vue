<script setup>
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';

const toast = useToast();
const fileupload = ref();

function upload() {
    fileupload.value.upload();
}

function onUpload() {
    toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
}
</script>

<template>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-6">
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Advanced</h3>
                </div>
                <FileUpload name="demo[]" @uploader="onUpload" :multiple="true" accept="image/*" :maxFileSize="1000000" customUpload />
            </div>
        </div>
        <div class="col-span-full lg:col-span-6">
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Basic</h3>
                </div>
                <div class="card flex flex-col gap-6 items-center justify-center">
                    <Toast />
                    <FileUpload ref="fileupload" mode="basic" name="demo[]" accept="image/*" :maxFileSize="1000000" @uploader="onUpload" customUpload />
                    <Button label="Upload" @click="upload" severity="secondary" />
                </div>
            </div>
        </div>
    </div>
</template>
