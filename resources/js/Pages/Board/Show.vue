<script setup>
import {reactive, ref, toRefs} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from "@headlessui/vue";


const props = defineProps({
    project: Object,
    board: Object,
    columns: Object,
    tasks: Object,
    priorities: Array,
    members: Array,
    loggedInUser: Number,
    errors: Object,
})

const createColumnOpen = ref(false)
const editColumnOpen = ref(false)
const deleteColumnConfirm = ref(false)

// const createTaskOpen = ref(false)
// const editTaskOpen = ref(false)
// const deleteTaskConfirm = ref(false)

const {project, board, tasks, columns, priorities, members} = toRefs(props)
console.log(columns.value)
console.log(tasks.value)
console.log(project.value.id)

const columnForm = useForm({
    name: null,
})

// const taskForm = reactive({
//     name: null,
//     description: null,
//     deadline: null,
//     priority: null,
//     assign_to_me: false,
//     assigned_to: null,
// })

function submitCreateColumn() {
    router.post(`/project/${project.value.id}/board/${board.value.id}/column/store`, columnForm)
    columnForm.reset()
}

// function submitCreateTask(column){
//     router.post(`/column/${column}/task/store`, taskForm)
// }
</script>

<template>
    <header class="bg-white shadow flex justify-between">
        <div class=" max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ board.name }}</h1>
        </div>
        <div class="my-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex gap-4">
            <button class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800" v-if="project.owner_id === loggedInUser" @click="createColumnOpen = true">Add new column</button>
        </div>
    </header>
    <!-- Kanban Board Container -->
    <div class="flex justify-center items-start p-4 gap-4 ">
        <!-- Kanban Column -->
        <div class="bg-gray-100 p-4 rounded basis-full" v-for="col in columns" :key="col.id" @dragover.prevent
             @drop="droppedTask(col)">
            <div class="font-bold mb-2 grid grid-cols-5 space-x-8">
                <div class="col-start-1 content-center">
                    <h2 v-text="col.name"></h2>
                </div>
                <div class="col-start-2 col-span-4 flex justify-end">
                    <button
                        class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800"
                        v-if="project.owner_id === loggedInUser" @click="editColumnOpen = true, editColumn(col)">Edit
                    </button>
                    <button
                        class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                        v-if="project.owner_id === loggedInUser"
                        @click="deleteColumnConfirm = true, destroyColumn(col)">Delete
                    </button>
                </div>
            </div>
            <!-- Tasks -->
            <div class="space-y-2">
                <div v-for="task in tasks" :key="task.id">
                    <div class="bg-white border-fuchsia-300 border-4 p-2 rounded shadow"
                         v-if="task.column_id === col.id && loggedInUser === task.assigned_to"
                         draggable="true"
                         @dragstart="dragStartTask(task)">
                        <div class="p-2 rounded shadow-sm border-black-100 border-2">
                            <h3 class="text-md mb-3 text-gray-950">{{ task.name }}</h3>
                            <p class="mb-3 text-sm text-gray-700">{{ task.description }}</p>
                            <p v-if="task.priority === 'Low'"
                               class="bg-green-300 text-xs w-max p-1 rounded mr-2 text-gray-700">{{ task.priority }}</p>
                            <p v-if="task.priority === 'Medium'"
                               class="bg-yellow-300 text-xs w-max p-1 rounded mr-2 text-gray-700">
                                {{ task.priority }}</p>
                            <p v-if="task.priority === 'High'"
                               class="bg-red-300 text-xs w-max p-1 rounded mr-2 text-gray-700">{{ task.priority }}</p>
                            <div class="flex flex-row items-center mt-2">
                                <div class="bg-gray-300 rounded-full w-4 h-4 mr-3"></div>
                                <a href="#" class="text-xs text-gray-500">{{ task.assigned_to }}</a>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 mb-2">{{ task.created_at.slice(0, 10) }} &#10141;
                                {{ task.deadline.slice(0, 10) }}</p>

                            <div class="flex justify-end">
                            <button class="bg-blue-300 hover:bg-blue-500 text-white text-xs py-1 px-1 rounded"
                                    v-if="project.owner_id === loggedInUser"
                                    @click="editTaskOpen = true, editTask(task)">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                </svg>
                            </button>
                            <button class="bg-red-300 hover:bg-red-500 text-white text-xs py-1 px-1 rounded ml-1"
                                    v-if="project.owner_id === loggedInUser"
                                    @click="deleteTaskConfirm = true, destroyTask(task)">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                </svg>
                            </button>
                            </div>
                        </div>

                    </div>

                    <div class="bg-white border-4 p-2 rounded shadow"
                         v-if="task.column_id === col.id && loggedInUser !== task.assigned_to"
                         draggable="false"
                         @dragstart="dragStartTask(task)">
                        <div class="p-2 rounded shadow-sm border-black-100 border-2">
                            <h3 class="text-md mb-3 text-gray-950">{{ task.name }}</h3>
                            <p class="mb-3 text-sm text-gray-700">{{ task.description }}</p>
                            <p v-if="task.priority === 'Low'"
                               class="bg-green-300 text-xs w-max p-1 rounded mr-2 text-gray-700">{{ task.priority }}</p>
                            <p v-if="task.priority === 'Medium'"
                               class="bg-yellow-300 text-xs w-max p-1 rounded mr-2 text-gray-700">
                                {{ task.priority }}</p>
                            <p v-if="task.priority === 'High'"
                               class="bg-red-300 text-xs w-max p-1 rounded mr-2 text-gray-700">{{ task.priority }}</p>
                            <div class="flex flex-row items-center mt-2">
                                <div class="bg-gray-300 rounded-full w-4 h-4 mr-3"></div>
                                <a href="#" class="text-xs text-gray-500">{{ task.assigned_to }}</a>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">{{ task.created_at.slice(0, 10) }} &#10141;
                                {{ task.deadline.slice(0, 10) }}</p>

                            <div class="flex justify-end">
                                <button class="bg-blue-300 hover:bg-blue-500 text-white text-xs py-1 px-1 rounded"
                                        v-if="project.owner_id === loggedInUser"
                                        @click="editTaskOpen = true, editTask(task)">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                    </svg>
                                </button>
                                <button class="bg-red-300 hover:bg-red-500 text-white text-xs py-1 px-1 rounded ml-1"
                                        v-if="project.owner_id === loggedInUser"
                                        @click="deleteTaskConfirm = true, destroyTask(task)">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>



            <button v-if="project.owner_id === loggedInUser" @click="createTaskOpen = true, createTask(col)" type="button" >
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
    <!-- Add more tasks here -->


    <!--Column create form-->
    <TransitionRoot as="template" :show="createColumnOpen">
        <Dialog as="form" class="relative z-10" @close="createColumnOpen = false" @submit.prevent="submitCreateColumn">
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
                                            Create new column
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <label for="name">Column Name:</label>
                                            <input id="name" v-model="columnForm.name"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600
                                     px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                        @click="createColumnOpen = false">Add
                                </button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white
                                    px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                     hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="createColumnOpen = false"
                                        ref="cancelButtonRef">Cancel
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>


    <!--Column edit form-->
    <TransitionRoot as="template" :show="editColumnOpen">
        <Dialog as="form" class="relative z-10" @close="editColumnOpen = false"
                @submit.prevent="submitEditColumn(project, board)">
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
                                            Edit column
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <label for="name">Name:</label>
                                            <input id="name" v-model="selectedColumn.name"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600
                                     px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                        @click="editColumnOpen = false">Edit
                                </button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white
                                    px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                     hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="editColumnOpen = false"
                                        ref="cancelButtonRef">Cancel
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>


    <!--Column delete confirm-->
    <TransitionRoot as="template" :show="deleteColumnConfirm">
        <Dialog as="form" class="relative z-10" @close="deleteColumnConfirm = false"
                @submit.prevent="confirmDestroyColumn(project, board)">
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
                                            Delete column {{ selectedColumn.name }}
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <h3>Deletion of this column will delete all tasks that belong to it. Are you
                                                sure you want to delete?</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600
                                     px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                        @click="deleteColumnConfirm = false">Delete
                                </button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white
                                    px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                     hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="deleteColumnConfirm = false"
                                        ref="cancelButtonRef">Cancel
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>


    <!--Task create form-->
    <TransitionRoot as="template" :show="createTaskOpen">
        <Dialog as="form" class="relative z-10" @close="createTaskOpen = false"
                @submit.prevent="submitCreateTask(project, board)">
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
                                            Create new task
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <label for="name">Task Name:</label>
                                            <input id="name" v-model="taskForm.name"/>
                                        </div>

                                        <div class="mt-2">
                                            <label for="description">Task Description:</label>
                                            <input type="text" id="description" v-model="taskForm.description"/>
                                        </div>

                                        <div class="mt-2">
                                            <label for="deadline">Due Date:</label>
                                            <input type="date" id="deadline" v-model="taskForm.deadline"/>
                                        </div>

                                        <div class="mt-2">
                                            <label for="priority">Priority:</label>
                                            <select id="priority" v-model="taskForm.priority">
                                                <option v-for="priority in priorities" :value="priority">
                                                    {{ priority }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mt-2">
                                            <label for="is_assigned_to_owner">Assign to me</label>
                                            <input type="checkbox" id="is_assigned_to_owner"
                                                   v-model="taskForm.is_assigned_to_owner"/>
                                        </div>

                                        <div class="mt-2">
                                            <label for="assignee">Assign To:</label>
                                            <select :disabled="taskForm.is_assigned_to_owner" id="assignee"
                                                    v-model="taskForm.assigned_to">
                                                <option v-for="member in members" :value="member.user_id">{{
                                                        member.name
                                                    }}-{{ member.role }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600
                                     px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                        @click="createTaskOpen = false">Add
                                </button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white
                                    px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                     hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="createTaskOpen = false"
                                        ref="cancelButtonRef">Cancel
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>


    <!--Task edit form-->
    <TransitionRoot as="template" :show="editTaskOpen">
        <Dialog as="form" class="relative z-10" @close="editTaskOpen = false"
                @submit.prevent="submitEditTask(project, board)">
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
                                            Edit task
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <label for="name">Task Name:</label>
                                            <input id="name" v-model="selectedTask.name"/>
                                        </div>

                                        <div class="mt-2">
                                            <label for="description">Task Description:</label>
                                            <input type="text" id="description" v-model="selectedTask.description"/>
                                        </div>

                                        <div class="mt-2">
                                            <label for="deadline">Current Due Date:
                                                {{ selectedTask.deadline.slice(0, 10) }}</label>
                                            <br>
                                            <input type="date" id="deadline" v-model="selectedTask.deadline">
                                        </div>

                                        <div class="mt-2">
                                            <label for="priority">Priority:</label>
                                            <select id="priority" v-model="selectedTask.priority">
                                                <option v-for="priority in priorities" :value="priority">
                                                    {{ priority }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mt-2">
                                            <label for="is_assigned_to_owner">Assign to me</label>
                                            <input type="checkbox" id="is_assigned_to_owner"
                                                   v-model="selectedTask.is_assigned_to_owner"/>
                                        </div>

                                        <div class="mt-2">
                                            <label for="assignee">Assign To:</label>
                                            <select :disabled="selectedTask.is_assigned_to_owner" id="assignee"
                                                    v-model="selectedTask.assigned_to">
                                                <option v-for="member in members" :value="member.user_id">{{
                                                        member.name
                                                    }}-{{ member.role }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600
                                     px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                        @click="editTaskOpen = false">Edit
                                </button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white
                                    px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                     hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="editTaskOpen = false"
                                        ref="cancelButtonRef">Cancel
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>


    <!--Task delete confirm-->
    <TransitionRoot as="template" :show="deleteTaskConfirm">
        <Dialog as="form" class="relative z-10" @close="deleteTaskConfirm = false"
                @submit.prevent="confirmDestroyTask(project, board)">
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
                                            Delete task {{ selectedTask.name }}
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <h3>Are you sure you want to delete this task?</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600
                                     px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                        @click="deleteTaskConfirm = false">Delete
                                </button>
                                <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white
                                    px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                                     hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="deleteTaskConfirm = false"
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
import {reactive, ref} from "vue";


export default {
    data() {
        return {
            selectedColumn: null,
            selectedTask: null,
            taskForm: useForm({
                name: null,
                description: null,
                deadline: null,
                priority: null,
                is_assigned_to_owner: (this.selectedTask != null && this.selectedTask.is_assigned_to_owner === true) ? 1 : 0,
                assigned_to: null,
            }),
            createTaskOpen: ref(false),
            editTaskOpen: ref(false),
            deleteTaskConfirm: ref(false),
        };
    },

    methods: {
        //column
        editColumn(col) {
            this.selectedColumn = Object.assign({}, col);
        },
        submitEditColumn(project, board) {
            router.put(`/project/${project.id}/board/${board.id}/column/${this.selectedColumn.id}/update`, this.selectedColumn)
        },
        destroyColumn(col) {
            this.selectedColumn = Object.assign({}, col)
        },
        confirmDestroyColumn(project, board) {
            router.delete(`/project/${project.id}/board/${board.id}/column/${this.selectedColumn.id}/delete`, this.selectedColumn);
        },

        //task
        createTask(task) {
            this.selectedColumn = Object.assign({}, task);
        },
        submitCreateTask(project, board) {
            router.post(`/project/${project.id}/board/${board.id}/column/${this.selectedColumn.id}/task/store`, this.taskForm);
            this.taskForm.reset()
        },
        editTask(task) {
            this.selectedTask = Object.assign({}, task);
        },
        submitEditTask(project, board) {
            router.put(`/project/${project.id}/board/${board.id}/column/${this.selectedTask.column_id}/task/${this.selectedTask.id}/update`, this.selectedTask)
        },
        destroyTask(task) {
            this.selectedTask = Object.assign({}, task)
        },
        confirmDestroyTask(project, board) {
            router.delete(`/project/${project.id}/board/${board.id}/column/${this.selectedTask.column_id}/task/${this.selectedTask.id}/delete`, this.selectedTask);
        },
        dragStartTask(task) {
            this.selectedTask = Object.assign({}, task);
        },
        droppedTask(col) {
            this.selectedColumn = Object.assign({}, col)
            router.put(`/column/${this.selectedColumn.id}/task/${this.selectedTask.id}/changeTaskInColumn`)
        },
    },


};
</script>
