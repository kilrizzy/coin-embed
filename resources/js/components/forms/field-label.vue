<template>
    <div class="block text-sm font-semibold leading-5 text-gray-700">
        <slot></slot><span v-if="required" class="text-red-600"> *</span>
    </div>
</template>

<script>
    export default {
        props: {
            required: {
                default: false,
            }
        },
    }
</script>