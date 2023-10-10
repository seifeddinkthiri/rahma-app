<template>
  <div>
    <trashed-message
      v-if="deleted_intervention_id"
      class="mb-6"
      @restore="restore(deleted_intervention_id)"
      >تم حذف هذا التدخل .
    </trashed-message>

  <!-- Family Card -->
<div class="p-6 bg-white rounded-md shadow">
  <div class="relative">
    <ul class="absolute left-0 top-0 space-y-3">
      <li>
        <img
          v-if="family.photo"
          :src="`/uploads/${family.photo}`"
          alt="family Image"
          class="block w-32 h-32 rounded"
        />
      </li>
      <li>
        <button
          @click="back"
          class="absolute left-0 ml-4 mt-4 pl-2 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
        >
          عودة
        </button>
      </li>
    </ul>
    <br />
    <br /><br /><br /><br />
  </div>
  <Head :title="form.name" />

  <h1 class="pl-4 md:pl-28 text-xl font-bold">
    <Link class="text-blue-400 hover:text-blue-600" href="/families">المنتفع</Link>
    <span class="text-blue-400 font-medium"> : </span>
    {{ form.name }}
  </h1>

  <div class="mt-8 p-4 bg-white rounded-md shadow">
    <br />
    <br />
    <br />

    <div class="overflow-x-auto">
      <table class="w-full">
        <tbody>
          <tr>
            <td class="px-2 md:px-4 py-2 h-16 border">الاسم</td>
            <td class="px-2 md:px-4 py-2 h-16 border">{{ form.name }}</td>
          </tr>
          <tr>
            <td class="px-2 md:px-4 py-2 h-16 border">الهاتف</td>
            <td class="px-2 md:px-4 py-2 h-16 border">{{ form.phone }}</td>
          </tr>
          <tr>
            <td class="px-2 md:px-4 py-2 h-16 border">الحالة</td>
            <td class="px-2 md:px-4 py-2 h-16 border">
              <p v-if="form.status === 'active'">نشط</p>
              <p v-else-if="form.status === 'disabled'">محضور</p>
              <p v-else-if="form.status === 'inactive'">غير نشط</p>
            </td>
          </tr>
          <tr v-if="!family.is_family">
            <td class="px-2 md:px-4 py-2 h-16 border">الحالة المدنية</td>
            <td class="px-2 md:px-4 py-2 h-16 border">
              {{ translate_social_status(form.social_status) }}
            </td>
          </tr>
          <tr>
            <td class="px-2 md:px-4 py-2 h-16 border">العنوان</td>
            <td class="px-2 md:px-4 py-2 h-16 border">{{ form.address }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Family Members -->
<h2 class="mt-12 text-2xl font-bold">الأفراد</h2>
<br />
<div>
  <div class="bg-white rounded-md shadow overflow-hidden">
    <br />
    <div class="flex flex-col sm:flex-row items-center justify-around w-full">
      <p v-if="form.name == null" class="px-6 text-red-600">
        يجب تعيين معيل أسرة لهذه الأسرة
      </p>
    </div>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">الاسم</th>
            <th class="pb-4 pt-6 px-6">القرابة</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="member in filteredMembers"
            :key="member.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/members/${member.id}/show`"
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
                :href="`/members/${member.id}/show`"
                tabindex="-1"
              >
                <p v-if="member.kinship == 'husband'">زوج</p>
                <p v-if="member.kinship == 'wife'">زوجة</p>
                <p v-if="member.kinship == 'child'">إبن</p>
                <p v-if="member.kinship == 'elderly'">مسن</p>
                <p v-if="member.kinship == 'other_member'">فرد إضافي</p>
                <p v-if="member.kinship == 'single_mother'">أم عزباء</p>
              </Link>
            </td>
          </tr>
          <tr v-if="filteredMembers.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">لا يوجد أفراد</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Facilities -->
<h2 class="mt-12 text-2xl font-bold">المرافق الأساسية</h2>
<br />
<div class="p-4 bg-white rounded-md shadow">
  <div class="overflow-x-auto">
    <table class="table-auto w-full">
      <thead>
        <tr>
          <th class="px-4 py-2 border"></th>
          <th class="px-4 py-2 border" v-for="(facility, index) in family.facilities" :key="index">{{ facility.name }}</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="px-4 py-2 border">الصرف الصحي</td>
          <td class="px-4 py-2 border" v-for="(facility, index) in family.facilities" :key="index">
            <icon v-if="facility.Sanitation" name="check" />
            <icon v-else name="minus" />
          </td>
        </tr>
        <tr>
          <td class="px-4 py-2 border">الكهرباء</td>
          <td class="px-4 py-2 border" v-for="(facility, index) in family.facilities" :key="index">
            <icon v-if="facility.electricity" name="check" />
            <icon v-else name="minus" />
          </td>
        </tr>
        <tr>
          <td class="px-4 py-2 border">الماء</td>
          <td class="px-4 py-2 border" v-for="(facility, index) in family.facilities" :key="index">
            <icon v-if="facility.water" name="check" />
            <icon v-else name="minus" />
          </td>
        </tr>
        <tr>
          <td class="px-4 py-2 border">التكييف</td>
          <td class="px-4 py-2 border" v-for="(facility, index) in family.facilities" :key="index">
            <icon v-if="facility.ventilation" name="check" />
            <icon v-else name="minus" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- home -->
<h2 class="mt-12 text-2xl font-bold">المسكن</h2>
<br />
<div >
  <div v-for="home in family.home" :key="home.id">
    <div class="p-4 bg-white rounded-md shadow">
      <h3 class="mb-2 text-xl">
        <span v-if="home.status == 'Ownership'">ملك</span>
        <span v-else-if="home.status == 'without compensation'">بدون مقابل</span>
        <span v-else-if="home.status == 'inherited'">ورثة</span>
        <span v-else-if="home.status == 'lease'">إيجار</span>
      </h3>
      <p class="mb-2 text-gray-700" v-if="home.status == 'lease'">
        سعر الكراء: {{ home.allocation_price }}
      </p>
      <p class="text-gray-700">{{ home.desciption }}</p>
    </div>
  </div>
  <div v-if="family.home.length === 0">
    <p class="text-center text-xl">لا يوجد مساكن</p>
  </div>
</div>

<!-- Interventions -->
<h2 class="mt-12 text-2xl font-bold">التدخلات</h2>

<div class="mt-8 p-4 bg-white rounded-md shadow">
  <div class="flex items-center mt-4 pr-4">
    <button @click="show_intervention_modal = true" class="btn-indigo">
      إنشاء تدخل
    </button>
  </div>

  <div class="bg-white rounded-md shadow overflow-hidden mt-6" v-if="show_intervention_modal">
  <div class="mt-6 bg-white rounded shadow overflow-x-auto">
    <div>
      <div class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
        <div class="relative bg-white p-6 sm:p-8 rounded-md shadow max-h-full overflow-y-auto w-full sm:w-2/3 md:w-1/2 lg:w-1/3">
          <button class="absolute top-0 right-0 p-4" @click="show_intervention_modal = false">
            &times;
          </button>
          <form @submit.prevent="store_intervention">
            <div>
              <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <select-input
                  v-model="intervention_form.type"
                  class="pb-8 pr-6 lg:w-1/2 w-full md:w-1/2"
                  label="نوع التدخل"
                  :error="intervention_form.errors.type"
                  ref="typeSelect"
                >
                <option :value="null" selected disabled hidden>
                  إختر نوع التدخل
                </option>
                <option value="shipments">عيني</option>
                <option value="cash">نقدي</option>
                <option value="other">آخر</option>                </select-input>

                <text-input
                  v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                  v-model="intervention_form.value"
                  :error="intervention_form.errors.value"
                  label="قيمة التدخل"
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
                <!-- Other input fields... -->
                <text-input
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                type="date"
                id="date"
                v-model="intervention_form.date"
                :error="intervention_form.errors.date"
                label="تاريخ التدخل"
                placeholder=" تاريخ التدخل هنا"
              />
              <select-input
                v-model="intervention_form.project"
                :error="intervention_form.errors.project"
                class="pb-8 pr-6 lg:w-1/2 w-full md:w-1/2"
                label=" في إطار مشروع "

                ref="projectSelect"
              >
                <option :value="null" selected disabled hidden>
                  إختر المشروع                </option>
                <option v-for="project in projects" :value="project.id">
                  {{ project.name }}
                </option>
                <option disabled :value="null" v-if="projects.length == 0">
                  قائمة فارغة
                </option>
              </select-input>
              <text-input
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                id="intervenor"
                v-model="intervention_form.intervenor"
                :error="intervention_form.errors.intervenor"
                placeholder="إسم المسؤل هنا"
                label="إسم المسؤل"
              />
              <text-input
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                id="intervenor"
                v-model="intervention_form.intervenor_phone"
                :error="intervention_form.errors.intervenor_phone"
                placeholder="هاتف المسؤل هنا"
                label="هاتف المسؤل"
              />
              <file-input
                v-model="intervention_form.file"
                :error="intervention_form.errors.file"
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                type="file"
                label="أضف ملف"
              />
              <text-input
                v-model="intervention_form.title"
                :error="intervention_form.errors.title"
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                label="عنوان الملف"
                placeholder="عنوان الملف هنا"
              />
              <TextAreaInput
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                id="notes"
                v-model="intervention_form.notes"
                :error="intervention_form.errors.notes"
                placeholder="اكتب ملاحظاتك هنا"
                label="ملاحظات"
              />

           
              </div>
            </div>

            <button class="btn-indigo w-full lg:w-auto" type="submit">إضافة</button>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="overflow-x-auto">
    <table class="w-full whitespace-nowrap">
      <thead>
        <tr class="text-right font-bold">
          <th class="pb-4 pt-6 px-6">النوع</th>
          <th class="pb-4 pt-6 px-6">القيمة</th>
          <th class="pb-4 pt-6 px-6">التاريخ</th>
          <th class="pb-4 pt-6 px-6">المسؤول</th>
          <th class="pb-4 pt-6 px-6">هاتف المسؤول</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="intervention in family.interventions"
          :key="intervention.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/interventions/${intervention.id}/show`"
              tabindex="-1"
            >
              {{ intervention.type }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/interventions/${intervention.id}/show`"
              tabindex="-1"
            >
              {{ intervention.value }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/interventions/${intervention.id}/show`"
              tabindex="-1"
            >
              {{ intervention.date }}
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="focus:text-blue-500 flex items-center px-6 py-4"
              :href="`/interventions/${intervention.id}/show`"
            >
              {{ intervention.intervenor }}
              <icon
                v-if="intervention.deleted_at"
                name="trash"
                class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
              />
            </Link>
          </td>
          <td class="border-t">
            <Link
              class="flex items-center px-6 py-4"
              :href="`/interventions/${intervention.id}/show`"
              tabindex="-1"
            >
              {{ intervention.intervenor_phone }}
            </Link>
          </td>
          <td class="w-px border-t">
            <div class="flex items-center">
              <Link
                class="flex items-center px-4"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-4 h-4 fill-gray-400" />
              </Link>
              <Link
                class="flex items-center px-4"
                :href="`/interventions/${intervention.id}/show`"
                tabindex="-1"
              >
                <icon name="eye" />
              </Link>
              <button
                v-if="!intervention.deleted_at"
                class="flex items-center px-4"
                tabindex="-1"
                @click="delete_intervention(intervention.id)"
              >
                <icon name="delete" />
              </button>
              <button
                v-else
                class="flex items-center px-4"
                tabindex="-1"
                @click="restore(intervention.id)"
              >
                <icon name="restore" />
              </button>
            </div>
          </td>
        </tr>
        <tr v-if="family.interventions.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">قائمة فارغة</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>


<!-- Notes -->
<div class="mx-auto max-w-5xl overflow-x-auto">
  <h2 class="mt-12 text-2xl font-bold">الملاحظات</h2>
  <br />
  <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    <div v-for="note in family.notes.data" :key="note.id">
      <div class="p-4 md:p-6 lg:p-8 text-center bg-white rounded-md shadow overflow-hidden">
        <h3 class="mb-2 text-xl md:text-xl font-bold">{{ note.title }}</h3>
        <div v-if="toggledNoteIds.includes(note.id)">
          <p class="text-gray-700 whitespace-pre-line">{{ note.value }}</p>
        </div>
        <div v-else>
          <p class="text-gray-700 truncate">{{ note.value }}</p>
        </div>
        <div class="flex justify-start mt-2">
          <button
            v-if="!toggledNoteIds.includes(note.id)"
            @click="toggleNoteVisibility(note.id)"
            class="w-4 h-4"
          >
            <icon name="cheveron-down" />
          </button>
          <button
            v-else
            @click="toggleNoteVisibility(note.id)"
            class="w-4 h-4"
          >
            <icon name="cheveron-up" />
          </button>
        </div>
      </div>
    </div>
    <div v-if="family.notes.data.length === 0">
      <p class="text-center text-xl">لا يوجد ملاحظات</p>
    </div>
  </div>
</div>




  <!-- Files -->
<h2 class="mt-12 text-2xl font-bold">الملفات</h2>
<br />
<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 shadow overflow-x-auto">
  <div v-for="file in family.files" :key="file.id">
    <div class="p-4 bg-white rounded-md shadow">
      <a :href="getFileUrl(file.id)" target="_blank" class="text-blue-600">
        {{ file.title }}
      </a>
    </div>
  </div>
  <div v-if="family.files.length === 0">
    <p class="text-center text-xl">لا يوجد ملفات</p>
  </div>
</div>

  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import TrashedMessage from "@/Shared/TrashedMessage";
import FileInput from "@/Shared/FileInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput.vue";
import TextInput from "@/Shared/TextInput";
export default {
  components: {
    Head,
    Icon,
    Link,
    TrashedMessage,
    FileInput,
    SelectInput,
    TextareaInput,
    TextInput,
  },
  layout: Layout,
  props: {
    family: Object,
    projects : Object,
  },
  remember: "form",

  data() {
    return {
      show_intervention_modal: false,
      deleted_intervention_id: null,
      toggledNoteIds: [],
      note_id: null,
      intervention_form: this.$inertia.form({
        type: null,
        value: null,
        date: null,
        intervenor: null,
        intervenor_phone: null,
        file: null,
        title: null,
        notes: null,
        family: this.family.id,
      }),
      form: this.$inertia.form({
        name: this.family.name,
        phone: this.family.phone,
        status: this.family.status,
        social_status: this.family.social_status,
        address: this.family.address,
        photo: this.family.photo,
        id: this.family.id,
      }),
    };
  },
  computed: {
    filteredMembers() {
      return this.family.members.data.filter((member) => !member.caregiver);
    },
  },
  methods: {
    translate_social_status(status) {
      if (status === "widow") {
        return "أرملة";
      } else if (status === "elderly") {
        return "مسن";
      } else if (status === "single_mother") {
        return "أم عزباء";
      } else if (status === "divorced") {
        return "مطلقة";
      }
    },
    back() {
      window.history.back();
    },
    getFileUrl(id) {
      const fileUrl = `/openFile/${id}`;
      return fileUrl;
    },
    toggleNoteVisibility(noteId) {
      const index = this.toggledNoteIds.indexOf(noteId);
      if (index !== -1) {
        this.toggledNoteIds.splice(index, 1); // Remove the noteId from the array
      } else {
        this.toggledNoteIds.push(noteId); // Add the noteId to the array
      }
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
    delete_intervention(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف  هذا التدخل ")) {
        this.$inertia.delete(`/interventions/${id}`);
        this.deleted_intervention_id = id;
      }
    },

    restore(id) {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا التدخل  ؟")) {
        this.$inertia.put(`/interventions/${id}/restore`);
        this.deleted_intervention_id = null;
      }
    },
  },
};
</script>
