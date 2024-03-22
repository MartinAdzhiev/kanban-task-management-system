<script setup>
import {reactive, ref, toRefs} from "vue";
import {router} from "@inertiajs/vue3";
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from "@headlessui/vue";

const props = defineProps({
    board: Object,
    columns: Object,
    tasks: Object,
})

const createColumnOpen = ref(false)
const editColumnOpen = ref(false)
const deleteColumnConfirm = ref(false)

const {board, tasks, columns} = toRefs(props)
console.log(columns.value)
console.log(tasks.value)

const columnForm = reactive({
    name: null,
})

function submitCreateColumn(){
    router.post(`/board/${board.value.id}/column/store`, columnForm)
}
</script>

<template>
    <header class="bg-white shadow flex justify-between">
        <div class=" max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{board.name}}</h1>
        </div>
        <div class="my-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex gap-4">
            <button @click="createColumnOpen = true">Add new column</button>
        </div>
    </header>
    <!-- Kanban Board Container -->
    <div class="flex justify-center items-start p-4 gap-4">
        <!-- Kanban Column -->
        <div class="bg-gray-100 p-4 rounded basis-full flex justify-between" v-for="col in columns" :key="col.id">
            <h2 class="font-bold mb-2" v-text="col.name"></h2>
                <button @click="editColumnOpen = true, editColumn(col)">Edit</button>
                <button @click="deleteColumnConfirm = true, destroyColumn(col)">Delete</button>
            <!-- Tasks -->
            <div class="space-y-2">
                <div v-for="task in tasks" :key="task.id">
                    <div class="bg-white p-2 rounded shadow" v-if="task.column_id === col.id" v-text="task.name"></div>
                </div>
                <!-- Add more tasks here -->
            </div>
        </div>
    </div>

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
                                            <input id="name" v-model="columnForm.name" />
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
        <Dialog as="form" class="relative z-10" @close="editColumnOpen = false" @submit.prevent="submitEditColumn">
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
        <Dialog as="form" class="relative z-10" @close="deleteColumnConfirm = false" @submit.prevent="confirmDestroyColumn">
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
                                            Delete column {{selectedColumn.name}}
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <h3>Deletion of this column will delete all tasks that belong to it. Are you sure you want to delete?</h3>
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

</template>

<script>
import {router} from "@inertiajs/vue3";

export default {
    data() {
        return {
            selectedColumn: null
        };
    },
    methods: {
        editColumn(col) {
            this.selectedColumn = Object.assign({}, col);
        },
        submitEditColumn() {
            router.put(`/column/${this.selectedColumn.id}/update`, this.selectedColumn)
        },
        destroyColumn(col){
            this.selectedColumn = Object.assign({}, col)
        },
        confirmDestroyColumn() {
            router.delete(`/column/${this.selectedColumn.id}/delete`, this.selectedColumn);
        }
    },
};
</script>
