<template>
    <div>
        <Head title="Create Organization" />
        <Breadcrumb_individuals
            :Individual_id="Individual.id"
            :current_form_title="current_form_title"
            :active_step="active_step"
            :members_form_title="members_form_title"
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
            class="bg-white rounded-md shadow overflow-hidden" v-if="active_step == 'facilities'">
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
                @click="back_to_Individual_create"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                >
                    إنشاء منتفع جديد 
                </button>
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
    import Breadcrumb_individuals from "@/Shared/Breadcrumb_individuals";
    export default {
        components: {
        Head,
        Link,
        LoadingButton,
        SelectInput,
        TextInput,
        ToggleCheckbox,
        TextareaInput,
        Breadcrumb_individuals
        },
        layout: Layout,
        remember: "form",
        props: {
            Individual: Object,
        },
        data() {
        return {
            isFormDisabled: false,
    
            active_step: 'home',
            current_form_title: "",
            members_form_title: "",
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
            Individual_id: this.Individual.id,
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
            good: true,
            disease: null,
            disability: null,
            disability_card_number: null,
            }),
        };
        },
        methods: {
        updateActiveStep(step) {
            this.active_step = step; // Update the active_step prop in the parent component
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
            this.Individual.facilities.forEach((element) => {
            this.facilities_form.Sanitation = element.Sanitation;
            this.facilities_form.ventilation = element.ventilation;
            this.facilities_form.water = element.water;
            this.facilities_form.electricity = element.electricity;
            });
        },
            
        back_to_Individual_create() {
            this.$inertia.get("/individuals/create");
        },
        save_home() {
            this.home_form.put(`/home/${this.Individual.id}/IndividualsStore`, {
            preserveScroll: true,
            onSuccess: () => {
                this.active_step = "facilities";
                this.current_form_title = "تعديل المرافق الإساسية";
            },
            });
        },
        define_facilities() {
            this.facilities_form.put(`/facilities/${this.Individual.id}/Individualsupdate`, {
            preserveScroll: true,
            onSuccess: () => {
                this.active_step = "notes";
                this.current_form_title = "أضف  ملاحظة";
            },
            });
        },
        save_note() {
            this.notes_form.put(`/notes/${this.Individual.id}/IndividualStoreNote`, {
            preserveScroll: true,
            onSuccess: () => {
                this.active_step = "notes";
                this.current_form_title = "تم إضافة الملاحظة";
            },
            });
        },
    

        },
    };
</script>
