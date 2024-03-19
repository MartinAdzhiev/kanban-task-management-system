<script setup>
import {reactive, toRefs} from 'vue'
import {router} from '@inertiajs/vue3'
import {Link} from "@inertiajs/vue3";
import { useForm } from '@inertiajs/inertia-vue3'


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

    <form @submit.prevent="submit">

        <label for="priority">Priority:</label>
        <select id="priority" v-model="form.priority">
<!--            <option :value="All" :selected="form.priority === null">All</option>-->
            <option v-for="priority in priorities" :value="priority">{{ priority }}</option>
        </select>

        <label for="deadline">Due Date:</label>
        <input type="date" id="deadline" v-model="form.deadline"/>

        <button type="submit">Filter</button>
        <Link href="/task" method="get" type="button">Reset</Link>
    </form>

    <li v-for="task in tasks">
        {{ task.name }}
        {{ task.description }}
        {{ task.deadline.slice(0, 10) }}
        {{ task.priority }}
    </li>
</template>
