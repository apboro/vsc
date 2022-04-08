<template>
    <div>
        <GuiContainer w-50 mt-30>
            <GuiValue :title="'ФИО'">{{ data['full_name'] }}</GuiValue>
            <GuiValue :title="'Дата добавления'">{{ data['created_at'] }}</GuiValue>
            <GuiValue :title="'Должность'">{{ data['position_title'] }}</GuiValue>
            <GuiValue :title="'Статус трудоустройства'">
                <GuiActivityIndicator :active="data.active"/>{{ data['status'] }}
            </GuiValue>
        </GuiContainer>

        <GuiContainer w-50 mt-30 inline>
            <GuiValue :title="'Пол'">{{ data['gender'] }}</GuiValue>
            <GuiValue :title="'Дата рождения'">{{ data['birth_date'] }}</GuiValue>
            <GuiValue :title="'Email'">
                <a class="link" v-if="data.email" target="_blank" :href="'mailto:'+data.email">{{ data['email'] }}</a>
            </GuiValue>
            <GuiValue :title="'Рабочий телефон'">{{ data['work_phone'] }}
                <span v-if="data['work_phone_additional']"> доб. {{ data['work_phone_additional'] }}</span>
            </GuiValue>
            <GuiValue :title="'Мобильный телефон'">{{ data['mobile_phone'] }}</GuiValue>
        </GuiContainer>

        <GuiContainer w-100 mt-30>
            <GuiValueArea :title="'Заметки'" v-text="data['notes']"/>
        </GuiContainer>

        <FormPopUp :title="'Статус сотрудника'"
                   :form="form"
                   :options="{id: staffId}"
                   ref="popup"
        >
            <GuiContainer w-350px>
                <FormDictionary :form="form" :name="'value'" :dictionary="'position_statuses'" :hide-title="true"/>
            </GuiContainer>
        </FormPopUp>
    </div>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiValue from "@/Components/GUI/GuiValue";
import GuiValueArea from "@/Components/GUI/GuiValueArea";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";
import GuiButton from "@/Components/GUI/GuiButton";
import FormPopUp from "@/Components/FormPopUp";
import FormDictionary from "@/Components/Form/FormDictionary";
import form from "@/Core/Form";

export default {
    props: {
        staffId: {type: Number, required: true},
        data: {type: Object, required: true},
    },

    emits: ['update'],

    components: {
        FormDictionary,
        FormPopUp,
        GuiButton,
        GuiActivityIndicator,
        GuiValueArea,
        GuiValue,
        GuiContainer
    },

    data: () => ({
        form: form(null, '/api/staff/properties'),
    }),

    methods: {
        statusChange() {
            this.form.reset();
            this.form.set('name', 'status_id');
            this.form.set('value', this.data['status_id'], 'required', 'Статус сотрудника', true);
            this.form.toaster = this.$toast;
            this.form.load();
            this.$refs.popup.show()
                .then(response => {
                    this.$emit('update', response.payload);
                });
        },
    }
}
</script>
