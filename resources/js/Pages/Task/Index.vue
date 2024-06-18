<script setup>
import {reactive, toRefs} from 'vue'
import {router} from '@inertiajs/vue3'
import {Link} from "@inertiajs/vue3";
import {useForm} from '@inertiajs/vue3'
import AppLayout from "@/Layouts/AppLayout.vue";


const props = defineProps({
    tasks: Array,
    priorities: Array,
})

const {tasks} = toRefs(props);
const {priorities} = toRefs(props);

const form = reactive({
    priority: null,
    deadline: null,
})


function submit() {
    router.get('/task', form)
}

</script>

<template>

    <AppLayout title="Tasks"></AppLayout>
    <form class="max-w-lg mx-auto" @submit.prevent="submit">

        <div class="mx-auto mb-3">
            <label for="priority" class="block mb-2 text-lg font-medium text-gray-900 dark:text-white">Priority</label>
            <select id="priority" v-model="form.priority"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                <option v-for="priority in priorities" :value="priority">{{ priority }}</option>
            </select>
        </div>


        <div class="mb-3">
            <label for="deadline"
                   class="block mb-2 text-lg font-medium text-gray-900 dark:text-white">Deadline</label>
            <div class="flex">
            <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                <svg class="w-[22px] h-[22px] text-gray-800 dark:text-white" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                  d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                                  clip-rule="evenodd"/>
                </svg>

            </span>
                <input type="date" id="deadline" v-model="form.deadline"
                       class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>
        </div>


        <div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Filter</button>
            <Link  href="/task" method="get" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">Reset</Link>
        </div>
    </form>

    <ul role="list" class="divide-y divide-gray-100 mx-40">
        <li v-for="task in tasks" class="flex items-center justify-between gap-x-6 py-5">
            <div class="min-w-0">
                <div class="flex items-start gap-x-3">
                    <p class="text-lg font-semibold leading-6 text-gray-900">{{ task.name }}</p>
                    <p v-if="task.priority === 'Low'" class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-sm font-medium ring-1 ring-inset text-green-700 bg-green-50 ring-green-600/20">Low</p>
                    <p v-if="task.priority === 'Medium'" class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-sm font-medium ring-1 ring-inset text-yellow-700 bg-yellow-50 ring-yellow-600/20">Medium</p>
                    <p v-if="task.priority === 'High'" class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-sm font-medium ring-1 ring-inset text-red-700 bg-red-50 ring-red-600/20">High</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2 text-lg leading-5 text-gray-500">
                    <p class="whitespace-nowrap">Deadline <time>{{ task.deadline.slice(0,10) }}</time></p>
                    <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current">
                        <circle cx="1" cy="1" r="1" />
                    </svg>
                </div>
                <p class="truncate">Description: {{ task.description }}</p>
            </div>
            <div class="flex flex-none items-center gap-x-4">
<!--                <a :href="'/project/' + task.project_id + '/show'" class="hidden rounded-md bg-fuchsia-100 px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block">View project</a>-->
                <a :href="'/board/' + task.column.board_id + '/show'" class="hidden rounded-md bg-fuchsia-100 px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block">View board</a>
                <div class="relative flex-none">

                </div>
            </div>
        </li>

    </ul>




    <!--    <li v-for="task in tasks">-->
<!--        {{ task.name }}-->
<!--        {{ task.description }}-->
<!--        {{ task.deadline.slice(0, 10) }}-->
<!--        {{ task.priority }}-->
<!--    </li>-->
</template>
