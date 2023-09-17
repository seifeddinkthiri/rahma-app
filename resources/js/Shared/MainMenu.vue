<template>
  <div v-if="$page.props.auth.user && $page.props.auth.user.wait == false">
    <div class="mb-4">
      <Link class="group flex items-center py-3 relative w-full" href="/">
        <icon
          name="home"
          class="mr-2 w-4 h-4 ml-4"
          :class="isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'"
        />
        <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
          لوحة التحكم
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3 relative w-full" href="/projects">
        <icon
          name="dashboard"
          class="mr-2 w-4 h-4 ml-4"
          :class="
            isUrl('projects') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('projects') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          المشاريع
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3 relative w-full" href="/interventions">
        <icon
          name="interventions"
          class="mr-2 w-4 h-4 ml-4"
          :class="
            isUrl('interventions')
              ? 'fill-white'
              : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('interventions')
              ? 'text-white'
              : 'text-indigo-300 group-hover:text-white'
          "
        >
          التدخلات
        </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3 relative w-full" href="/beneficials">
        <icon
          name="beneficials"
          class="mr-2 w-4 h-4 ml-4"
          :class="
            isUrl('beneficials') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('beneficials') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          المنتفعين
        </div>
      </Link>
    </div>
    <div class="mb-4" v-if="$page.props.auth.user.admin || $page.props.auth.user.owner">
      <Link class="group flex items-center py-3 relative w-full" href="/users">
        <icon
          name="users"
          class="mr-2 w-4 h-4 ml-4"
          :class="
            isUrl('users') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('users') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          المستخدمين
        </div>
      </Link>
    </div>
    <div
      class="mb-4"
      v-if="
        ($page.props.auth.user && $page.props.auth.user.admin) ||
        ($page.props.auth.user && $page.props.auth.user.owner)
      "
    >
      <Link class="group flex items-center py-3 relative w-full" href="/wait_list">
        <icon
          name="users"
          class="mr-2 w-4 h-4 ml-4"
          :class="
            isUrl('wait_list') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
          "
        />
        <div
          :class="
            isUrl('wait_list') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
          "
        >
          قائمة الانتظار
        </div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";

export default {
  components: {
    Icon,
    Link,
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
