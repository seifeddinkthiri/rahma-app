<template>
  <div>
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:flex-col md:h-screen">
        <div class="md:flex md:flex-shrink-0">
          <div
            class="flex items-center justify-between px-6 py-4 bg-indigo-900 md:flex-shrink-0 md:justify-center md:w-56"
          >
            <Link class="mt-1" href="/">
              <logo class="fill-white h-16 w-16" width="120" height="28" />
            </Link>
            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <svg
                  class="w-6 h-6 fill-white"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                >
                  <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                </svg>
              </template>
              <template #dropdown>
                <div class="mt-2 px-8 py-4 bg-indigo-800 rounded shadow-lg">
                  <main-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div
            class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0"
          >
            <div v-if="auth.user.admin" class="mr-4 mt-1">مسؤل</div>
            <div v-else-if="auth.user.owner" class="mr-4 mt-1">مستخدم متميز</div>
            <div v-else="auth.user.admin" class="mr-4 mt-1">مستخدم</div>
            <dropdown class="mt-1" placement="bottom-end">
              <template #default>
                <div class="group flex items-center cursor-pointer select-none">
                  <div
                    class="mr-1 text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 whitespace-nowrap"
                  >
                    <span>{{ auth.user.first_name }}</span>
                    <span class="hidden md:inline">&nbsp;{{ auth.user.last_name }}</span>
                  </div>
                  <icon
                    class="w-5 h-5 fill-gray-700 group-hover:fill-indigo-600 focus:fill-indigo-600"
                    name="cheveron-down"
                  />
                </div>
              </template>
              <template #dropdown>
                <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                  <Link
                    class="block px-6 py-2 hover:text-white hover:bg-indigo-500"
                    :href="`/users/${auth.user.id}/edit`"
                    >ملفي</Link
                  >
                  <Link
                    class="block px-6 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                    href="/logout"
                    method="delete"
                    as="button"
                    >تسجيل خروج</Link
                  >
                </div>
              </template>
            </dropdown>
          </div>
        </div>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <div
            class="hidden flex-shrink-0 py-12 px-2 w-56 bg-indigo-800 overflow-y-auto md:block"
          >
            <div>
              <div class="mb-4">
                <Link class="group flex items-center py-3 relative w-full" href="/">
                  <icon
                    name="dashboard"
                    class="mr-2 w-4 h-4 ml-4"
                    :class="
                      isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'
                    "
                  />
                  <div
                    :class="
                      isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'
                    "
                  >
                    لوحة التحكم
                  </div>
                </Link>
              </div>

              <div class="mb-4">
                <Link
                  class="group flex items-center py-3 relative w-full"
                  href="/interventions"
                >
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
              <div class="mb-4" v-if="auth.user.wait == false">
                <Drop_down_minu>
                  <li>
                    <Link
                      class="group flex items-center py-3 relative w-full"
                      href="/families"
                    >
                      <div
                        :class="
                          isUrl('families')
                            ? 'text-white'
                            : 'text-indigo-300 group-hover:text-white'
                        "
                      >
                        العائلات
                      </div>
                    </Link>
                  </li>
                  <li>
                    <Link
                      class="group flex items-center py-3 relative w-full"
                      href="/individuals"
                    >
                      <div
                        :class="
                          isUrl('individuals')
                            ? 'text-white'
                            : 'text-indigo-300 group-hover:text-white'
                        "
                      >
                        الأفراد
                      </div>
                    </Link>
                  </li>
                </Drop_down_minu>
              </div>

              <div class="mb-4" v-if="auth.user.admin || auth.user.owner">
                <Drop_down_minu_members>
                  <li>
                    <Link
                      class="group flex items-center py-3 relative w-full"
                      href="/users"
                    >
                      <div
                        :class="
                          isUrl('users')
                            ? 'text-white'
                            : 'text-indigo-300 group-hover:text-white'
                        "
                      >
                        ادارة المستخدمين
                      </div>
                    </Link>
                  </li>
                  <li v-if="auth.user.admin">
                    <Link
                      class="group flex items-center py-3 relative w-full"
                      href="/wait_list"
                    >
                      <div
                        :class="
                          isUrl('wait_list')
                            ? 'text-white'
                            : 'text-indigo-300 group-hover:text-white'
                        "
                      >
                        قائمة الانتظار
                      </div>
                    </Link>
                  </li>
                </Drop_down_minu_members>
              </div>
            </div>
          </div>
          <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region>
            <flash-messages />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Logo from "@/Shared/Logo";
import Dropdown from "@/Shared/Dropdown";
import Drop_down_minu from "@/Shared/Drop_down_minu";
import Drop_down_minu_members from "@/Shared/Drop_down_minu_members";
import FlashMessages from "@/Shared/FlashMessages";

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Link,
    Logo,
    Drop_down_minu,
    Drop_down_minu_members,
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
