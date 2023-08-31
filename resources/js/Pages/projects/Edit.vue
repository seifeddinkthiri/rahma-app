<template>
  <div>
    <Head :title="'المشاريع'" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/project">المشاريع </Link>
      <span class="text-blue-400 font-medium"> : </span>
    </h1>
    <trashed-message v-if="project.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذا المشروع .
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="اسم المشروع"
              :error="form.errors.name"
              placeholder="اسم المشروع هنا"
            />
            <text-area-input
              v-model="form.description"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="وصف المشروع"
              :error="form.errors.description"
              placeholder="وصف المشروع هنا"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              v-model="form.date"
              :error="form.errors.date"
              label="تاريخ بدء المشروع"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              v-model="form.deadline"
              :error="form.errors.deadline"
              label="تاريخ الإنتهاء المتوقع"
            />

            <select-input
              v-model="form.status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="حالة المشروع"
              :error="form.errors.status"
            >
              <option value="" disabled hidden>إختر حالة المشروع</option>
              <option :value="false">قيد التنفيذ</option>
              <option :value="true">مكتمل</option>
            </select-input>
          </div>
        </div>
        <div
          class="flex items-center space-x-3 px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <button
            v-if="!project.deleted_at"
            class="bg-red-500 text-white hover:bg-red-600 focus:ring-red-600 focus:ring-opacity-50 ml-3 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded focus:outline-none"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            حذف المشروع
          </button>

          <loading-button
            :loading="form.processing"
            class="ml-auto inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            type="submit"
          >
            تعديل المشروع
          </loading-button>
        </div>

        <h2 class="mt-12 text-2xl font-bold">التدخلات</h2>
        <br />

        <div class="mt-6 bg-white rounded shadow overflow-x-auto">
          <div class="flex items-center pr-4">
            <button
              @click="show_intervention_modal = true"
              class="inline-flex items-center justify-center my-4 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              أضف تدخل
            </button>
          </div>
          <div ref="intervention_modal" v-if="show_intervention_modal">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 flex items-center justify-center">
              <form @submit.prevent="store_intervention">
                <div class="w-96 h-auto bg-white rounded shadow-xl">
                  <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <select-input
                      v-model="intervention_form.type"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      :error="intervention_form.errors.type"
                    >
                      <option hidden disabled :value="null">نوع التدخل</option>
                      <option value="shipments">عيني</option>
                      <option value="cash">نقدي</option>
                      <option value="other">آخر</option>
                    </select-input>

                    <text-input
                      v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                      v-model="intervention_form.value"
                      :error="intervention_form.errors.value"
                      :id="
                        intervention_form.type === 'shipments'
                          ? 'shipments'
                          : intervention_form.type === 'cash'
                          ? 'cash'
                          : 'other'
                      "
                      :placeholder="
                        intervention_form.type === null
                          ? 'القيمة'
                          : intervention_form.type === 'shipments'
                          ? 'الكمية'
                          : intervention_form.type === 'cash'
                          ? 'المبلغ'
                          : 'القيمة الأخرى'
                      "
                    />

                    <text-input
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      id="intervenor"
                      v-model="intervention_form.intervenor"
                      :error="intervention_form.errors.intervenor"
                      placeholder="إسم المسؤل هنا"
                    />
                    <text-input
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      id="intervenor"
                      v-model="intervention_form.intervenor_phone"
                      :error="intervention_form.errors.intervenor_phone"
                      placeholder="هاتف المسؤل هنا"
                    />
                    <file-input
                      v-model="intervention_form.file"
                      :error="intervention_form.errors.file"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      type="file"
                      label="أضف ملف"
                    />
                    <text-input
                      v-model="intervention_form.title"
                      :error="intervention_form.errors.title"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="عنوان الملف"
                      placeholder="عنوان الملف هنا"
                    />
                  </div>
                  <div
                    class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
                  >
                    <button
                      class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                      type="submit"
                    >
                      إضافة
                    </button>
                    <button
                      @click="show_intervention_modal = false"
                      class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                      type="button"
                    >
                      عودة
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div ref="members" class="bg-white rounded shadow overflow-hidden">
          <table class="table-auto w-full text-right">
            <thead class="text-right">
              <tr class="h-12 text-right font-bold">
                <th class="pr-4">النوع</th>
                <th class="pr-4">القيمة</th>
                <th class="pr-4">المتدخل</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="intervention in project.interventions"
                :key="intervention.id"
                class="h-12 hover:bg-gray-100 focus-within:bg-gray-100"
              >
                <td class="px-6 py-4 border-t">
                  <Link
                    class="flex items-center"
                    :href="`/interventions/${intervention.id}/edit`"
                    tabindex="-1"
                  >
                    <p class="whitespace-nowrap">
                      {{ intervention.type === "cash" ? "نقدي" : "عيني" }}
                    </p>
                  </Link>
                </td>
                <td class="px-6 py-4 border-t">
                  <Link
                    class="flex items-center"
                    :href="`/interventions/${intervention.id}/edit`"
                    tabindex="-1"
                  >
                    <p class="whitespace-nowrap">{{ intervention.value }}</p>
                  </Link>
                </td>
                <td class="px-6 py-4 border-t">
                  <Link
                    class="flex items-center"
                    :href="`/interventions/${intervention.id}/edit`"
                    tabindex="-1"
                  >
                    <p class="whitespace-nowrap">{{ intervention.intervenor }}</p>
                  </Link>
                </td>
                <td class="w-px border-t">
                  <Link
                    class="flex items-center px-4"
                    :href="`/interventions/${intervention.id}/show`"
                    tabindex="-1"
                  >
                    <icon name="eye" class="block w-6 h-6" />
                  </Link>
                </td>
                <td class="w-px border-t">
                  <button
                    @click="destroy_intervention(intervention.id)"
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
              <tr v-if="project.interventions.length === 0">
                <td class="px-6 py-4 border-t" colspan="3">لا يوجد تدخلات</td>
              </tr>
            </tbody>
          </table>
        </div>
      </form>

      <h2 class="mt-12 text-2xl font-bold">المنتفعين</h2>
      <br />

      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <div class="flex items-center pr-4">
          <button
            @click="show_beneficials_modal = true"
            class="inline-flex items-center justify-center my-4 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
          >
            أضف منتفع
          </button>
        </div>
        <div
          v-if="show_beneficials_modal"
          class="fixed inset-0 flex items-center justify-center z-50 rounded"
        >
          <div
            class="bg-gray-500 bg-opacity-75 fixed inset-0 transition-opacity rounded"
          ></div>
          <div class="bg-white w-96 rounded shadow-xl z-10">
            <div class="px-4 py-6 bg-gray-50 rounded">
              <form @submit.prevent="link_beneficial">
                <div class="flex justify-center flex-col items-center rounded">
                  <select-input
                    v-model="beneficial_form.type"
                    class="pb-8 pr-6 w-full lg:w-1/2"
                    label="نوع المنتفع"
                    :error="beneficial_form.errors.type"
                  >
                    <option :value="null" selected disabled hidden>
                      إختر نوع المنتفع
                    </option>
                    <option value="family">عائلة معوزة</option>
                    <option value="elderly">مسن</option>
                    <option value="divorced">مطلقة</option>
                    <option value="widow">أرملة</option>
                    <option value="single_mother">أم عزباء</option>
                  </select-input>
                  <select-input
                    v-if="beneficial_form.type == 'family'"
                    v-model="beneficial_form.family"
                    :error="beneficial_form.errors.family"
                    required
                    class="pb-8 pr-6 w-full lg:w-1/2"
                    label="العائلة"
                  >
                    <option hidden disabled :value="null">إختر العائلة</option>

                    <option v-for="family in filteredFamilies" :value="family.id">
                      {{ family.caregiver_phone }} - {{ family.name }}
                    </option>
                    <option :value="null" v-if="filteredFamilies.length == 0">
                      قائمة فارغة
                    </option>
                  </select-input>
                  <select-input
                    v-if="beneficial_form.type == 'elderly'"
                    v-model="beneficial_form.family"
                    :error="beneficial_form.errors.family"
                    required
                    class="pb-8 pr-6 w-full lg:w-1/2"
                    label="المسن"
                  >
                    <option hidden disabled :value="null">إختر المسن</option>

                    <option v-for="elderly in elderlies" :value="elderly.id">
                      {{ elderly.caregiver_phone }} - {{ elderly.name }}
                    </option>
                    <option :value="null" v-if="elderlies.length == 0">
                      قائمة فارغة
                    </option>
                  </select-input>
                  <select-input
                    v-if="beneficial_form.type == 'divorced'"
                    v-model="beneficial_form.family"
                    :error="beneficial_form.errors.family"
                    required
                    class="pb-8 pr-6 w-full lg:w-1/2"
                    label="المطلقة"
                  >
                    <option hidden disabled :value="null">إختر المطلقة</option>

                    <option v-for="divorced in divorceds" :value="divorced.id">
                      {{ divorced.caregiver_phone }} - {{ divorced.name }}
                    </option>
                    <option :value="null" v-if="divorceds.length == 0">
                      قائمة فارغة
                    </option>
                  </select-input>
                  <select-input
                    v-if="beneficial_form.type == 'single_mother'"
                    v-model="beneficial_form.family"
                    :error="beneficial_form.errors.family"
                    required
                    class="pb-8 pr-6 w-full lg:w-1/2"
                    label="الأم العزباء"
                  >
                    <option hidden disabled :value="null">إختر الأم العزباء</option>

                    <option
                      v-for="singleMother in singleMothers"
                      :value="singleMother.id"
                    >
                      {{ singleMother.caregiver_phone }} - {{ singleMother.name }}
                    </option>
                    <option :value="null" v-if="singleMothers.length == 0">
                      قائمة فارغة
                    </option>
                  </select-input>

                  <select-input
                    v-if="beneficial_form.type == 'widow'"
                    v-model="beneficial_form.family"
                    :error="beneficial_form.errors.family"
                    required
                    class="pb-8 pr-6 w-full lg:w-1/2"
                    label="الأرملة"
                  >
                    <option hidden disabled :value="null">إختر الأرملة</option>

                    <option v-for="widow in widows" :value="widow.id">
                      {{ widow.caregiver_phone }} - {{ widow.name }}
                    </option>
                    <option :value="null" v-if="widows.length == 0">قائمة فارغة</option>
                  </select-input>
                  <div class="flex justify-between">
                    <button
                      @click="show_beneficials_modal = false"
                      type="button"
                      class="mt-3 mx-2 inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                    >
                      إلغاء
                    </button>
                    <button
                      type="submit"
                      class="mt-3 mx-2 inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                    >
                      إضافة
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div ref="members" class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
          <thead>
            <tr class="text-right font-bold">
              <th class="pb-4 pt-6 px-6">المعيل</th>
              <th class="pb-4 pt-6 px-6">العنوان</th>
              <th class="pb-4 pt-6 px-6">الهاتف</th>
              <th class="pb-4 pt-6 px-6">الحالة</th>
              <th class="pb-4 pt-6 px-6">الحالة المدنية</th>
              <th class="pb-4 pl-3 pt-6">الصورة</th>
              <th class="pb-4 pl-3 pt-6" colspan="3">إجراءات</th>
            </tr>
          </thead>
          <tbody class="text-right">
            <tr
              v-for="family in beneficials"
              :key="family.id"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
            >
              <td class="border-t" v-if="family.name">
                <Link
                  class="flex items-center px-6 py-4 focus:text-indigo-500"
                  :href="`/families/${family.id}/edit`"
                >
                  {{ family.name }}
                </Link>
              </td>
              <td class="border-t" v-if="family.name">
                <Link
                  class="flex items-center px-6 py-4 focus:text-indigo-500"
                  :href="`/families/${family.id}/edit`"
                >
                  {{ family.address }}
                </Link>
              </td>
              <td class="border-t" v-if="family.name">
                <Link
                  class="flex items-center px-6 py-4 focus:text-indigo-500"
                  :href="`/families/${family.id}/edit`"
                >
                  {{ family.phone }}
                </Link>
              </td>
              <td class="border-t" v-if="family.name">
                <Link
                  class="flex items-center px-6 py-4 focus:text-indigo-500"
                  :href="`/families/${family.id}/edit`"
                >
                  <p v-if="family.status == 'active'">نشط</p>
                  <p v-if="family.status == 'inactive'">غير نشط</p>
                  <p v-if="family.status == 'disabled'">محضور</p>
                </Link>
              </td>
              <td class="border-t" v-if="family.name">
                <Link
                  class="flex items-center px-6 py-4 focus:text-indigo-500"
                  :href="`/families/${family.id}/edit`"
                >
                  <p v-if="family.social_status == 'widow'">أرملة</p>
                  <p v-if="family.social_status == 'divorced'">مطلقة</p>
                  <p v-if="family.social_status == 'single_mother'">أم عزباء</p>
                  <p v-if="family.social_status == 'elderly'">مسن</p>
                  <p v-if="family.social_status == null"></p>
                </Link>
              </td>
              <td class="border-t" v-if="family.name">
                <Link
                  class="flex items-center mr-8 py-4"
                  :href="`/families/${family.id}/edit`"
                  tabindex="-1"
                >
                  <img
                    v-if="family.photo"
                    class="h-10 w-10 block -my-2 mr-2 rounded"
                    :src="'uploads/' + family.photo"
                  />
                </Link>
              </td>
              <td class="w-px border-t" v-if="family.name">
                <Link
                  class="flex items-center px-4"
                  :href="`/members/${family.id}/create_new_one`"
                  tabindex="-1"
                >
                  <div
                    v-if="family.is_family && family.members.length < 2 && family.name"
                    class="flex items-center justify-between px-4 sm:px-20"
                  >
                    <p class="flex-grow px-3 py-2 text-red-600 whitespace-nowrap">
                      تحتوي العائلة فردين على الأقل
                    </p>
                  </div>
                </Link>
              </td>

              <td class="w-px border-t" v-if="family.name">
                <Link
                  class="flex items-center px-4"
                  :href="`/families/${family.id}/edit`"
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                </Link>
              </td>
              <td class="w-px border-t" v-if="family.name">
                <Link
                  class="flex items-center px-4"
                  :href="`/families/${family.id}/show`"
                  tabindex="-1"
                >
                  <icon name="eye" />
                </Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";
