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
              <logo class="fill-white" width="120" height="28" />
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
            <div v-if="auth.user.admin" class="mr-4 mt-1">admin</div>
            <div v-else-if="auth.user.owner" class="mr-4 mt-1">super user</div>
            <div v-else="auth.user.admin" class="mr-4 mt-1">user</div>
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
            class="hidden flex-shrink-0 p-12 w-56 bg-indigo-800 overflow-y-auto md:block"
          >
            <div>
              <div class="mb-4">
                <Link class="group flex items-center py-3" href="/">
                  <icon
                    name="dashboard"
                    class="mr-2 w-4 h-4"
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
              <div class="mb-4" v-if="auth.user.wait == false">
                <Link class="group flex items-center py-3" href="/families">
                  <icon
                    name="office"
                    class="mr-2 w-4 h-4"
                    :class="
                      isUrl('families')
                        ? 'fill-white'
                        : 'fill-indigo-400 group-hover:fill-white'
                    "
                  />
                  <div
                    :class="
                      isUrl('families')
                        ? 'text-white'
                        : 'text-indigo-300 group-hover:text-white'
                    "
                  >
                    المنتفعين العائلات
                  </div>
                </Link>
              </div>
              <div class="mb-4" v-if="!auth.user.wait">
                <Link class="group flex items-center py-3" href="/individuals">
                  <icon
                    name="office"
                    class="mr-2 w-4 h-4"
                    :class="
                      isUrl('individuals')
                        ? 'fill-white'
                        : 'fill-indigo-400 group-hover:fill-white'
                    "
                  />
                  <div
                    :class="
                      isUrl('individuals')
                        ? 'text-white'
                        : 'text-indigo-300 group-hover:text-white'
                    "
                  >
                    المنتفعين الأفراد
                  </div>
                </Link>
              </div>
              <div class="mb-4">
                <Link class="group flex items-center py-3" href="/interventions">
                  <icon
                    name="office"
                    class="mr-2 w-4 h-4"
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
                <Link class="group flex items-center py-3" href="/organizations">
                  <icon
                    name="office"
                    class="mr-2 w-4 h-4"
                    :class="
                      isUrl('organizations')
                        ? 'fill-white'
                        : 'fill-indigo-400 group-hover:fill-white'
                    "
                  />
                  <div
                    :class="
                      isUrl('organizations')
                        ? 'text-white'
                        : 'text-indigo-300 group-hover:text-white'
                    "
                  >
                    المنظمات
                  </div>
                </Link>
              </div>
              <div class="mb-4">
                <Link class="group flex items-center py-3" href="/contacts">
                  <icon
                    name="contact"
                    class="mr-2 w-4 h-4"
                    :class="
                      isUrl('contacts')
                        ? 'fill-white'
                        : 'fill-indigo-400 group-hover:fill-white'
                    "
                  />
                  <div
                    :class="
                      isUrl('contacts')
                        ? 'text-white'
                        : 'text-indigo-300 group-hover:text-white'
                    "
                  >
                    جهات الاتصال
                  </div>
                </Link>
              </div>
              <div class="mb-4">
                <Link class="group flex items-center py-3" href="/reports">
                  <icon
                    name="printer"
                    class="mr-2 w-4 h-4"
                    :class="
                      isUrl('reports')
                        ? 'fill-white'
                        : 'fill-indigo-400 group-hover:fill-white'
                    "
                  />
                  <div
                    :class="
                      isUrl('reports')
                        ? 'text-white'
                        : 'text-indigo-300 group-hover:text-white'
                    "
                  >
                    التقارير
                  </div>
                </Link>
              </div>
              <div class="mb-4" v-if="auth.user.admin || auth.user.owner">
                <Link class="group flex items-center py-3" href="/users">
                  <icon
                    name="users"
                    class="mr-2 w-4 h-4"
                    :class="
                      isUrl('contacts')
                        ? 'fill-white'
                        : 'fill-indigo-400 group-hover:fill-white'
                    "
                  />
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
              </div>
              <div class="mb-4" v-if="auth.user.admin">
                <Link class="group flex items-center py-3" href="/wait_list">
                  <icon
                    name="waiting_user"
                    class="mr-2 w-4 h-4"
                    :class="
                      isUrl('wait_list')
                        ? 'fill-white'
                        : 'fill-indigo-400 group-hover:fill-white'
                    "
                  />
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
// import MainMenu from '@/Shared/MainMenu'
import FlashMessages from "@/Shared/FlashMessages";

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Link,
    Logo,
    //MainMenu,
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
