<template>
    <div v-show="isOpen" class="top-0 left-0 right-0 fixed flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-10">
        <div v-if="isOpen" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto">
            <div class="rounded-lg shadow-xs overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-blue-400" v-if="currentTheme=='info'"></i>
                            <i class="fas fa-check-circle text-green-400" v-if="currentTheme=='success'"></i>
                            <i class="fas fa-exclamation-triangle text-yellow-400" v-if="currentTheme=='warning'"></i>
                            <i class="fas fa-exclamation-triangle text-red-400" v-if="currentTheme=='error'"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm leading-5 font-medium text-gray-900" v-if="currentTitle">
                                {{ currentTitle }}
                            </p>
                            <p class="mt-1 text-sm leading-5 text-gray-500">
                                <span v-if="currentContent">{{ currentContent }}</span>
                                <span v-if="!currentContent"><slot></slot></span>
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button v-on:click="close()" class="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {

        },
        props: {
            theme: {
                default: 'info'
            },
            title: {

            },
            startOpen: {
                default: true
            }
        },
        data: function () {
            return {
                isOpen: this.startOpen,
                currentTheme: this.theme,
                currentTitle: this.title,
                currentContent: null,
            }
        },
        mounted: function(){

        },
        methods: {
            open(theme = null, title = null, content = null, timeout = null){
                this.isOpen = true;
                if(theme){
                    this.currentTheme = theme;
                }
                if(title){
                    this.currentTitle = title;
                }
                if(content){
                    this.currentContent = content;
                }
                if(timeout){
                    setTimeout(() => this.close(), timeout);
                }
            },
            close(){
                this.isOpen = false;
            }
        },
    }
</script>
