<template>
    <div>
        <GuiContainer w-50 mt-30 pr-10 inline>
            <GuiValue :title="'Краткое наименование'">{{ data['short_title'] }}</GuiValue>
            <GuiValue :title="'Полное наименование'">{{ data['title'] }}</GuiValue>
            <GuiValue :title="'Адрес'">{{ data['address'] }}</GuiValue>
            <GuiValue :title="'Район'">{{ data['region'] }}</GuiValue>
            <GuiValue :title="'Статус'">
                <GuiActivityIndicator :active="data['active']"/>
                {{ data['status'] }}
            </GuiValue>
            <GuiValue :title="'Телефон'">{{ data['phone'] }}</GuiValue>
            <GuiValue :title="'Email'">
                <a class="link" v-if="data['email']" target="_blank" :href="'mailto:'+data['email']">{{ data['email'] }}</a>
            </GuiValue>
            <GuiValue :title="'Страница в сети интернет'">
                <a class="link" v-if="data['homepage']" target="_blank" :href="data['homepage']">{{ data['homepage'] }}</a>
            </GuiValue>
            <GuiValue :title="'Виды спорта'">{{ data['sport_kinds'] ? data['sport_kinds'].join(', ') : null }}</GuiValue>
        </GuiContainer>
        <GuiContainer w-50 mt-30 inline v-if="data['images'] && data['images'][0]">
            <div class="training-base-image-view">
                <img class="training-base-image-view__image" :src="data['images'][image_index]" :alt="data['name']"/>
            </div>
        </GuiContainer>
        <GuiContainer w-100 mt-30 v-if="data['images'] && data['images'].length > 1">
            <img class="training-base-image" v-for="(image, key) in data['images']"
                 :class="{'training-base-image__selected': key === image_index}"
                 :src="image"
                 :alt="data['name']"
                 @click="selectImage(key)"
            />
        </GuiContainer>
        <GuiContainer mt-30>
            <GuiValueArea :title="'Описание'" :text-content="data['description']"/>
        </GuiContainer>
    </div>
</template>

<script>
import GuiContainer from "@/Components/GUI/GuiContainer";
import GuiValue from "@/Components/GUI/GuiValue";
import GuiValueArea from "@/Components/GUI/GuiValueArea";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";

export default {
    props: {
        data: {type: Object, required: true},
    },

    components: {
        GuiActivityIndicator,
        GuiValueArea,
        GuiValue,
        GuiContainer
    },

    data: () => ({
        image_index: 0,
    }),

    methods: {
        selectImage(key) {
            this.image_index = key;
        },
    }
}
</script>

<style lang="scss" scoped>
.training-base-image-view {
    height: 370px;
    display: flex;
    justify-content: center;
    align-items: center;

    &__image {
        max-width: 100%;
        max-height: 100%;
    }
}

.training-base-image {
    cursor: pointer;
    height: 150px;
    margin: 0 10px 10px 0;
    display: inline-block;
    vertical-align: top;
    box-sizing: border-box;
    border: 4px solid transparent;
    border-radius: 3px;

    &__selected {
        border-color: #0B68C2;
    }
}
</style>
