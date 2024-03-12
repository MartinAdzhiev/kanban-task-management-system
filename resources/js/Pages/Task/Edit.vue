<script setup>
import {reactive, toRefs} from 'vue'
import { router } from '@inertiajs/vue3'


const props = defineProps({
    task: Object,
    priorities:Array,
    members:Array,
    assigned_to_me:Boolean
})

const { task } = toRefs(props);
const { priorities } = toRefs(props);
const { members } = toRefs(props);
const { assigned_to_me } = toRefs(props);



const form = reactive({
    name: task.value.name,
    description: task.value.description,
    deadline: task.value.deadline.slice(0,10),
    priority: task.value.priority,
    assign_to_me: assigned_to_me.value,
    assigned_to: task.value.assigned_to

})

function submit() {
    router.put(`/task/${task.value.id}/update`, form)
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

            <label for="assign_to_me">Assign to me</label>
            <input type="checkbox" id="assign_to_me" v-model="form.assign_to_me" />

            <label for="assignee">Assign To:</label>
            <select :disabled="form.assign_to_me" id="assignee" v-model="form.assigned_to">
                <option v-for="member in members" :value="member.user_id">{{ member.name }}-{{ member.role }}</option>
            </select>




            <button type="submit">Submit</button>
    </form>
</template>
