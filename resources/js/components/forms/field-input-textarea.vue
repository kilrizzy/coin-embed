<template>
    <textarea v-model="content" @input="handleInput" class="mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"></textarea>
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
