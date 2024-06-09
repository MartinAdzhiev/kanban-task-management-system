<script setup>
import {reactive, ref, toRefs} from 'vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {router} from "@inertiajs/vue3";

const createOpen = ref(false)
const editOpen = ref(false)

const props = defineProps({
    projects: Object,
    memberInProjects: Object
})

const {projects} = toRefs(props)
console.log(projects);
const form = reactive({
    name: null,
    description: null
})

function submitCreate() {
    router.post('/project/store', form)
}
</script>

<template>
    <div class="container mx-auto px-4">
        <ul role="list" class="divide-y divide-gray-100">
            <li v-for="project in projects" :key="project.id" class="flex justify-between gap-x-6 py-5">
                <div class="flex min-w-0 gap-x-4 ">
                    <div class="min-w-0 flex-auto">
                        <button @click="show(project)" class="text-sm font-semibold leading-6 text-gray-900">{{ project.name }} -
                            <span class="text-blue-800">Owner</span></button>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ project.description }}</p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-row sm:items-end gap-4">
                    <button @click="editOpen = true, editProject(project)" class="text-sm leading-6 text-gray-900">
                        Edit
                    </button>
                    <button @click="destroy(project)" class="text-sm leading-6 text-gray-900">Delete</button>
                </div>
            </li>
        </ul>
        <ul role="list" class="divide-y divide-gray-100">
            <li v-for="memberInProject in memberInProjects" :key="memberInProject.id" class="flex justify-between gap-x-6 py-5">
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
        <button @click="createOpen = true" class="mt-4">Add new project</button>
    </div>
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
                                            <input id="name" v-model="form.name"/>
                                        </div>
                                        <div class="mt-2">
                                            <label for="description">Description:</label>
                                            <input id="description" v-model="form.description"/>
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
                                            <input id="name" v-model="selectedProject.name"/>
                                        </div>
                                        <div class="mt-2">
                                            <label for="description">Description:</label>
                                            <input id="description" v-model="selectedProject.description"/>
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
