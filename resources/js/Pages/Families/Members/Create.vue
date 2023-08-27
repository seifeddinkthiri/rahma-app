<template>

  <div>
    <Breadcrumb
      :Family_id="Family.id"
      :current_form_title="current_form_title"
      :active_step="active_step"
      :HorW="HorW"
      @update-H-or-W="updateHorW"
      @update-active-step="updateActiveStep"
      @update-current-form-title="updateCurrentFormTitle"
    />
    <div class="bg-white rounded-md shadow overflow-hidden" v-if="active_step == 'home'">
      <form @submit.prevent="save_home">
        <div class="p-6">
          <label for="homeStatus" class="block mb-2 text-gray-700 text-sm font-bold">
            الوضعية القانونية
          </label>
          <select-input
            :error="home_form.errors.status"
            v-model="home_form.status"
            class="form-input"
            ><option :value="null" disabled hidden>إختر وضعية المسكن</option>
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
            <text-input
              v-model="home_form.allocation_price"
              id="allocation_price"
              name="allocation_price"
              rows="5"
              placeholder="اكتب سعر الكراء"
              class="w-full"
            />
          </div>
          <label
            for="homeDescription"
            class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
          >
            وصف المسكن
          </label>
          <TextareaInput
            v-model="home_form.desciption"
            :error="home_form.errors.desciption"
            id="homeDescription"
            name="homeDescription"
            rows="5"
            placeholder="اكتب وصف المسكن"
            class="w-full"
          ></TextareaInput>
        </div>
        <div class="flex justify-end px-4 py-3 bg-gray-50">
          <button
            type="submit"
            class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
          >
            تسجيل
          </button>
        </div>
      </form>
    </div>
    <div
      class="bg-white rounded-md shadow overflow-hidden"
      v-if="active_step == 'facilities'"
    >
      <form @submit.prevent="define_facilities">
        <div class="p-6">
          <ToggleCheckbox
            :id="'sanitaion'"
            :active_value="'نعم'"
            :inactive_value="'لا'"
            :label="'الصرف الصحي'"
            :isChecked="facilities_form.Sanitation"
            @toggle="toggle_sanitation"
          />
          <ToggleCheckbox
            :id="'electricity'"
            :active_value="'نعم'"
            :inactive_value="'لا'"
            :label="'الكهرباء'"
            :isChecked="facilities_form.electricity"
            @toggle="toggle_electricity"
          />
          <ToggleCheckbox
            :id="'water'"
            :active_value="'نعم'"
            :inactive_value="'لا'"
            :label="'الماء'"
            :isChecked="facilities_form.water"
            @toggle="toggle_water"
          />
          <ToggleCheckbox
            :id="'ventilation'"
            :active_value="'نعم'"
            :inactive_value="'لا'"
            :label="'التهوئة'"
            :isChecked="facilities_form.ventilation"
            @toggle="toggle_ventilation"
          />
        </div>
        <div class="flex justify-end px-4 py-3 bg-gray-50">
          <button
            type="submit"
            class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
          >
            تسجيل
          </button>
        </div>
      </form>
    </div>

    <div class="bg-white rounded-md shadow overflow-hidden" v-if="active_step == 'notes'">
      <form @submit.prevent="save_note">
        <div class="p-6">
          <label for="title" class="block mb-2 text-gray-700 text-sm font-bold">
            العنوان
          </label>
          <text-input
            id="title"
            v-model="notes_form.title"
            :error="notes_form.errors.title"
            placeholder="اكتب عنوان الملاحظة"
            class="w-full"
          />
          <label for="message" class="block mb-2 mt-6 text-gray-700 text-sm font-bold">
            التفاصيل
          </label>
          <TextareaInput
            v-model="notes_form.value"
            :error="notes_form.errors.value"
            id="message"
            name="message"
            rows="5"
            placeholder="اكتب تفاصيل الملاحظة"
            class="w-full"
          ></TextareaInput>
        </div>
        <div class="flex justify-end px-4 py-3 bg-gray-50">
          <button
            type="submit"
            class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
          >
            تسجيل
          </button>
        </div>
      </form>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden" v-if="active_step == 'files'">
      <form @submit.prevent="save_files">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="files_form.title"
            :error="files_form.errors.title"
            class="pb-8 pr-6 w-full lg:w-1/2"
            id="file_title"
            name="file_title"
            placeholder="اكتب عنوان الملف"
            label="العنوان"
          />
          <file-input
            id="file"
            v-model="files_form.file"
            :error="files_form.errors.file"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            label="الملف"
          />
        </div>

        <div class="flex justify-end px-4 py-3 bg-gray-50">
          <button
            type="submit"
            class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
          >
            إضافة
          </button>
          <button
            @click="back"
            type="button"
            class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
          >
            عودة
          </button>
        </div>
      </form>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div v-if="active_step == 'part1'">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              :error="form.errors.name"
              placeholder=" الإسم هنا"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الإسم"
              :disabled="isFormDisabled"
            />

            <text-input
              v-model="form.address"
              :error="form.errors.address"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العنوان"
              placeholder=" العنوان هنا"
              :disabled="isFormDisabled"
            />
            <text-input
              v-model="form.cin"
              :error="form.errors.cin"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="بطاقة التعريف الوطنية"
              placeholder=" بطاقة التعريف الوطنية هنا"
              :disabled="isFormDisabled"
            />
            <text-input
              v-model="form.phone"
              :error="form.errors.phone"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الهاتف"
              placeholder="الهاتف هنا"
              :disabled="isFormDisabled"
            />
            <select-input
              v-model="form.birth_city"
              :error="form.errors.birth_city"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مدينة الولادة"
              :disabled="isFormDisabled"
            >
              <option :value="null" disabled hidden>إختر المدينة</option>
              <option value="مدنين">مدنين</option>
              <option value="باجة">باجة</option>
              <option value="تونس">تونس</option>
              <option value="صفاقس">صفاقس</option>
              <option value="سوسة">سوسة</option>
              <option value="أريانة">أريانة</option>
              <option value="بن عروس">بن عروس</option>
              <option value="القصرين">القصرين</option>
              <option value="الكاف">الكاف</option>
              <option value="المهدية">المهدية</option>
              <option value="منوبة">منوبة</option>
              <option value="المنستير">المنستير</option>
              <option value="نابل">نابل</option>
              <option value="سيدي بوزيد">سيدي بوزيد</option>
              <option value="سليانة">سليانة</option>
              <option value="قابس">قابس</option>
              <option value="جندوبة">جندوبة</option>
              <option value="تطاوين">تطاوين</option>
              <option value="توزر">توزر</option>
              <option value="زغوان">زغوان</option>
            </select-input>
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              id="birth_date"
              v-model="form.birth_date"
              :error="form.errors.birth_date"
              label="تاريخ الولادة"
              placeholder=" تاريخ الولادة هنا"
              :disabled="isFormDisabled"
            />

            <text-input
              v-model="form.job_place"
              :error="form.errors.job_place"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مكان العمل "
              placeholder="  مكان العمل هنا"
              :disabled="isFormDisabled"
            />
            <text-input
              v-model="form.job"
              :error="form.errors.job"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العمل"
              placeholder="   العمل هنا"
              :disabled="isFormDisabled"
            />
          </div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input
              v-model="form.social_status"
              :error="form.errors.social_status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الحالة المدنية "
              :disabled="isFormDisabled"
            >
              <option disabled hidden :value="null">إختر الحالة</option>
              <option value="single">أعزب/عزباء</option>
              <option value="married">متزوج/متزوجة</option>
              <option value="divorced">مطلق/مطلقة</option>
              <option value="widower">أرمل/أرملة</option>
            </select-input>

            <select-input
              v-model="form.education_level"
              :error="form.errors.education_level"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label=" المستوى الدراسي "
            >
              <option hidden disabled selected :value="null">إختر المستوى الدراسي</option>
              <option value="primary">إعدادي</option>
              <option value="secondary">ثاناوي</option>
              <option value="university ">جامعي</option>
            </select-input>
            <file-input
              v-model="form.photo"
              :error="form.errors.photo"
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="file"
              accept="image/*"
              label="الصورة "
            />
            <select-input
              v-if="HorW !== 'husband' && HorW !== 'wife'"
              v-model="form.kinship"
              :error="form.errors.kinship"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الفرد"
              :disabled="isFormDisabled"
            >
              <option :value="null" disabled hidden>إختر نوع الفرد</option>

              <option value="child">إبن</option>
              <option value="elderly">مسن</option>
              <option value="other_member">فرد آخر</option>
              <option value="single_mother">أم عزباء</option>
            </select-input>

            <ToggleCheckbox
              :id="'grant'"
              :class="'lg:w-1/2'"
              :isChecked="form.grant"
              :label="'منحة إجتماعية'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_grant"
              :isDisabled="isFormDisabled"
            />

            <div v-if="form.grant" class="w-full">
              <div class="flex flex-row flex-nowrap w-full">
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="source"
                  :error="form.errors.grant_source"
                  v-model="form.grant_source"
                  label="المصدر"
                  placeholder="المصدر هنا"
                  :disabled="isFormDisabled"
                />
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="value"
                  v-model="form.grant_value"
                  :error="form.errors.grant_value"
                  label="القيمة"
                  placeholder="القيمة بالدينار هنا"
                  :disabled="isFormDisabled"
                />
              </div>
            </div>

            <p class="text-18 pb-8 pr-6 w-full text-black font-bold">البيانات الصحة</p>
            <div class="flex flex-row flex-nowrap w-full">
              <ToggleCheckbox
                :id="'health_insurance'"
                :class="'lg:w-1/2'"
                :isChecked="form.health_insurance"
                :label="'التغطية الصحية'"
                :active_value="'نعم'"
                :inactive_value="'لا'"
                @toggle="toggle_health_insurance"
                :isDisabled="isFormDisabled"
              />

              <ToggleCheckbox
                :id="'good'"
                :class="'lg:w-1/2'"
                :isChecked="form.good"
                :label="'الحالة الصحية'"
                :active_value="'جيدة'"
                :inactive_value="'عليلة '"
                @toggle="toggle_health_Status"
                :isDisabled="isFormDisabled"
              />
            </div>
            <div v-if="form.good == false" class="w-full">
              <div class="flex flex-row flex-nowrap w-full">
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="disease"
                  :error="form.errors.disease"
                  v-model="form.disease"
                  label="مرض مزمن"
                  placeholder="  المرض المزمن هنا"
                  :disabled="isFormDisabled"
                />
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="disability"
                  v-model="form.disability"
                  :error="form.errors.disability"
                  label=" إعاقة"
                  placeholder="الإعاقة هنا"
                  :disabled="isFormDisabled"
                />

                <text-input
                  v-model="form.disability_card_number"
                  :error="form.errors.disability_card_number"
                  class="pb-8 pr-6 w-full lg:w-1/2"
                  label="رقم بطاقة الإعاقة"
                  placeholder="الرقم هنا"
                  :disabled="isFormDisabled"
                />
              </div>
            </div>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <!-- Add a spacer element to create space between the buttons -->
            <div class="w-4"></div>
            <loading-button
              :loading="form.processing"
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="submit"
            >
              إضافة
            </loading-button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";
