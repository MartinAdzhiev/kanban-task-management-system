<script setup>
import {reactive, ref, toRefs} from 'vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {router, useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Footer from "@/Layouts/Footer.vue";

const createOpen = ref(false)
const editOpen = ref(false)

const props = defineProps({
    projects: Object,
    memberInProjects: Object
})

const {projects} = toRefs(props)
console.log(projects);
const form = useForm({
    name: null,
    description: null
})

function submitCreate() {
    router.post('/project/store', form)
    form.reset();
}
</script>

<template>
    <AppLayout title="Projects"></AppLayout>
    <header class="bg-gray-200 flex justify-between">
        <div class="container mx-auto max-w-7xl px-4 py-6">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Projects</h1>
        </div>
    </header>
    <div class="container mx-auto px-4">
        <ul role="list" class="divide-y divide-gray-100">
            <li v-for="project in projects" :key="project.id" class="flex justify-between gap-x-6 py-5 hover:bg-blue-100 rounded">
                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex-auto flex-grow">
                        <button @click="show(project)" class="text-sm font-semibold leading-6 text-gray-900">{{ project.name }} -
                            <span class="text-blue-800">Owner</span></button>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ project.description }}</p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-row sm:items-end gap-4">
                    <button @click="editOpen = true, editProject(project)" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                        Edit
                    </button>
                    <button @click="destroy(project)" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">Delete</button>
                </div>
            </li>
        </ul>
        <hr>
        <ul role="list" class="divide-y divide-gray-100">
            <li v-for="memberInProject in memberInProjects" :key="memberInProject.id" class="flex justify-between gap-x-6 py-5 hover:bg-green-100">
                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex-auto">
                        <button @click="show(memberInProject)" class="text-sm font-semibold leading-6 text-gray-900">{{ memberInProject.name }} -
                            <span class="text-green-800">Member</span></button>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ memberInProject.description }}</p>
                    </div>
                </div>
            </li>
        </ul>
        <hr>
        <button @click="createOpen = true" class="mt-4 text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Add new project</button>
    </div>
    <Footer></Footer>
    <TransitionRoot as="template" :show="createOpen">
        <Dialog as="form" class="relative z-10" @close="createOpen = false" @submit.prevent="submitCreate">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                             leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4
                        sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in
                        duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4
                        sm:translate-y-0 sm:scale-95">
                        <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl
                             transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">
                                            Create new project
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <label for="name">Name:</label>
                                            <input class="peer h-full w-full rounded-[7px]  !border  !border-gray-300
                                            border-t-transparent bg-transparent bg-white px-3 py-2.5 font-sans text-sm
                                            font-normal text-blue-gray-700  shadow-lg shadow-gray-900/5 outline outline-0
                                            ring-4 ring-transparent transition-all placeholder:text-gray-500 placeholder-shown:border
                                            placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200
                                            focus:border-2 focus:!border-gray-900 focus:border-t-transparent focus:!border-t-gray-900
                                            focus:outline-0 focus:ring-gray-900/10 disabled:border-0 disabled:bg-blue-gray-50"
                                                   placeholder="Project Name" id="name" v-model="form.name"/>
                                        </div>
                                        <div class="mt-2">
                                            <label for="description">Description:</label>
                                            <input class="peer h-full w-full rounded-[7px]  !border  !border-gray-300
                                            border-t-transparent bg-transparent bg-white px-3 py-2.5 font-sans text-sm
                                            font-normal text-blue-gray-700  shadow-lg shadow-gray-900/5 outline outline-0
                                            ring-4 ring-transparent transition-all placeholder:text-gray-500 placeholder-shown:border
                                            placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200
                                            focus:border-2 focus:!border-gray-900 focus:border-t-transparent focus:!border-t-gray-900
                                            focus:outline-0 focus:ring-gray-900/10 disabled:border-0 disabled:bg-blue-gray-50"
                                                   placeholder="Project Description" id="description" v-model="form.description"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600
                                     px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                        @click="createOpen = false">Add
                                </button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white
                                    px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                     hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="createOpen = false"
                                        ref="cancelButtonRef">Cancel
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <TransitionRoot as="template" :show="editOpen">
        <Dialog as="form" class="relative z-10" @close="editOpen = false" @submit.prevent="submitEdit">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                             leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4
                        sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in
                        duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4
                        sm:translate-y-0 sm:scale-95">
                        <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl
                             transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">
                                            Edit project
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <label for="name">Name:</label>
                                            <input class="peer h-full w-full rounded-[7px]  !border  !border-gray-300
                                            border-t-transparent bg-transparent bg-white px-3 py-2.5 font-sans text-sm
                                            font-normal text-blue-gray-700  shadow-lg shadow-gray-900/5 outline outline-0
                                            ring-4 ring-transparent transition-all placeholder:text-gray-500 placeholder-shown:border
                                            placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200
                                            focus:border-2 focus:!border-gray-900 focus:border-t-transparent focus:!border-t-gray-900
                                            focus:outline-0 focus:ring-gray-900/10 disabled:border-0 disabled:bg-blue-gray-50"
                                                   placeholder="Project Name" id="name" v-model="selectedProject.name"/>
                                        </div>
                                        <div class="mt-2">
                                            <label for="description">Description:</label>
                                            <input class="peer h-full w-full rounded-[7px]  !border  !border-gray-300
                                            border-t-transparent bg-transparent bg-white px-3 py-2.5 font-sans text-sm
                                            font-normal text-blue-gray-700  shadow-lg shadow-gray-900/5 outline outline-0
                                            ring-4 ring-transparent transition-all placeholder:text-gray-500 placeholder-shown:border
                                            placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200
                                            focus:border-2 focus:!border-gray-900 focus:border-t-transparent focus:!border-t-gray-900
                                            focus:outline-0 focus:ring-gray-900/10 disabled:border-0 disabled:bg-blue-gray-50"
                                                   placeholder="Project Name" id="description" v-model="selectedProject.description"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600
                                     px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                        @click="editOpen = false">Edit
                                </button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white
                                    px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                     hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="editOpen = false"
                                        ref="cancelButtonRef">Cancel
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
<script>
import {router} from "@inertiajs/vue3";

export default {
    data() {
        return {
            selectedProject: null
        };
    },
    methods: {
        editProject(project) {
            this.selectedProject = Object.assign({}, project);
        },
        submitEdit() {
            router.put(`/project/${this.selectedProject.id}/update`, this.selectedProject)
        },
        destroy(project){
            this.selectedProject = Object.assign({}, project)
            router.delete(`/project/${this.selectedProject.id}/delete`);
        },
        show(project){
            this.selectedProject = Object.assign({}, project);
            router.get(`/project/${this.selectedProject.id}/show`)
        },
    },
};
</script>
