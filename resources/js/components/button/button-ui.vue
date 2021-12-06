<template>
    <component :is="tag" v-bind:href="href" v-bind:type="type" :class="themeClass" :data-theme="theme" v-on:click="$emit('click')">
        <slot></slot>
    </component>
</template>

<script>
    export default {
        props: {
            href: {
                default: null
            },
            type: {
                default: null
            },
            theme: {
                default: 'default'
            },
            size: {
                default: 'md'
            }
        },
        mounted() {
        },
        data() {
            return {

            }
        },
        methods: {

        },
        computed: {
            tag: function(){
                if(this.href){
                    return 'a';
                }
                return 'button';
            },
            themeClass: function(){
                let base = 'no-underline inline-block focus:outline-none';
                if(this.size === 'xs'){
                    base += ' px-4 py-1';
                }
                if(this.size === 'sm'){
                    base += ' px-5 py-2';
                }
                if(this.size === 'sm-a'){
                    base += ' px-3 py-2';
                }
                if(this.size === 'md'){
                    base += ' px-6 py-3';
                }
                if(this.size === 'md-sq'){
                    base += ' px-3 py-3';
                }
                if(this.size === 'lg'){
                    base += ' px-8 py-4';
                }
                if(this.theme === 'primary'){
                    return base + ' font-bold shadow rounded-full bg-blue-600 text-blue-200 hover:text-white hover:bg-blue-700';
                }
                if(this.theme === 'danger'){
                    return base + ' font-bold shadow rounded-full bg-red-600 text-red-200 hover:text-white hover:bg-red-700';
                }
                if(this.theme === 'inset'){
                    return base + ' font-medium rounded bg-gray-100 border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-200 hover:border-gray-300';
                }
                return base + ' font-bold shadow rounded-full bg-white border-blue-600 text-blue-600 hover:text-blue-800 hover:border-blue-800 hover:bg-blue-100';
            }
        }

    }
</script>
