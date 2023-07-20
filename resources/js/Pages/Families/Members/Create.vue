<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-3xl font-bold">
      {{ current_form_title }}
    </h1>

    <div
      class="max-w-3xl bg-white rounded-md shadow overflow-hidden"
      v-if="active_step == 'home'"
    >
      <form @submit.prevent="save_home">
        <div class="p-6">
          <label for="homeStatus" class="block mb-2 text-gray-700 text-sm font-bold">
            الوضعية القانونية
          </label>
          <select-input
            :error="home_form.errors.status"
            v-model="home_form.status"
            class="form-input"
          >
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
          <TextareaInput
            v-model="home_form.desciption"
            :error="home_form.errors.desciption"
            id="homeDescription"
            name="homeDescription"
            rows="5"
            placeholder="اكتب وصف المسكن ..."
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
      class="max-w-3xl bg-white rounded-md shadow overflow-hidden"
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

    <div
      class="max-w-3xl bg-white rounded-md shadow overflow-hidden"
      v-if="active_step == 'notes'"
    >
      <form @submit.prevent="save_note">
        <div class="p-6">
          <label for="title" class="block mb-2 text-gray-700 text-sm font-bold">
            العنوان
          </label>
          <text-input
            id="title"
            v-model="notes_form.title"
            :error="notes_form.errors.title"
            placeholder="اكتب عنوان الملاحظة ..."
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
            placeholder="اكتب تفاصيل الملاحظة ..."
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
          <button
            @click="back_to_family_create"
            type="button"
            class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
          >
            عودة
          </button>
        </div>
      </form>
    </div>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div ref="part1" v-if="active_step == 1">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              :error="form.errors.name"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الإسم"
            />
            <text-input
              v-model="form.address"
              :error="form.errors.address"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العنوان"
            />
            <div class="w-full flex flex-row flex-nowrap">
              <ToggleCheckbox
                :id="'cin_verif'"
                :isChecked="form.cin_verif"
                :label='"بطاقة التعريف الوطنية"'
                :active_value="'نعم'"
                :inactive_value="'لا'"
                @toggle="toggle_cin"
              />
              <text-input
                v-if="form.cin_verif"
                class="pb-8 pr-6 w-full"
                id="cin"
                v-model="form.cin"
                :error="form.errors.cin"
                label="رقم بطاقة التعريف الوطنية"
              />
              <text-input
                v-else
                disabled
                class="pb-8 pr-6 w-full"
                id="cin"
                label="لا يوجد"
              />
            </div>
            <div class="w-full flex flex-row flex-nowrap">
              <ToggleCheckbox
                :id="'phone_verif'"
                :isChecked="form.phone_verif"
                :label='"الهاتف"'
                :active_value="'نعم'"
                :inactive_value="'لا'"
                @toggle="toggle_phone"
              />
              <text-input
                v-if="form.phone_verif"
                class="pb-8 pr-6 w-full"
                id="phone"
                v-model="form.phone"
                :error="form.errors.phone"
                label="رقم الهاتف"
              />
              <text-input
                v-else
                disabled
                class="pb-8 pr-6 w-full"
                id="phone"
                label="لا يوجد"
              />
            </div>
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              id="birth_date"
              v-model="form.birth_date"
              :error="form.errors.birth_date"
              label="تاريخ الولادة"
            />
            <select-input
              v-model="form.birth_city"
              :error="form.errors.birth_city"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مدينة الولادة"
            >
              <option value="Medenine">مدنين</option>
              <option value="Beja">باجة</option>
              <option value="Tunis">تونس</option>
              <option value="Sfax">صفاقس</option>
              <option value="Sousse">سوسة</option>
              <option value="Ariana">أريانة</option>
              <option value="Ben Arous">بن عروس</option>
              <option value="Kasserine">القصرين</option>
              <option value="Le Kef">الكاف</option>
              <option value="Mahdia">المهدية</option>
              <option value="Manouba">منوبة</option>
              <option value="Monastir">المنستير</option>
              <option value="Nabeul">نابل</option>
              <option value="Sidi Bouzid">سيدي بوزيد</option>
              <option value="Siliana">سليانة</option>
              <option value="Gabes">قابس</option>
              <option value="Jendouba">جندوبة</option>
              <option value="Tataouine">تطاوين</option>
              <option value="Tozeur">توزر</option>
              <option value="Zaghouan">زغوان</option>
            </select-input>

            <div class="w-full flex flex-row flex-nowrap">
              <ToggleCheckbox
                :id="'job_verif'"
                :isChecked="form.job_verif"
                :label='"يعمل"'
                :active_value="'نعم'"
                :inactive_value="'لا'"
                @toggle="toggle_job"
              />
              <text-input
                v-if="form.job_verif"
                class="pb-8 pr-6 w-full"
                id="job"
                v-model="form.job"
                :error="form.errors.job"
                label="العمل"
              />
              <text-input
                v-else
                disabled
                class="pb-8 pr-6 w-full"
                id="job"
                label="لا يوجد"
              />
              <text-input
                v-if="form.job_verif"
                class="pb-8 pr-6 w-full"
                id="job_place"
                v-model="form.job_place"
                :error="form.errors.job_place"
                label="مكان العمل"
              />
              <text-input
                v-else
                disabled
                class="pb-8 pr-6 w-full"
                id="job_place"
                label="لا يوجد"
              />
            </div>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button @click="active_step = 2" class="btn-indigo" type="button">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-4"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15.75 19.5L8.25 12l7.5-7.5"
                />
              </svg>
            </button>
          </div>
        </div>
        <div ref="part2" v-if="active_step == 2">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input
              v-model="form.social_status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الوضعية الأجتماعية"
            >
              <option :value="null" />
              <option value="single">أعزب/عزباء</option>
              <option value="married">متزوج/متزوجة</option>
              <option value="divorced">مطلق/مطلقة</option>
              <option value="Widower">أرمل/أرملة</option>
            </select-input>
            <text-input
              v-model="form.monthly_income"
              :error="form.errors.monthly_income"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الدخل الشهري"
            />

            <div class="w-full flex flex-row flex-nowrap">
              <ToggleCheckbox
                :id="'education_verif'"
                :isChecked="form.education_verif"
                :label='"الدراسة"'
                :active_value="'نعم'"
                :inactive_value="'لا'"
                @toggle="toggle_education"
              />
              <text-input
                v-if="form.education_verif"
                class="pb-8 pr-6 w-full"
                id="education_level"
                v-model="form.education_level"
                :error="form.errors.education_level"
                label="المستوى الدراسي"
              />
              <text-input
                v-else
                disabled
                class="pb-8 pr-6 w-full"
                id="education_level"
                label="لا يوجد"
              />
              <text-input
                  v-if="form.education_verif"
                  class="pb-8 pr-6 w-full"
                  id="education_place"
                  v-model="form.education_place"
                  :error="form.errors.education_place"
                  label="مكان الدراسة"
                />
                <text-input
                  v-else
                  disabled
                  class="pb-8 pr-6 w-full"
                  id="education_place"
                  label="لا يوجد"
                />
            </div>
            <p class="w-full text-black font-bold text-18 pb-8 pr-6">البيانات الصحة</p>
            <div class="w-full flex flex-row flex-nowrap">
              <div class="w-30">
                <ToggleCheckbox
                  :id="'health_insurance'"
                  :class="'w-full'"
                  :isChecked="form.health_insurance"
                  :label="'التغطية الصحية'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  @toggle="toggle_health_insurance"
                />
              </div>
              <div class="w-7/10 flex flex-row flex-nowrap" style='display: flex;flex-direction: column;'>
                <p class="title_session_families pb-8 pr-6 lg:w-1/2">الحالة الصحية</p>
                <div style='flex-direction: row; display: flex; border-color: rgba(226, 232, 240, var(--tw-border-opacity));    border-radius: 0.25rem;border-width: 1px;'>
                  <ToggleCheckbox
                  :id="'good'"
                  :name = "'helth_state'"
                  :class="'w-1/4'"
                  :isChecked="form.good"
                  :label="' جيدة'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  @toggle="toggle_health_Status_good"
                />
                <ToggleCheckbox
                  :id="'bad'"
                  :name = "'helth_state'"
                  :class="'w-1/4'"
                  :isChecked="form.bad"
                  :label="' عليلة'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  @toggle="toggle_health_Status_bad"
                />
                </div>
              </div>
            </div>
            <div v-if="form.bad">
              <div class="w-full flex flex-row flex-nowrap">
                <ToggleCheckbox
                  :id="'disease_verif'"
                  :isChecked="form.disease_verif"
                  :label='"مرض مزمن"'
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  @toggle="toggle_disease"
                />
                <text-input
                  v-if="form.disease_verif"
                  class="pb-8 pr-6 w-full"
                  id="disease"
                  :error="form.errors.disease"
                  v-model="form.disease"
                  label="أذكر المرض المزمن"
                />
                <text-input
                  v-else
                  disabled
                  class="pb-8 pr-6 w-full"
                  id="disease"
                  label="لا يوجد"
                />
              </div>
              <div class="w-full flex flex-row flex-nowrap">
                <ToggleCheckbox
                  :id="'disability_verif'"
                  :isChecked="form.disability_verif"
                  :label='"إعاقة"'
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  @toggle="toggle_disability"
                />
                <text-input
                  v-if="form.disability_verif"
                  class="pb-8 pr-6 w-full"
                  id="disability"
                  v-model="form.disability"
                  :error="form.errors.disability"
                  label=" أذكر الإعاقة"
                />
                <text-input
                  v-else
                  disabled
                  class="pb-8 pr-6 w-full"
                  id="disability"
                  label="لا يوجد"
                />
                <text-input
                  v-if="form.disability_verif"
                  v-model="form.disability_card_number"
                  :error="form.errors.disability_card_number"
                  class="pb-8 pr-6 w-full lg:w-1/2"
                  label="رقم بطاقة الإعاقة"
                />
              </div>
            </div>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button @click="active_step = 1" class="btn-indigo" type="button">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-4"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M8.25 4.5l7.5 7.5-7.5 7.5"
                />
              </svg>
            </button>
            <loading-button :loading="form.processing" class="btn-indigo" type="submit"
              >إنشاء الفرد</loading-button
            >
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
export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    ToggleCheckbox,
    TextareaInput,
  },
  layout: Layout,
  remember: "form",
  props: {
    Family: Object,
  },
  data() {
    return {
      active_step: 1,
      current_form_title: "",
      current_form: "childrens",
      notes_form: this.$inertia.form({
        title: null,
        value: null,
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
        monthly_income: null,
        health_insurance: false,
        kinship: null,
        caregiver: false,
        education_level: null,
        job: null,
        job_place: null,
        good: false,
        bad: false,
        disease: null,
        disability: null,
        disability_card_number: null,
      }),
    };
  },
  mounted() {
    localStorage.setItem("stored_childrens", 0);
    localStorage.setItem("stored_elderlies", 0);
    localStorage.setItem("stored_other_members", 0);
    this.prepare_childrens();
  },
  methods: {
    toggle_cin() {
      this.form.cin_verif = !this.form.cin_verif;
      this.form.cin = 0;
    },
    toggle_phone() {
      this.form.phone_verif = !this.form.phone_verif;
      this.form.phone = 0;
    },
    toggle_job() {
      this.form.job_verif = !this.form.job_verif;
      this.form.job = '';
      this.form.job_place = '';
    },
    toggle_education() {
      this.form.education_verif = !this.form.education_verif;
      this.form.education_level = '';
      this.form.education_place = '';
    },
    toggle_disease() {
      this.form.disease_verif = !this.form.disease_verif;
      this.form.disease = '';
    },
    toggle_disability() {
      this.form.disability_verif = !this.form.disability_verif;
      this.form.disability = '';
      this.form.disability_card_number = '';
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
      this.notes_form.post(`/notes/${this.Family.id}`);
    },

    back_to_family_create() {
      this.$inertia.get("/families/create");
    },
    define_facilities() {
      this.facilities_form.put(`/facilities/${this.Family.id}`);
      this.active_step = "notes";
      this.current_form_title = "أضف  ملاحظة";
    },

    save_home() {
      this.home_form.post(`/home/${this.Family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.active_step = "facilities";
          this.current_form_title = "تعديل المرافق الإساسية";
        },
      });
    },
    prepare_childrens() {
      const stored_childs = parseInt(localStorage.getItem("stored_childrens"));
      if (stored_childs < this.Family.childrens_number) {
        this.active_step = 1;
        this.current_form = "childrens";
        this.current_form_title = " أدخل بيانات الإبن رقم" + stored_childs + 1;
      } else {
        this.prepare_elderlies();
      }
    },
    prepare_elderlies() {
      const stored_elderlies = parseInt(localStorage.getItem("stored_elderlies"));
      if (stored_elderlies < this.Family.elderlies_number) {
        this.active_step = 1;
        this.current_form_title = " أدخل بيانات المسن رقم" + stored_elderlies + 1;
        this.current_form = "elderlies";
      } else {
        this.prepare_other_members();
      }
    },
    prepare_other_members() {
      const stored_other_members = parseInt(localStorage.getItem("stored_other_members"));
      if (stored_other_members < this.Family.other_members_number) {
        this.active_step = 1;
        this.current_form_title =
          " أدخل بيانات الفرد الإضافي رقم" + stored_other_members + 1;
        this.current_form = "other_members";
      } else {
        this.prepare_husband();
      }
    },
    prepare_husband() {
      if (this.Family.husband) {
        this.active_step = 1;
        this.current_form_title = "أدخل بيانات الزوج";
        this.current_form = "husband";
      } else {
        this.prepare_wife();
      }
    },
    prepare_wife() {
      if (this.Family.wife) {
        this.active_step = 1;
        this.current_form_title = "أدخل بيانات الزوجة";
        this.current_form = "wife";
      } else {
        this.members_process_done();
      }
    },
    toggle_health_insurance() {
      this.form.health_insurance = !this.form.health_insurance;
    },

    toggle_health_Status_good() {
      this.form.good = !this.form.good;
    },
    toggle_health_Status_bad() {
      this.form.bad = !this.form.bad;
    },
    store() {
      if (this.current_form == "childrens") {
        this.form.kinship = "child";
        this.store_childrens();
      }
      if (this.current_form == "elderlies") {
        this.form.kinship = "elderly";

        this.store_elderlies();
      }
      if (this.current_form == "other_members") {
        this.form.kinship = "other_member";

        this.store_other_members();
      }
      if (this.current_form == "husband") {
        this.form.kinship = "husband";

        this.store_husband();
      }
      if (this.current_form == "wife") {
        this.form.kinship = "wife";

        this.store_wife();
      }
    },
    members_process_done() {
      this.current_form_title = "أضف بيانات المسكن";
      this.active_step = "home";
      this.current_form = "";
      this.form.reset();
      localStorage.setItem("stored_childrens", 0);
      localStorage.setItem("stored_elderlies", 0);
      localStorage.setItem("stored_other_members", 0);
    },
    store_husband() {
      if (this.Family.husband) {
        this.form.post("/members", {
          preserveScroll: true,
          onSuccess: () => {
            this.form.reset();
            this.active_step = 1;
            this.prepare_wife();
          },
        });
      } else {
        this.prepare_wife();
      }
    },
    store_wife() {
      if (this.Family.wife) {
        this.form.post("/members", {
          preserveScroll: true,
          onSuccess: () => {
            this.members_process_done();
          },
        });
      } else {
        this.members_process_done();
      }
    },
    store_childrens() {
      if (this.Family.childrens_number > 0) {
        let stored_childs = parseInt(localStorage.getItem("stored_childrens"));

        if (stored_childs < this.Family.childrens_number) {
          this.form.post("/members", {
            preserveScroll: true,
            onSuccess: () => {
              const updated_s_ch = stored_childs + 1;
              localStorage.setItem("stored_childrens", updated_s_ch);
              stored_childs = parseInt(localStorage.getItem("stored_childrens"));

              this.form.reset();
              this.active_step = 1;

              if (stored_childs < this.Family.childrens_number) {
                const nextChildNumber = updated_s_ch + 1;
                this.current_form_title = " أدخل بيانات الإبن رقم " + nextChildNumber;
              } else {
                this.prepare_elderlies();
              }
            },
          });
        }
      } else {
        this.prepare_elderlies();
      }
    },

    store_elderlies() {
      if (this.Family.elderlies_number > 0) {
        let stored_elderlies = parseInt(localStorage.getItem("stored_elderlies"));

        if (stored_elderlies < this.Family.elderlies_number) {
          this.form.post("/members", {
            preserveScroll: true,
            onSuccess: () => {
              const updated_s_e = stored_elderlies + 1;
              localStorage.setItem("stored_elderlies", updated_s_e);
              stored_elderlies = parseInt(localStorage.getItem("stored_elderlies"));

              this.form.reset();
              this.active_step = 1;

              if (stored_elderlies < this.Family.elderlies_number) {
                const nextErderlyNumber = updated_s_e + 1;
                this.current_form_title = " أدخل بيانات المسن رقم " + nextErderlyNumber;
              } else {
                this.prepare_other_members();
              }
            },
          });
        }
      } else {
        this.prepare_other_members();
      }
    },
    store_other_members() {
      if (this.Family.other_members_number > 0) {
        let stored_other_members = parseInt(localStorage.getItem("stored_other_members"));

        if (stored_other_members < this.Family.other_members_number) {
          this.form.post("/members", {
            preserveScroll: true,
            onSuccess: () => {
              const updated_o_m = stored_other_members + 1;
              localStorage.setItem("stored_other_members", updated_o_m);
              stored_other_members = parseInt(
                localStorage.getItem("stored_other_members")
              );

              this.form.reset();
              this.active_step = 1;

              if (stored_other_members < this.Family.other_members_number) {
                const nextOtherMembersNumber = updated_o_m + 1;
                this.current_form_title =
                  " أدخل بيانات الفرد الإضافي رقم " + nextOtherMembersNumber;
              } else {
                this.prepare_other_members();
              }
            },
          });
        }
      } else {
        this.prepare_other_members();
      }
    },
  },
};
</script>
