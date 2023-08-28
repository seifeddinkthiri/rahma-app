<template>
  <button class="group flex items-center py-3 relative w-full" id="app">
    <icon
      name="users"
      class="mr-2 w-4 h-4 ml-4"
      :class="isUrl('contacts') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'"
    />
    <div
      :class="
        isUrl('organizations') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
      "
    >
      المستخدمين
    </div>

    <div class="absolute left-0">

    </div>
  </button>
  <ul class="pr-10 mr-15 border_menu_side">
    <slot />
  </ul>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";

export default {
  components: {
    Icon,
    Link,
  },
  props: {
    auth: Object,
  },
  methods: {
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
