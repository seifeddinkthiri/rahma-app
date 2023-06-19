<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/families">العائلات</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="family.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذه العائلة.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.name"
            :error="form.errors.name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="الاسم"
          />

          <file-input
            v-model="form.photo"
            :error="form.errors.photo"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="الصورة"
          />
        </div>
        <div
          class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100 space-x-3"
        >
          <button
            v-if="!family.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            حذف العائلة
          </button>
          <loading-button
            :loading="form.processing"
            class="btn-indigo ml-auto"
            type="submit"
          >
            تعديل العائلة
          </loading-button>
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
          <thead>
            <tr class="text-right font-bold">
              <th class="pb-4 pt-6 px-6">الاسم</th>
              <th class="pb-4 pt-6 px-6" colspan="2">القرابة</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="member in family.members.data"
              :key="member.id"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
            >
              <td class="border-t">
                <Link
                  class="flex items-center px-6 py-4 focus:text-indigo-500"
                  :href="`/members/${member.id}/edit`"
                >
                  {{ member.name }}
                  <icon
                    v-if="member.deleted_at"
                    name="trash"
                    class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                  />
                </Link>
              </td>
              <td class="border-t">
                <Link
                  class="flex items-center px-6 py-4"
                  :href="`/members/${member.id}/edit`"
                  tabindex="-1"
                >
                  {{ member.kinship }}
                </Link>
              </td>
              <td class="border-t">
                <Link
                  v-if="member.caregiver"
                  class="flex items-center px-6 py-4"
                  :href="`/members/${member.id}/edit`"
                  tabindex="-1"
                >
                  معيل الأسرة
                </Link>
              </td>
              <td class="w-px border-t">
                <Link
                  class="flex items-center px-4"
                  :href="`/members/${member.id}/edit`"
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                </Link>
              </td>
            </tr>
            <tr v-if="family.members.length === 0">
              <td class="px-6 py-4 border-t" colspan="4">لم يتم العثور على الأفراد</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="note_modal"
      v-if="show_note_modal"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="save_note">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="title" class="block mb-2 text-gray-700 text-sm font-bold">
                العنوان
              </label>
              <input
                id="title"
                v-model="notes_form.title"
                :error="notes_form.errors.title"
                placeholder="اكتب عنوان الملاحظة ..."
                class="w-full"
              />
              <label
                for="message"
                class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
              >
                التفاصيل
              </label>
              <textarea
                v-model="notes_form.value"
                id="message"
                name="message"
                rows="5"
                placeholder="اكتب تفاصيل الملاحظة ..."
                class="w-full"
              ></textarea>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_note_modal = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button
                type="submit"
                class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
              >
                تسجيل
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="note_modal_update"
      v-if="show_note_modal_update"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="update_note">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="title" class="block mb-2 text-gray-700 text-sm font-bold">
                العنوان
              </label>
              <input
                id="title"
                v-model="notes_form_update.title"
                :error="notes_form_update.errors.title"
                placeholder="اكتب عنوان الملاحظة ..."
                class="w-full"
              />
              <label
                for="message"
                class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
              >
                التفاصيل
              </label>
              <textarea
                v-model="notes_form_update.value"
                id="message"
                name="message"
                rows="5"
                placeholder="اكتب تفاصيل الملاحظة ..."
                class="w-full"
              ></textarea>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_note_modal_update = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button
                type="submit"
                class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
              >
                تسجيل التعديلات
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">المرافق الأساسية</h2>
    <br />
    <div ref="facilities" class="max-w-3xl bg-white rounded shadow overflow-hidden">
      <br />
      <div class="flex items-center">
        <button class="btn-indigo" @click="edit_facilities">
          <span>تعديل</span>
          <span class="hidden md:inline">&nbsp;المرافق</span>
        </button>
      </div>
      <br />
      <div
        class="mt-6 bg-white rounded shadow overflow-x-auto"
        ref="facility_modal_update"
        v-if="show_facility_modal_update"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 flex items-center justify-center">
          <form @submit.prevent="update_facility">
            <div class="w-96 h-auto bg-white rounded shadow-xl">
              <div class="p-6">
                <ToggleCheckbox
                  :id="'sanitaion'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  :label="'الصرف الصحي'"
                  :isChecked="facilities_form_update.Sanitation"
                  @toggle="toggle_sanitation"
                />
                <ToggleCheckbox
                  :id="'electricity'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  :label="'الكهرباء'"
                  :isChecked="facilities_form_update.electricity"
                  @toggle="toggle_electricity"
                />
                <ToggleCheckbox
                  :id="'water'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  :label="'الماء'"
                  :isChecked="facilities_form_update.water"
                  @toggle="toggle_water"
                />
                <ToggleCheckbox
                  :id="'ventilation'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  :label="'التهوئة'"
                  :isChecked="facilities_form_update.ventilation"
                  @toggle="toggle_ventilation"
                />
              </div>
              <div class="flex justify-end px-4 py-3 bg-gray-50">
                <button
                  @click="show_facility_modal_update = false"
                  type="button"
                  class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                >
                  إلغاء
                </button>
                <button
                  type="submit"
                  class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
                >
                  تسجيل التعديلات
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">الملاحظات</h2>
    <br />
    <div ref="members" class="max-w-3xl bg-white rounded shadow overflow-hidden">
      <br />
      <div class="flex items-center">
        <button class="btn-indigo" @click="openNoteModal">
          <span>إنشاء</span>
          <span class="hidden md:inline">&nbsp;ملاحظة</span>
        </button>
      </div>
      <br />
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">العنوان</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="note in family.notes.data"
            :key="note.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <button
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                @click="edit_note(note.id)"
              >
                {{ note.title }}
                <icon
                  v-if="note.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </button>
            </td>
            <td class="w-px border-t">
              <button
                @click="edit_note(note.id)"
                type="button"
                class="flex items-center px-4"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </button>
            </td>
            <td class="w-px border-t">
              <button
                @click="destroy_note(note.id)"
                type="button"
                class="flex items-center px-4"
                tabindex="-1"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-5 h-5"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                  />
                </svg>
              </button>
            </td>
          </tr>
          <tr v-if="family.notes.length === 0">
            <td class="px-6 py-4 border-t" colspan="2">لا يوجد ملاحظات</td>
          </tr>
        </tbody>
      </table>
    </div>

    <h2 class="mt-12 text-2xl font-bold">المسكن</h2>
    <br />
    <div ref="members" class="max-w-3xl bg-white rounded shadow overflow-hidden">
      <br />
      <div class="flex items-center">
        <button class="btn-indigo" @click="openHomeModal">
          <span>إنشاء</span>
          <span class="hidden md:inline">&nbsp;المسكن</span>
        </button>
      </div>
      <br />
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">الوضعية القانونية</th>
            <th class="pb-4 pt-6 px-6">سعر الكراء</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="home in family.home"
            :key="home.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <button
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                @click="edit_home()"
              >
                {{ home.status }}
                <icon
                  v-if="home.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </button>
            </td>
            <td class="border-t">
              <button
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                @click="edit_home()"
              >
                {{ home.allocation_price }}
                <icon
                  v-if="home.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </button>
            </td>
            <td class="w-px border-t">
              <button
                @click="edit_home()"
                type="button"
                class="flex items-center px-4"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </button>
            </td>
          </tr>
          <tr v-if="family.home.length === 0">
            <td class="px-6 py-4 border-t" colspan="2">لا يوجد معطيات المسكن</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="home_modal"
      v-if="show_home_modal"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="save_home">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="homeStatus" class="block mb-2 text-gray-700 text-sm font-bold">
                الوضعية القانونية
              </label>
              <select-input
                v-model="home_form.status"
                :error="home_form.errors.status"
                class="pb-8 pr-6 w-full lg:w-1/2"
              >
                <option :value="null" />
                <option value="Ownership">ملك</option>
                <option value="without compensation">بدون مقابل</option>
                <option value="inherited">ورثة</option>
                <option value="lease">إيجار</option>
              </select-input>
              <div v-if="this.home_form.status == 'lease'">
                <label
                  for="allocation_price"
                  class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
                >
                  سعر الكراء
                </label>
                <input
                  v-model="home_form.allocation_price"
                  id="allocation_price"
                  name="allocation_price"
                  rows="5"
                  placeholder="اكتب سعر الكراء  ..."
                  class="w-full"
                />
              </div>
              <label
                for="homeDescription"
                class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
              >
                وصف المسكن
              </label>
              <textarea
                v-model="home_form.desciption"
                id="homeDescription"
                name="homeDescription"
                rows="5"
                placeholder="اكتب وصف المسكن ..."
                class="w-full"
              ></textarea>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_home_modal = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button
                type="submit"
                class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
              >
                تسجيل
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="home_modal_update"
      v-if="show_home_modal_update"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="update_home">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="homeStatus" class="block mb-2 text-gray-700 text-sm font-bold">
                الوضعية القانونية
              </label>
              <input
                id="homeStatus"
                v-model="home_form_update.status"
                :error="home_form_update.errors.status"
                placeholder="اكتب وضعية المسكن القانونية ..."
                class="w-full"
              />
              <label
                for="allocation_price"
                class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
              >
                سعر الكراء
              </label>
              <input
                v-model="home_form_update.allocation_price"
                id="allocation_price"
                name="allocation_price"
                rows="5"
                placeholder="اكتب سعر الكراء  ..."
                class="w-full"
              />
              <label
                for="homeDescription"
                class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
              >
                وصف المسكن
              </label>
              <textarea
                v-model="home_form_update.desciption"
                id="homeDescription"
                name="homeDescription"
                rows="5"
                placeholder="اكتب وصف المسكن ..."
                class="w-full"
              ></textarea>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_home_modal_update = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button
                type="submit"
                class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
              >
                تسجيل التعديلات
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";
import ToggleCheckbox from "../../Shared/ToggleCheckbox.vue";
import FileInput from "@/Shared/FileInput";
import SelectInput from "@/Shared/SelectInput";

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    ToggleCheckbox,
    FileInput,
  },
  layout: Layout,
  props: {
    family: Object,
  },
  remember: "form",

  data() {
    return {
      show_note_modal: false,
      show_home_modal: false,

      show_note_modal_update: false,
      show_facility_modal_update: false,
      show_home_modal_update: false,

      note_id: null,

      form: this.$inertia.form({
        name: this.family.name,
        photo: null,
      }),
      notes_form: this.$inertia.form({
        title: null,
        value: null,
      }),
      notes_form_update: this.$inertia.form({
        title: null,
        value: null,
      }),

      facilities_form_update: this.$inertia.form({
        Sanitation: false,
        electricity: false,
        water: false,
        ventilation: false,
      }),

      home_form: this.$inertia.form({
        status: null,
        allocation_price: null,
        desciption: null,
      }),
      home_form_update: this.$inertia.form({
        status: null,
        allocation_price: null,
        desciption: null,
      }),
    };
  },

  methods: {
    destroy_note(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذه الملاحظة؟")) {
        this.$inertia.delete(`/notes/${id}`);
      }
    },

    toggle_sanitation() {
      this.facilities_form_update.Sanitation = !this.facilities_form_update.Sanitation;
    },
    toggle_water() {
      this.facilities_form_update.water = !this.facilities_form_update.water;
    },
    toggle_electricity() {
      this.facilities_form_update.electricity = !this.facilities_form_update.electricity;
    },
    toggle_ventilation() {
      this.facilities_form_update.ventilation = !this.facilities_form_update.ventilation;
    },

    edit_facilities() {
      this.show_facility_modal_update = true;
      this.family.facilities.forEach((element) => {
        this.facilities_form_update.Sanitation = element.Sanitation;
        this.facilities_form_update.ventilation = element.ventilation;
        this.facilities_form_update.water = element.water;
        this.facilities_form_update.electricity = element.electricity;
      });
    },

    update_facility() {
      this.show_facility_modal_update = false;

      this.facilities_form_update.put(`/facilities/${this.family.id}`);
    },

    edit_note(id) {
      const note_title = this.family.notes.data
        .filter((note) => note.id === id)
        .map((note) => note.title);
      const note_value = this.family.notes.data
        .filter((note) => note.id === id)
        .map((note) => note.value);

      this.show_note_modal_update = true;
      this.notes_form_update.title = note_title.toString();
      this.notes_form_update.value = note_value.toString();
      this.note_id = id;
    },
    save_note() {
      this.show_note_modal = false;

      this.notes_form.post(`/notes/${this.family.id}`);
      this.notes_form.title = null;
      this.notes_form.value = null;
    },
    update_note() {
      this.show_note_modal_update = false;

      this.notes_form_update.put(`/notes/${this.note_id}`);
    },
    openNoteModal() {
      this.show_note_modal = true;
    },

    edit_home() {
      this.show_home_modal_update = true;

      const home_status = this.family.home.map((home) => home.status);
      const home_allocation_price = this.family.home.map((home) => home.allocation_price);
      const home_desciption = this.family.home.map((home) => home.desciption);

      this.home_form_update.status = home_status.toString();
      this.home_form_update.allocation_price = home_allocation_price.toString();
      this.home_form_update.desciption = home_desciption.toString();
    },
    save_home() {
      this.show_home_modal = false;

      this.home_form.post(`/home/${this.family.id}`);
      this.home_form.status = null;
      this.home_form.allocation_price = null;
      this.home_form.desciption = null;
    },
    update_home() {
      this.show_home_modal_update = false;

      this.home_form_update.put(`/home/${this.family.id}`);
    },
    openHomeModal() {
      this.show_home_modal = true;
    },

    update() {
      this.form.post(`/families_update/${this.family.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذه العائلة؟")) {
        this.$inertia.delete(`/families/${this.family.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذه العائلة؟")) {
        this.$inertia.put(`/families/${this.family.id}/restore`);
      }
    },
  },
};
</script>
