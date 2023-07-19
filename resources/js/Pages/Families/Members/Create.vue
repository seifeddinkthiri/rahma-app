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
            <text-input
              v-model="form.cin"
              :error="form.errors.cin"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="بطاقة التعريف الوطنية"
            />
            <text-input
              v-model="form.phone"
              :error="form.errors.phone"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الهاتف"
            />
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
              <option value="Medenine">Medenine</option>
              <option value="Beja">Beja</option>
              <option value="Tunis">Tunis</option>
              <option value="Sfax">Sfax</option>
              <option value="Sousse">Sousse</option>
              <option value="Ariana">Ariana</option>
              <option value="Ben Arous">Ben Arous</option>
              <option value="Kasserine">Kasserine</option>
              <option value="Le Kef">Le Kef</option>
              <option value="Mahdia">Mahdia</option>
              <option value="Manouba">Manouba</option>
              <option value="Monastir">Monastir</option>
              <option value="Nabeul">Nabeul</option>
              <option value="Sidi Bouzid">Sidi Bouzid</option>
              <option value="Siliana">Siliana</option>
              <option value="Gabes">Gabes</option>
              <option value="Jendouba">Jendouba</option>
              <option value="Tataouine">Tataouine</option>
              <option value="Tozeur">Tozeur</option>
              <option value="Zaghouan">Zaghouan</option>
            </select-input>
            <text-input
              v-model="form.job_place"
              :error="form.errors.job_place"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مكان العمل "
            />
            <text-input
              v-model="form.job"
              :error="form.errors.job"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العمل"
            />
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
              <option value="single">أعزب</option>
              <option value="married">متزوج</option>
              <option value="divorced">مطلق</option>
            </select-input>
            <text-input
              v-model="form.monthly_income"
              :error="form.errors.monthly_income"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الدخل الشهري"
            />

            <text-input
              v-model="form.education_level"
              :error="form.errors.education_level"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="المستوى الدراسي"
            />
            <ToggleCheckbox
              :id="'good'"
              :isChecked="form.good"
              :label="'الحالة الصحية'"
              :active_value="'جيدة'"
              :inactive_value="'عليلة'"
              @toggle="toggle_health_Status"
            />
            <ToggleCheckbox
              :id="'health_insurance'"
              :isChecked="form.health_insurance"
              :label="'التغطية الصحية'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_health_insurance"
            />

            <text-input
              v-model="form.disease"
              :error="form.errors.disease"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مرض مزمن"
            />
            <text-input
              v-model="form.disability"
              :error="form.errors.disability"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="إعاقة"
            />
            <text-input
              v-if="form.disability !== null"
              v-model="form.disability_card_number"
              :error="form.errors.disability_card_number"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="رقم بطاقة الإعاقة"
            />
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

    toggle_health_Status() {
      this.form.good = !this.form.good;
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
