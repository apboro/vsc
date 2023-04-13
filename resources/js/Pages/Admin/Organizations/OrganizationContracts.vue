<template>
    <LoadingProgress :loading="is_processing">

        <GuiContainer v-if="data.data.patterns !== null" w-50 mt-30>
            <template v-for="patterns in data.data.patterns">
                <FieldCheckBox v-model="data.data.patternIDs" :label="patterns.name" :value="patterns.id" :hide-title="true" :name="String(patterns.id)"/>
            </template>
        </GuiContainer>

        <GuiContainer mt-30>
            <GuiButton @click="save" :color="'blue'">Сохранить</GuiButton>
        </GuiContainer>

    </LoadingProgress>
</template>

<script>
import LayoutPage from "@/Components/Layout/LayoutPage.vue";
import data from "@/Core/Data";
import GuiContainer from "@/Components/GUI/GuiContainer.vue";
import FieldCheckBox from "@/Components/Fields/FieldCheckBox.vue";
import GuiButton from "@/Components/GUI/GuiButton.vue";
import LoadingProgress from "@/Components/LoadingProgress";

export default {
    components: {
        LoadingProgress,
        GuiButton,
        FieldCheckBox,
        GuiContainer,
        LayoutPage,
    },

    props: {
        organizationId: {type: Number, default: null},
    },

    data: () => ({
        data: data('/api/contracts'),
        is_processing: false,
    }),


    created() {
        this.data.load({
            organization_id: this.organizationId
        })
            .catch(response => response.code === 404 && this.$router.replace({name: '404'}));
    },

    methods: {
        save() {
            this.is_processing = true;

            axios.post('/api/contracts/update', {
                patternIDs: this.data.data.patternIDs,
                organization_id: this.organizationId
            })
                .then(response => {
                    this.$toast.success(response.data['message'], 5000);
                })
                .catch(error => {
                    this.$toast.error(error.response.data['message'], 5000);
                })
                .finally(() => {
                    this.is_processing = false;
                });
        },

    }
}
</script>
