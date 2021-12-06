<template>
    <select v-model="content" @change="handleInput" class="mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
        <option value="" :selected="!content">--</option>
        <option v-for="(optionLabel, optionKey) in options" :value="optionKey" :selected="content==optionKey">{{ optionLabel }}</option>
    </select>
</template>

<script>
    export default {
        props: {
            value: {},
            options: {}
        },
        data () {
            return {
                content: this.value
            }
        },
        methods: {
            handleInput (e) {
                this.$emit('input', this.content)
            }
        },
        watch: {
            value: function(newVal, oldVal) {
                if(this.content !== newVal){
                    this.content = newVal;
                }
            }
        },
    }
</script>