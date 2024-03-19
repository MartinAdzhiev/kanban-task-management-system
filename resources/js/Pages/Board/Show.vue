<script setup>
import {toRefs} from "vue";

const props = defineProps({
    board: Object,
    columns: Object,
    tasks: Object,
})

const {board, tasks, columns} = toRefs(props)
console.log(columns.value)
console.log(tasks.value)
</script>

<template>
    <header class="bg-white shadow flex justify-between">
        <div class=" max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{board.name}}</h1>
        </div>
    </header>
    <!-- Kanban Board Container -->
    <div class="flex justify-center items-start p-4 gap-4">
        <!-- Kanban Column -->
        <div class="bg-gray-100 p-4 rounded basis-full" v-for="col in columns" :key="col.id">
            <h2 class="font-bold mb-2" v-text="col.name"></h2>
            <!-- Tasks -->
            <div class="space-y-2">
                <div v-for="task in tasks" :key="task.id">
                    <div class="bg-white p-2 rounded shadow" v-if="task.column_id === col.id" v-text="task.name"></div>
                </div>
                <!-- Add more tasks here -->
            </div>
        </div>
    </div>

</template>
