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
    <br />
    <div ref="members" class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <br />
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

    <div class="mt-6 bg-white rounded shadow overflow-x-auto" ref="note_modal" v-if="show_note_modal">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="save_note">
          <div class="w-full max-w-lg bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="title" class="block mb-2 text-gray-700 text-sm font-bold"> العنوان </label>
              <input id="title" v-model="notes_form.title" :error="notes_form.errors.title" placeholder="اكتب عنوان الملاحظة ..." />

              <label for="message" class="block mb-2 mt-6 text-gray-700 text-sm font-bold"> التفاصيل </label>
              <textarea v-model="notes_form.value" id="message" name="message" rows="5" placeholder="اكتب تفاصيل الملاحظة ..."></textarea>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button @click="show_note_modal = false" type="button" class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded-md focus:outline-none">Cancel</button>
              <button type="submit" class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded-md focus:outline-none">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="mt-6 bg-white rounded shadow overflow-x-auto" ref="note_modal_update" v-if="show_note_modal_update">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="update_note">
          <div class="w-full max-w-lg bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="title_update" class="block mb-2 text-gray-700 text-sm font-bold"> العنوان </label>
              <input id="title_update" v-model="notes_form_update.title" :error="notes_form_update.errors.title" placeholder="اكتب عنوان الملاحظة ..." />

              <label for="message" class="block mb-2 mt-6 text-gray-700 text-sm font-bold"> التفاصيل </label>
              <textarea v-model="notes_form_update.value" :error="notes_form_update.errors.value" id="message" name="message" rows="5" placeholder="اكتب تفاصيل الملاحظة ..."></textarea>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button @click="show_note_modal_update = false" type="button" class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded-md focus:outline-none">Cancel</button>
              <button type="submit" class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded-md focus:outline-none">Save updates</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">الملاحظات</h2>
    <br />
    <div ref="members" class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <br />
      <div class="flex items-center">
        <button class="btn-indigo" @click="openNoteModal">
          <span>إنشاء</span>
          <span class="hidden md:inline">&nbsp;ملاحظة</span>
        </button>
      </div>
      <br />

      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">العنوان</th>
        </tr>
        <tr v-for="note in family.notes.data" :key="note.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <button class="flex items-center px-6 py-4 focus:text-indigo-500" @click="edit_note(note.id)">
              {{ note.title }}
              <icon v-if="note.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </button>
          </td>

          <td class="w-px border-t">
            <button @click="edit_note(id)" type="button" class="flex items-center px-4" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </button>
          </td>
        </tr>
        <tr v-if="family.notes.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">لا يوجد ملاحظات</td>
        </tr>
      </table>
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
      show_note_modal: false,
      show_note_modal_update :false,
      note_id: null,
      form: this.$inertia.form({
        name: this.family.name,
        photo: this.family.photo,
      }),
      notes_form: this.$inertia.form({
        title: null,
        value: null,
      }),
      notes_form_update: this.$inertia.form({
        title: null,
        value: null,
      }),
    }
  },

  methods: {
    edit_note(id) {
     const note_title = this.family.notes.data.filter((note) => note.id === id).map((note) => note.title);
      const note_value = this.family.notes.data.filter((note) => note.id === id).map((note) => note.value);

      this.show_note_modal_update = true
      this.notes_form_update.title = note_title.toString() ;
      this.notes_form_update.value = note_value.toString() ;
      this.note_id = id;
    },
    save_note() {
      this.show_note_modal = false

      this.notes_form.post(`/notes/${this.family.id}`)
      this.notes_form.title=null;
      this.notes_form.value=null;
    },
    update_note() {
      this.show_note_modal_update = false

      this.notes_form_update.put(`/notes/${this.note_id}`)
    },
    openNoteModal() {
      this.show_note_modal = true
    },
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
