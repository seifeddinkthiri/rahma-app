<template>
  <div>
    <Head title="Create Family" />
    <Breadcrumb :active_step="'family'" :form_title="'إنشاء عائلة'" />

    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <!--    <text-input
            class="pb-8 pr-6 w-full lg:w-1/2"
            id="caregiver_phone"
            v-model="form.caregiver_phone"
            :error="form.errors.caregiver_phone"
            label="رقم هاتف معيل الأسرة"
          />-->

          <ToggleCheckbox
            :id="'husband'"
            :isChecked="form.husband"
            :label="'الزوج'"
            :active_value="'نعم'"
            :inactive_value="'لا'"
            @toggle="toggle_husband"
          />
          <ToggleCheckbox
            :id="'wife'"
            :isChecked="form.wife"
            :label="'الزوجة'"
            :active_value="'نعم'"
            :inactive_value="'لا'"
            @toggle="toggle_wife"
          />
          <div>
            <ToggleCheckbox
              :id="'childrens'"
              :isChecked="form.childrens"
              :label="'الأبناء'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_childrens"
            />
            <text-input
              v-if="form.childrens"
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="childrens_number"
              v-model="form.childrens_number"
              label="عددهم"
            />
            <text-input
              v-else
              disabled
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="childrens_number"
              label="لا يوجد"
            />
          </div>

          <div>
            <ToggleCheckbox
              :id="'elderlies'"
              :isChecked="form.elderlies"
              :label="'المسنين'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_elderlies"
            />
            <text-input
              v-if="form.elderlies"
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="elderlies_number"
              v-model="form.elderlies_number"
              label="عددهم"
            />
            <text-input
              hidden
              v-else
              disabled
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="elderlies_number"
              label="لا يوجد "
            />
          </div>
          <div>
            <ToggleCheckbox
              :id="'other_members'"
              :isChecked="form.other_members"
              :label="'أفراد آخرين'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_other_members"
            />
            <text-input
              v-if="form.other_members"
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="other_members_number"
              v-model="form.other_members_number"
              label="عددهم"
            />
            <text-input
              v-else
              disabled
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="other_members_number"
              label="لا يوجد"
            />
          </div>

          <button
            class="inline-flex h-12 items-center justify-center px-4 py-2 text-white hover:text-white text-sm font-medium bg-gray-600 hover:bg-gray-800 rounded focus:outline-none"
            type="submit"
          >
            التالي</button
          ><file-input
            id="photo"
            v-model="form.photo"
            :error="form.errors.photo"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label=" الصورة (اخيتاري)  "
          />
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
import FileInput from "@/Shared/FileInput";
import ToggleCheckbox from "../../Shared/ToggleCheckbox.vue";
import Breadcrumb from "@/Shared/Breadcrumb";
import Icon from "@/Shared/Icon";

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
    ToggleCheckbox,
    Breadcrumb,
    Icon,
  },
  layout: Layout,
  remember: "form",
  mounted() {
    localStorage.setItem("stored_childrens", 0);
    localStorage.setItem("stored_elderlies", 0);
    localStorage.setItem("stored_other_members", 0);
  },
  data() {
    return {
      form: this.$inertia.form({
        photo: null,
        // caregiver_phone: null,
        wife: false,
        husband: false,
        elderlies: false,
        childrens: false,
        other_members: false,
        childrens_number: 0,
        elderlies_number: 0,
        other_members_number: 0,
      }),
    };
  },
  methods: {
    toggle_husband() {
      this.form.husband = !this.form.husband;
    },
    toggle_wife() {
      this.form.wife = !this.form.wife;
    },
    toggle_childrens() {
      this.form.childrens = !this.form.childrens;
      this.form.childrens_number = 0;
    },
    toggle_elderlies() {
      this.form.elderlies = !this.form.elderlies;
      this.form.elderlies_number = 0;
    },
    toggle_other_members() {
      this.form.other_members_number = 0;
      this.form.other_members = !this.form.other_members;
    },
    TotalMembers() {
      return (
        this.form.childrens_number +
        this.form.elderlies_number +
        this.form.other_members_number
      );
    },
    store() {
      if (
        (this.form.husband && this.form.wife) ||
        (this.form.husband && this.TotalMembers() > 0) ||
        (this.form.wife && this.TotalMembers() > 0) ||
        this.TotalMembers() >= 2
      ) {
        this.form.post("/families");
      } else {
        window.alert("تحتوي العائلة على فردين على الأقل");
      }
    },
  },
};
</script>
