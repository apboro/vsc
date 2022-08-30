<template>
    <LayoutPage :loading="processing" :title="form.payload['title']"
                :breadcrumbs="[{caption: 'Объекты', to: {name: 'training-base-list'}}]"
                :link="{name: 'training-base-list'}"
                :link-title="'К списку объектов'"
    >
        <GuiContainer mt-30>
            <FormString :form="form" :name="'title'"/>
            <FormString :form="form" :name="'short_title'"/>
            <FormDictionary :form="form" :name="'status_id'" :dictionary="'training_base_statuses'"/>

            <FormDictionary :form="form" :name="'sport_kinds'" :dictionary="'sport_kinds'" :multi="true" :placeholder="'Выберите виды спорта'"/>
            <FormImages :form="form" :name="'images'"/>

            <FormString :form="form" :name="'address'"/>
            <FormDictionary :form="form" :name="'region_id'" :dictionary="'regions'"/>
            <FormString :form="form" :name="'email'"/>
            <FormPhone :form="form" :name="'phone'"/>
            <FormString :form="form" :name="'homepage'"/>

            <FormText :form="form" :name="'description'"/>

            <GuiContainer mt-30>
                <GuiButton @click="save" :color="'blue'">Сохранить</GuiButton>
                <GuiButton @click="cancel">Отмена</GuiButton>
            </GuiContainer>
        </GuiContainer>
    </LayoutPage>
</template>

<script>

import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiContainer from "@/Components/GUI/GuiContainer";
import form from "@/Core/Form";
import FormString from "@/Components/Form/FormString";
import FormDictionary from "@/Components/Form/FormDictionary";
import FormPhone from "@/Components/Form/FormPhone";
import FormText from "@/Components/Form/FormText";
import GuiButton from "@/Components/GUI/GuiButton";
import FormImages from "@/Components/Form/FormImages";

export default {
    components: {
        FormImages,
        GuiButton,
        FormText,
        FormPhone,
        FormDictionary,
        FormString,
        GuiContainer,
        LayoutPage

    },

    data: () => ({
        form: form('/api/training-base/get', '/api/training-base/update'),
    }),

    computed: {
        baseId() {
            return Number(this.$route.params.id);
        },
        processing() {
            return this.form.is_loading || this.form.is_saving;
        },
    },

    created() {
        this.form.toaster = this.$toast;
        this.form.load({id: this.baseId});
    },

    methods: {
        save() {
            if (!this.form.validate()) {
                return;
            }
            this.form.save({id: this.baseId})
                .then(response => {
                    if (this.baseId === 0) {
                        this.$router.push({name: 'training-base-view', params: {id: response.payload['id']}});
                    } else {
                        this.$router.push({name: 'training-base-view', params: {id: this.baseId}});
                    }
                })
        },
        cancel() {
            if (this.baseId === 0) {
                this.$router.push({name: 'training-base-list'});
            } else {
                this.$router.push({name: 'training-base-view', params: {id: this.baseId}});
            }
        },
    }
}
</script>
