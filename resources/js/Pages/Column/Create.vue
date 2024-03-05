<script setup>
import {reactive, toRefs} from 'vue'
import { router } from '@inertiajs/vue3'


const props = defineProps({
    board: Object
})

const { board } = toRefs(props);


const form = reactive({
    board_id: board.value.id,
    name: null,
})

function submit() {
    router.post('/column', form)
}
</script>

<template>
    <form @submit.prevent="submit">
        <label for="board">{{board.name}}:</label>
        <input disabled id="name" v-model="board.id"/>
        <label for="name">Column Name:</label>
        <input id="name" v-model="form.name" />
        <button type="submit">Submit</button>
    </form>
</template>
