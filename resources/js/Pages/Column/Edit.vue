<script setup>
import {reactive, toRefs} from 'vue'
import { router } from '@inertiajs/vue3'


const props = defineProps({
    column: Object
})

const { column } = toRefs(props);


const form = reactive({
    name: column.value.name,
})

function submit() {
    router.put(`/column/${column.value.id}/update`, form)
}
</script>

<template>
    <form @submit.prevent="submit">
        <label for="name">Column Name:</label>
        <input id="name" v-model="form.name" />
        <button type="submit">Submit</button>
    </form>
</template>
