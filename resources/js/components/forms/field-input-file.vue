<template>
    <input type="file" @change="processFile($event)" class="mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" v-on:change="$emit('change')">
</template>

<script>
    export default {
        props: ['value'],
        data () {
            return {
                content: this.value
            }
        },
        methods: {
            processFile (event) {
                this.$emit('input', event.target.files[0])
            }
        }
    }
</script>
