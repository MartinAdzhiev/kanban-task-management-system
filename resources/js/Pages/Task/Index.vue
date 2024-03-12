<script setup>
import {reactive, toRefs} from 'vue'
import { router } from '@inertiajs/vue3'


const props = defineProps({
   tasks:Array,
})

const { tasks } = toRefs(props);

</script>

<template>
    <li v-for="task in tasks">
        {{ task.name }}
        {{task.description}}
        {{task.deadline.slice(0,10)}}
        {{task.priority}}
    </li>
</template>
