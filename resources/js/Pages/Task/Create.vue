<script setup>
import {reactive, toRefs} from 'vue'
import { router } from '@inertiajs/vue3'


const props = defineProps({
    column: Object,
    priorities: Array,
    members: Array,
})

const { column } = toRefs(props);
const { priorities } = toRefs(props);
const { members } = toRefs(props);


const form = reactive({
    column_id: column.value.id,
    name: null,
    description: null,
    deadline: null,
    priority: null,
    assigned_to: null,
})

function submit() {
    router.post('/task', form)
}
</script>

<template>
    <form @submit.prevent="submit">
        <label for="name">Task Name:</label>
        <input id="name" v-model="form.name" />

        <label for="description">Task Description:</label>
        <input type="text" id="description" v-model="form.description" />

        <label for="deadline">Due Date:</label>
        <input type="date" id="deadline" v-model="form.deadline" />

        <label for="priority">Priority:</label>
        <select id="priority" v-model="form.priority">
            <option v-for="priority in priorities" :value="priority">{{priority}}</option>
        </select>

        <label for="assignee">Assign To:</label>
        <select id="assignee" v-model="form.assigned_to">
            <option v-for="member in members" :value="member.user_id">{{ member.name }}-{{ member.role }}</option>
        </select>



        <button type="submit">Submit</button>
    </form>
</template>
