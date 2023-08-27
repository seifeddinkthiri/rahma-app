<template>
  <div>
    <!-- Family Card -->
    <div class="p-6 bg-white rounded-md shadow">
      <div class="relative">
        <ul class="absolute left-0 top-0 space-y-3">
          <li>
            <img
              v-if="family.photo"
              :src="`/uploads/${family.photo}`"
              alt="family Image"
              class="w-32 h-32 rounded"
            />
          </li>
          <li>
            <button
              @click="back"
              class="absolute left-0 mt-4 ml-4 pl-2 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              عودة
            </button>
          </li>
        </ul>
      </div>
      <Head :title="form.name" />

      <h1 class="pl-28 text-xl font-bold">
        <Link class="text-blue-400 hover:text-blue-600" href="/families">المنتفع</Link>
        <span class="text-blue-400 font-medium"> : </span>
        {{ form.name }}
      </h1>

      <h2 class="mt-12 text-2xl font-bold">العائلة</h2>

      <div class="mt-8 p-4 bg-white rounded-md shadow">
        <br />
        <br />
        <br />

        <table class="w-full">
          <tbody>
            <tr>
              <td class="px-4 py-2 border h-16">الاسم</td>
              <td class="px-4 py-2 border h-16">{{ form.name }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 border h-16">الهاتف</td>
              <td class="px-4 py-2 border h-16">{{ form.phone }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 border h-16">الحالة</td>
              <td class="px-4 py-2 border h-16">{{ form.status }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 border h-16">العنوان</td>
              <td class="px-4 py-2 border h-16">{{ form.address }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">الأفراد</h2>
    <br />
    <div>
      <div class="bg-white rounded-md shadow overflow-hidden">
        <br />
        <div class="flex flex-row items-center justify-around w-full">
          <p v-if="form.name == null" class="px-6 text-red-600">
            يجب تعيين معيل أسرة لهذه العائلة
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
                v-for="member in family.members.data"
                :key="member.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100"
              >
                <td class="border-t">
                  <Link
                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                    :to="`/members/${member.id}/edit`"
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
                    :to="`/members/${member.id}/edit`"
                    tabindex="-1"
                  >
                    <p v-if="member.kinship == 'husband'">زوج</p>
                    <p v-if="member.kinship == 'wife'">زوجة</p>
                    <p v-if="member.kinship == 'child'">إبن</p>
                    <p v-if="member.kinship == 'elderly'">مسن</p>
                    <p v-if="member.kinship == 'other_member'">فرد إضافي</p>
                  </Link>
                </td>
                <td class="border-t">
                  <p v-if="member.caregiver">معيل الأسرة</p>
                </td>
              </tr>
              <tr v-if="family.members.data.length === 0">
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
      <table class="table-auto w-full">
        <tbody v-for="(facility, index) in family.facilities" :key="index">
          <tr>
            <td class="px-4 py-2 border">الصرف الصحي</td>
            <td class="px-4 py-2 border" v-if="facility.Sanitation">
              <icon name="check" />
            </td>
            <td class="px-4 py-2 border" v-else><icon name="minus" /></td>
          </tr>
          <tr>
            <td class="px-4 py-2 border">الكهرباء</td>
            <td class="px-4 py-2 border" v-if="facility.electricity">
              <icon name="check" />
            </td>
            <td class="px-4 py-2 border" v-else><icon name="minus" /></td>
          </tr>
          <tr>
            <td class="px-4 py-2 border">الماء</td>
            <td class="px-4 py-2 border" v-if="facility.water">
              <icon name="check" />
            </td>
            <td class="px-4 py-2 border" v-else><icon name="minus" /></td>
          </tr>
          <tr>
            <td class="px-4 py-2 border">التهوئة</td>
            <td class="px-4 py-2 border" v-if="facility.ventilation">
              <icon name="check" />
            </td>
            <td class="px-4 py-2 border" v-else><icon name="minus" /></td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Notes -->
    <div>
      <h2 class="mt-12 text-2xl font-bold">الملاحظات</h2>
      <br />
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div v-for="note in family.notes.data" :key="note.id">
          <div class="p-4 bg-white rounded-md shadow text-center">
            <h3 class="mb-2 text-xl font-bold">{{ note.title }}</h3>
            <div v-if="toggledNoteIds.includes(note.id)">
              <p class="text-gray-700">{{ note.value }}</p>
            </div>
            <div v-else>
              <p class="text-gray-700 truncate">{{ note.value }}</p>
            </div>
            <button
              v-if="!toggledNoteIds.includes(note.id)"
              @click="toggleNoteVisibility(note.id)"
            >
              <icon name="cheveron-down" class="h-4 w-4" />
            </button>
            <button v-else @click="toggleNoteVisibility(note.id)">
              <icon name="cheveron-up" class="h-4 w-4" />
            </button>
          </div>
        </div>
        <div v-if="family.notes.data.length === 0">
          <p class="text-center text-xl font-bold">لا يوجد ملاحظات</p>
        </div>
      </div>
    </div>

    <!-- files -->
    <h2 class="mt-12 text-2xl font-bold">الملفات</h2>
    <br />
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="file in family.files" :key="file.id">
        <div class="p-4 bg-white rounded-md shadow">
          <a :href="getFileUrl(file.file)">{{ file.title }}</a>
        </div>
      </div>
      <div v-if="family.files.length === 0">
        <p class="text-center text-xl">لا يوجد ملفات</p>
      </div>
    </div>

    <!-- Interventions -->
    <h2 class="mt-12 text-2xl font-bold">التدخلات</h2>
    <br />
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="intervention in family.interventions" :key="intervention.id">
        <div class="p-4 bg-white rounded-md shadow">
          <Link :href="`/interventions/${intervention.id}/show`">
            <h3 class="mb-2 text-xl">
              <p v-if="intervention.type == 'cash'">النوع: نقدي</p>
              <p v-if="intervention.type == 'shipments'">النوع :عيني</p>
              <p v-if="intervention.type == 'other'">النوع :آخر</p>
            </h3>
            <h3 class="mb-2 text-xl">القيمة : {{ intervention.value }}</h3>
            <h3 class="mb-2 text-xl">المتدخل :{{ intervention.intervenor }}</h3>
          </Link>
        </div>
      </div>
      <div v-if="family.interventions.length === 0">
        <p class="text-center text-xl">لا يوجد تدخلات</p>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">المسكن</h2>
    <br />
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
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
          <p class="text-gray-700 truncate">{{ home.desciption }}</p>
        </div>
      </div>
      <div v-if="family.home.length === 0">
        <p class="text-center text-xl">لا يوجد مساكن</p>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";

export default {
  components: {
    Head,
    Icon,
    Link,
  },
  layout: Layout,
  props: {
    family: Object,
  },
  remember: "form",

  data() {
    return {
      toggledNoteIds: [],
      note_id: null,
      form: this.$inertia.form({
        name: this.family.name,
        phone: this.family.phone,
        status: this.family.status,
        address: this.family.address,
        photo: this.family.photo,
        id: this.family.id,
      }),
    };
  },

  methods: {
    back() {
      window.history.back();
    },
    getFileUrl(fileName) {
      const baseUrl = "http://127.0.0.1:8000";
      return `${baseUrl}/${fileName}`;
    },
    toggleNoteVisibility(noteId) {
      const index = this.toggledNoteIds.indexOf(noteId);
      if (index !== -1) {
        this.toggledNoteIds.splice(index, 1); // Remove the noteId from the array
      } else {
        this.toggledNoteIds.push(noteId); // Add the noteId to the array
      }
    },
  },
};
</script>