import ToggleCheckbox from "@/Shared/ToggleCheckbox.vue";
import TextareaInput from "@/Shared/TextareaInput.vue";
import Breadcrumb from "@/Shared/Breadcrumb";
import FileInput from "@/Shared/FileInput";

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    ToggleCheckbox,
    TextareaInput,
    Breadcrumb,
    FileInput,
  },
  layout: Layout,
  remember: "form",
  props: {
    Family: Object,
  },
  data() {
    return {
      isFormDisabled: false,
      HorW: "husband",
      active_step: "part1",
      current_form_title: "أضف بيانات الزوج",
      current_form: "childrens",
      notes_form: this.$inertia.form({
        title: null,
        value: null,
      }),

      files_form: this.$inertia.form({
        title: null,
        file: null,
      }),
      facilities_form: this.$inertia.form({
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
      form: this.$inertia.form({
        name: null,
        family_id: this.Family.id,
        address: null,
        cin: null,
        phone: null,
        birth_date: null,
        birth_city: null,
        social_status: null,
        health_insurance: false,
        photo: null,
        kinship: null,
        caregiver: false,
        education_level: null,
        job: null,
        job_place: null,
        good: true,
        disease: null,
        disability: null,
        disability_card_number: null,
        grant: false,
        grant_source: null,
        grant_value: null,
      }),
    };
  },

  methods: {
    toggle_grant() {
      this.form.grant = !this.form.grant;
    },

    updateActiveStep(step) {
      this.active_step = step; // Update the active_step prop in the parent component
    },
    updateHorW(HorW) {
      this.HorW = HorW;
    },
    updateCurrentFormTitle(title) {
      this.current_form_title = title; // Update the current_form_title prop in the parent component
    },

    toggle_sanitation() {
      this.facilities_form.Sanitation = !this.facilities_form.Sanitation;
    },
    toggle_water() {
      this.facilities_form.water = !this.facilities_form.water;
    },
    toggle_electricity() {
      this.facilities_form.electricity = !this.facilities_form.electricity;
    },
    toggle_ventilation() {
      this.facilities_form.ventilation = !this.facilities_form.ventilation;
    },

    edit_facilities() {
      this.Family.facilities.forEach((element) => {
        this.facilities_form.Sanitation = element.Sanitation;
        this.facilities_form.ventilation = element.ventilation;
        this.facilities_form.water = element.water;
        this.facilities_form.electricity = element.electricity;
      });
    },
    save_note() {
      this.notes_form.post(`/notes/${this.Family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.active_step = "files";

          this.notes_form.reset();
        },
      });
    },
    save_files() {
      this.files_form.post(`/files/${this.Family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.active_step = "files";
          this.files_form.reset();
        },
      });
    },
    back() {
      this.$inertia.get("/families");
    },
    define_facilities() {
      this.facilities_form.put(`/facilities/${this.Family.id}`);
      this.active_step = "home";
      this.current_form_title = "بيانات المسكن";
    },

    save_home() {
      this.home_form.post(`/home/${this.Family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.active_step = "notes";
          this.current_form_title = " أضف ملاحظة";
        },
      });
    },
    toggle_health_insurance() {
      this.form.health_insurance = !this.form.health_insurance;
    },

    toggle_health_Status() {
      this.form.good = !this.form.good;
    },
    resetForm() {
      this.form.name = null;
      this.form.address = null;
      this.form.cin = null;
      this.form.phone = null;
      this.form.health_insurance = false;
      this.form.photo = null;
      this.form.education_level = null;
      this.form.job = null;
      this.form.job_place = null;
      this.form.good = true;
      this.form.disease = null;
      this.form.disability = null;
      this.form.disability_card_number = null;
    },
    store() {
      if (this.HorW == "husband") {
        this.form.kinship = "husband";
        this.form.post("/members", {
          preserveScroll: true,
          onSuccess: () => {
            this.resetForm();

            this.active_step = "part1";
            this.HorW = "wife";
            this.current_form_title = "أضف بيانات الزوجة";
          },
        });
      } else if (this.HorW == "wife") {
        this.form.kinship = "wife";
        this.form.post("/members", {
          preserveScroll: true,
          onSuccess: () => {
            this.resetForm();

            this.active_step = "part1";
            this.HorW = "";
            this.current_form_title = "أضف بيانات الأفراد";
          },
        });
      } else {
        this.form.post("/members", {
          preserveScroll: true,
          onSuccess: () => {
            this.resetForm();

            this.active_step = "part1";
            this.current_form_title = "إضافة فرد آخر";
          },
        });
      }
    },
  },
};
</script>
