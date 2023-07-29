<template>
  <button
    @click="toggleDropdownMember"
    class="group flex items-center py-3 relative w-full"
    id="app"
  >
    <icon
      name="beneficials"
      class="mr-2 w-4 h-4 ml-4"
      :class="
        isUrl('organizations') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
      "
    />
    <div
      :class="
        isUrl('organizations') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
      "
    >
      المنتفعين
    </div>
    <div v-if="DropdownOpenow" class="absolute left-0">
      <icon
        class="w-5 h-5 fill-indigo-300 group-hover:fill-white focus:fill-indigo-600"
        name="cheveron-up"
      />
    </div>
    <div v-else class="absolute left-0">
      <icon
        class="w-5 h-5 fill-indigo-300 group-hover:fill-white focus:fill-indigo-600"
        name="cheveron-left"
      />
    </div>
  </button>
  <ul class="pr-10 mr-15 border_menu_side" v-if="DropdownOpenow">
    <slot />
  </ul>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";

export default {
  data() {
    return {
      DropdownOpenow: false,
    };
  },
  components: {
    Icon,
    Link,
  },
  props: {
    auth: Object,
  },
  methods: {
    toggleDropdownMember() {
      this.DropdownOpenow = !this.DropdownOpenow;
    },
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1);
      if (urls[0] === "") {
        return currentUrl === "";
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length;
    },
  },
};
</script>
