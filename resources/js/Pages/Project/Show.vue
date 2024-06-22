<script setup>
import {reactive, ref, toRefs} from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import {router, useForm} from '@inertiajs/vue3'
import AppLayout from "@/Layouts/AppLayout.vue";
import Footer from "@/Layouts/Footer.vue";

const props = defineProps({
    project: Object,
    boards: Object,
    owner: Object,
    loggedInUser: Object,
    members: Object
})

const openMembers = ref(false)
const addMember = ref(false)
const createOpen = ref(false)
const editOpen = ref(false)

const {project, boards, owner, loggedInUser, members} = toRefs(props)

const memberForm = useForm({
    email: null,
    role: null
})

const boardForm = useForm({
    name: null,
    description : null,
    defaultCols: false
})

function submitMember() {
    router.post(`/project/${project.value.id}/addMember`, memberForm)
    memberForm.reset()
}

function submitCreateBoard(){
    router.post(`/project/${project.value.id}/board/store`, boardForm)
    boardForm.reset()
}
</script>

<template>
    <AppLayout title="Boards"></AppLayout>
    <div class="bg-gray-200">
    <header class="container mx-auto flex justify-between">
        <div class="max-w-7xl px-4 py-6">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{project.name}}</h1>
        </div>
        <div class="my-auto max-w-7xl px-4 py-6  flex gap-4">
            <button class="text-green-900 hover:text-white border border-green-800 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-600 dark:text-green-400 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800" @click="openMembers=true">See members </button>
            <button class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800" v-if="owner.id === loggedInUser.id" @click="addMember=true">Add</button>
        </div>
    </header>
    </div>
    <div class="container mx-auto px-4">
        <ul role="list" class="divide-y divide-gray-100">
            <li v-for="board in boards" :key="board.id" class="flex justify-between gap-x-6 py-5 hover:bg-gray-100 rounded">
                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex-auto">
                        <button @click="showBoard(board)" class="text-sm font-semibold leading-6 text-gray-900">{{ board.name }}</button>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ board.description }}</p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-row sm:items-end gap-4">
                    <button v-if="owner.id === loggedInUser.id" @click="editOpen = true, editBoard(board)"
                            class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Edit</button>
                    <button v-if="owner.id === loggedInUser.id" @click="destroyBoard(board)"
                            class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">Delete</button>
                </div>
            </li>
        </ul>
        <hr>
        <button class="mt-4 text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800" v-if="owner.id === loggedInUser.id" @click="createOpen = true">Add new board</button>
    </div>
    <Footer></Footer>
    <TransitionRoot as="template" :show="openMembers">
        <Dialog as="div" class="relative z-10" @close="openMembers = false">
            <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100"
                             leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700"
                                         enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition
                                         ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto relative w-screen max-w-md">
                                <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0"
                                                 enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100"
                                                 leave-to="opacity-0">
                                    <div class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4">
                                        <button type="button" class="relative rounded-md text-gray-300 hover:text-white
                                        focus:outline-none focus:ring-2 focus:ring-white" @click="openMembers = false">
                                            Close
                                            <span class="absolute -inset-2.5" />
                                            <span class="sr-only">Close panel</span>
                                        </button>
                                    </div>
                                </TransitionChild>
                                <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                                    <div class="px-4 sm:px-6">
                                        <DialogTitle class="text-base font-semibold leading-6 text-gray-900">Members in
                                            project</DialogTitle>
                                    </div>
                                    <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                        <!-- Your content -->
                                        <div class="text-base font-semibold leading-6 text-gray-900" v-text="owner.name +
                                         ' - Owner'"></div>
                                        <hr>
                                        <div v-for="member in members" :key="member.id" v-text="member.name + '-' + member.role" ></div>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <!--Add member form-->
    <TransitionRoot as="template" :show="addMember">
        <Dialog as="form" class="relative z-10" @close="addMember = false" @submit.prevent="submitMember">
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
                                            Add member to project
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <label for="email">Add user by email:</label>
                                            <input class="peer h-full w-full rounded-[7px]  !border  !border-gray-300
                                            border-t-transparent bg-transparent bg-white px-3 py-2.5 font-sans text-sm
                                            font-normal text-blue-gray-700  shadow-lg shadow-gray-900/5 outline outline-0
                                            ring-4 ring-transparent transition-all placeholder:text-gray-500 placeholder-shown:border
                                            placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200
                                            focus:border-2 focus:!border-gray-900 focus:border-t-transparent focus:!border-t-gray-900
                                            focus:outline-0 focus:ring-gray-900/10 disabled:border-0 disabled:bg-blue-gray-50"
                                                   placeholder="Member email" id="email" v-model="memberForm.email"/>
                                        </div>
                                        <div class="mt-2">
                                            <label for="role">Role:</label>
                                            <input class="peer h-full w-full rounded-[7px]  !border  !border-gray-300
                                            border-t-transparent bg-transparent bg-white px-3 py-2.5 font-sans text-sm
                                            font-normal text-blue-gray-700  shadow-lg shadow-gray-900/5 outline outline-0
                                            ring-4 ring-transparent transition-all placeholder:text-gray-500 placeholder-shown:border
                                            placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200
                                            focus:border-2 focus:!border-gray-900 focus:border-t-transparent focus:!border-t-gray-900
                                            focus:outline-0 focus:ring-gray-900/10 disabled:border-0 disabled:bg-blue-gray-50"
                                                   placeholder="Member Role" id="role" v-model="memberForm.role"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600
                                     px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                        @click="addMember = false">Add
                                </button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white
                                    px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                     hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="addMember = false"
                                        ref="cancelButtonRef">Cancel
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
    <!--Board create form-->
    <TransitionRoot as="template" :show="createOpen">
        <Dialog as="form" class="relative z-10" @close="createOpen = false" @submit.prevent="submitCreateBoard">
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
                                                   placeholder="Board Name" id="name" v-model="boardForm.name"/>
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
                                                   placeholder="Board Description" type="text" id="description" v-model="boardForm.description"/>
                                        </div>
                                        <div class="mt-2">
                                            <label for="column">Add default columns(To do, In progress, Done):</label>
                                            <input class="ml-1.5" type="checkbox" id="column" v-model="boardForm.defaultCols"/>
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
    <!--Board edit form-->
    <TransitionRoot as="template" :show="editOpen">
        <Dialog as="form" class="relative z-10" @close="editOpen = false" @submit.prevent="submitEditBoard">
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
                                                   placeholder="Board Name" id="name" v-model="selectedBoard.name"/>
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
                                                   placeholder="Board Description" id="description" v-model="selectedBoard.description"/>
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
            selectedBoard: null
        };
    },
    methods: {
        editBoard(board) {
            this.selectedBoard = Object.assign({}, board);
        },
        submitEditBoard() {
            router.put(`/board/${this.selectedBoard.id}/update`, this.selectedBoard)
        },
        destroyBoard(board){
            this.selectedBoard = Object.assign({}, board)
            router.delete(`/board/${this.selectedBoard.id}/delete`);
        },
        showBoard(board){
            this.selectedBoard = Object.assign({}, board);
            router.get(`/board/${this.selectedBoard.id}/show`)
        },
    },
};
</script>
