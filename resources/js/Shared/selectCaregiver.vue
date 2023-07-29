<template>
    <div class="flex flex-col items-center relative">
        <button @click="toggleselect_caregiver" class=" rounded-lg  group flex items-center py-3 px-1 justify-between w-20" id="app">
            <div :class="'text-gray-700 group-hover:text-indigo-600'">
                تعيين  
            </div>
            <div v-if="select_caregiver">
                <icon
                    class="w-5 h-5 fill-gray-700 group-hover:fill-indigo-600 focus:fill-indigo-600"
                    name="cheveron-up"
                />
            </div>
            <div v-else>
                <icon
                    class="w-5 h-5 fill-gray-700 group-hover:fill-indigo-600 focus:fill-indigo-600"
                    name="cheveron-left"
                />
            </div>
        </button>
        <ul class="pr-6 mr-15 w-56 absolute top-12 right-0 bg-red-100" v-if="select_caregiver" >
            <slot />
        </ul>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";

export default {
    data() {
        return {
            select_caregiver: false,
        }
    },
    components: {
        Icon,
        Link,
    },
    props: {
        auth: Object,
    },
    methods: {
        toggleselect_caregiver() {
            this.select_caregiver = !this.select_caregiver;
        },
    },
};
</script>