<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/families">العائلات</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="family.deleted_at" class="mb-6" @restore="restore">تم حذف هذه العائلة. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="الاسم" />
          <text-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" label="الصورة" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100 space-x-3">
          <button v-if="!family.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">حذف العائلة</button>

          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit"> تعديل العائلة </loading-button>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">الأفراد</h2>
<br>
    <div ref="members" class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
    <br>
  <div class="flex items-center">

    <Link :href="`/members/${family.id}/create`" class="btn-indigo">
      <span>إنشاء</span>
      <span class="hidden md:inline">&nbsp;الفرد</span>
    </Link>
  </div>

  <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">الاسم</th>
          <th class="pb-4 pt-6 px-6">العنوان</th>
        </tr>
        <tr v-for="member in family.members.data" :key="member.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/members/${member.id}/edit`">
              {{ member.name }}
              <icon v-if="member.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>

          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/members/${member.id}/edit`" tabindex="-1">
              {{ member.address }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/members/${member.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="family.members.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">لم يتم العثور على الأفراد</td>
        </tr>
      </table>
    </div>
</div>

  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'


export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    family: Object,
    filters: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.family.name,
        photo: this.family.photo,

      }),
    }
  },


  methods: {


    update() {
      this.form.put(`/families/${this.family.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this family?')) {
        this.$inertia.delete(`/families/${this.family.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this family?')) {
        this.$inertia.put(`/families/${this.family.id}/restore`)
      }
    },
  },
}
</script>
