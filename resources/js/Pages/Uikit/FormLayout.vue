<script setup>
import { ref } from 'vue';

const dropdownItems = ref([
    { name: 'Option 1', code: 'Option 1' },
    { name: 'Option 2', code: 'Option 2' },
    { name: 'Option 3', code: 'Option 3' },
]);

const dropdownItem = ref(null);
</script>

<template>
    <Fluid>
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-6 flex flex-col gap-6">
                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Vertical</h3>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="name1">Name</label>
                            <InputText id="name1" type="text" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="email1">Email</label>
                            <InputText id="email1" type="text" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="age1">Age</label>
                            <InputText id="age1" type="text" />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Vertical Grid</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="name2">Name</label>
                            <InputText id="name2" type="text" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="email2">Email</label>
                            <InputText id="email2" type="text" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-6 flex flex-col gap-6">
                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Horizontal</h3>
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <label for="name3" class="col-span-12 md:col-span-2">Name</label>
                            <div class="col-span-12 md:col-span-10">
                                <InputText id="name3" type="text" />
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <label for="email3" class="col-span-12 md:col-span-2">Email</label>
                            <div class="col-span-12 md:col-span-10">
                                <InputText id="email3" type="text" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Inline</h3>
                    <div class="flex flex-wrap items-end gap-4">
                        <div>
                            <label for="firstname1" class="sr-only">Firstname</label>
                            <InputText id="firstname1" type="text" placeholder="Firstname" />
                        </div>
                        <div>
                            <label for="lastname1" class="sr-only">Lastname</label>
                            <InputText id="lastname1" type="text" placeholder="Lastname" />
                        </div>
                        <Button label="Submit" :fluid="false" />
                    </div>
                </div>

                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Help Text</h3>
                    <div class="flex flex-col gap-2">
                        <label for="username">Username</label>
                        <InputText id="username" type="text" />
                        <small class="text-slate-500">Enter your username to reset your password.</small>
                    </div>
                </div>
            </div>

            <div class="col-span-12">
                <div class="card">
                    <h3 class="font-semibold text-lg mb-4">Advanced</h3>
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="firstname2">Firstname</label>
                                <InputText id="firstname2" type="text" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="lastname2">Lastname</label>
                                <InputText id="lastname2" type="text" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="address">Address</label>
                            <Textarea id="address" rows="4" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="state">State</label>
                                <Select id="state" v-model="dropdownItem" :options="dropdownItems" optionLabel="name" placeholder="Select One" class="w-full" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="zip">Zip</label>
                                <InputText id="zip" type="text" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Fluid>
</template>