import FileInput from "@/Shared/FileInput";
import TextAreaInput from "@/Shared/TextareaInput";

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    FileInput,
    TextAreaInput,
  },
  layout: Layout,
  props: {
    elderlies: Object,
    divorceds: Object,
    singleMothers: Object,
    widows: Object,
    families: Object,
    beneficials: Object,
    project: Object,
  },
  remember: "form",
  created() {
    if (this.project.status == 1) {
      this.form.status = true;
    } else {
      this.form.status = false;
    }
  },
  data() {
    return {
      show_intervention_modal: false,
      show_beneficials_modal: false,
      beneficial_form: this.$inertia.form({
        type: null,
        family: null,
      }),
      intervention_form: this.$inertia.form({
        type: null,
        value: null,
        intervenor: null,
        intervenor_phone: null,
        file: null,
        title: null,
        notes: null,
        family: null,
        project: this.project.id,
      }),
      form: this.$inertia.form({
        name: this.project.name,
        description: this.project.description,
        date: this.project.date,
        deadline: this.project.deadline,
        status: this.project.status,
      }),
    };
  },
  computed: {
    filteredFamilies() {
      return this.families.filter((family) => family.name !== null);
    },
  },
  methods: {
    link_beneficial() {
      this.beneficial_form.post(`/link_beneficial/${this.project.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.beneficial_form.reset();
          this.show_beneficials_modal = false;
        },
      });
    },
    store_intervention() {
      this.intervention_form.post("/interventions", {
        preserveScroll: true,
        onSuccess: () => {
          this.intervention_form.reset();
          this.show_intervention_modal = false;
        },
      });
    },
    update() {
      this.form.post(`/projects/${this.project.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا المشروع ؟")) {
        this.$inertia.delete(`/projects/${this.project.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا المشروع ؟")) {
        this.$inertia.put(`/projects/${this.project.id}/restore`);
      }
    },
  },
};
</script>
