<template>
    <div class="flex items-center justify-center min-h-screen bg-indigo-800">
        <div class="w-full max-w-md">
            <flash-messages />
            <slot />
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import FlashMessages from '@/Shared/FlashMessages'

export default {
    components: {
        FlashMessages,
        Link,
    },
    props: {
        auth: Object,
    },
}
</script>