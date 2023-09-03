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

  methods: {
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
