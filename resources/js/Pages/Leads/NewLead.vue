<template>
    <div>
        <template v-if="!message">
            <GuiHeading>Записаться на услугу</GuiHeading>
            <GuiContainer>
                <FormString :form="form" :name="'lastname'"/>
                <FormString :form="form" :name="'firstname'"/>
                <FormString :form="form" :name="'patronymic'"/>
                <FormPhone :form="form" :name="'phone'"/>
                <FormString :form="form" :name="'email'"/>
                <FormDropdown :form="form" :name="'service_id'" :options="services" :identifier="'id'" :show="'title'"/>
            </GuiContainer>
            <GuiContainer>
                <GuiButton :color="'blue'" @clicked="sendLead">Отправить заявку</GuiButton>
            </GuiContainer>
        </template>
        <GuiMessage v-else>{{ message }}</GuiMessage>
    </div>
</template>

<script>
import form from "@/Core/Form";
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiHeading from "@/Components/GUI/GuiHeading";
import FormString from "@/Components/Form/FormString";
import FormPhone from "@/Components/Form/FormPhone";
import GuiButton from "@/Components/GUI/GuiButton";
import FormDropdown from "@/Components/Form/FormDropdown";
import GuiMessage from "@/Components/GUI/GuiMessage";

export default {
    components: {GuiMessage, FormDropdown, GuiButton, GuiContainer, GuiHeading, FormString, FormPhone},

    props: {
        crm_url: {type: String, default: null},
        debug: {type: Boolean, default: false},
        session: {type: String, default: null},
        services: {type: Array, default: () => ([])},
    },

    data: () => ({
        form: form(null, null),
        message: null,
    }),

    created() {
        this.form.save_url = this.url('/leads/send');
        this.form.set('lastname', null, 'required', 'Фамилия', true);
        this.form.set('firstname', null, 'required', 'Имя', true);
        this.form.set('patronymic', null, 'required', 'Отчество', true);
        this.form.set('phone', null, 'required', 'Телефон', true);
        this.form.set('email', null, 'required|email|bail', 'Email', true);
        this.form.set('service_id', null, 'required', 'Услуга', true);
        this.form.load();
    },

    methods: {
        url(path) {
            return this.crm_url + path + (this.debug ? '?XDEBUG_SESSION_START=PHPSTORM' : '');
        },

        sendLead() {
            if (!this.form.validate()) {
                return;
            }

            // override form saving to send headers
            this.form.is_saving = true;
            axios.post(this.form.save_url, {
                data: this.form.values,
            }, {headers: {'X-Vsc-Session': this.session}})
                .then(response => {
                    // show message
                    this.message = response.data['message'];
                })
                .catch(error => {
                    this.message = error.response.data['message'];
                })
                .finally(() => {
                    this.form.is_saving = false;
                })
        }
    }
}
</script>
