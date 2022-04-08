<template>
    <LayoutPage :title="$route.meta['title']" :loading="list.is_loading">
        <template #actions>
            <GuiActionsMenu>
                <router-link class="link" :to="{ name: 'roles-edit', params: { id: 0 }}">Добавить роль</router-link>
            </GuiActionsMenu>
        </template>
        <LayoutFilters>
            <LayoutFiltersItem :title="'Статус роли'">
                <InputDropDown
                    :options="[{value: true, name: 'Активная'}, {value: false, name: 'Неактивная'}]"
                    :identifier="'value'"
                    :show="'name'"
                    v-model="list.filters['active']"
                    :original="list.filters_original['active']"
                    :placeholder="'Все'"
                    :has-null="true"
                    :small="true"
                    @change="list.load()"
                />
            </LayoutFiltersItem>
        </LayoutFilters>

        <ListTable v-if="list.list && list.list.length > 0" :titles="list.titles">
            <ListTableRow v-for="role in list.list">
                <ListTableCell>
                    <template v-if="!role['locked']">
                        <GuiActivityIndicator :active="role['active']"/>
                        <router-link class="link" :to="{ name: 'roles-edit', params: { id: role['id'] }}">{{ role['name'] }}</router-link>
                    </template>
                    <template v-else>
                        <GuiActivityIndicator :active="role['active']"/>
                        {{ role['name'] }}
                        <span class="inline-flex text-gray w-10px" title="Системная роль"><IconLock/></span>
                    </template>
                </ListTableCell>
                <ListTableCell>
                    {{ role['description'] }}
                </ListTableCell>
            </ListTableRow>
        </ListTable>

        <GuiMessage border v-else-if="list.is_loaded">Ничего не найдено</GuiMessage>

        <Pagination :pagination="list.pagination" @pagination="(page, per_page) => list.load(page, per_page)"/>
    </LayoutPage>
</template>

<script>
import list from "@/Core/List";
import LayoutPage from "@/Components/Layout/LayoutPage";
import GuiActionsMenu from "@/Components/GUI/GuiActionsMenu";
import LayoutFilters from "@/Components/Layout/LayoutFilters";
import LayoutFiltersItem from "@/Components/Layout/LayoutFiltersItem";
import ListTable from "@/Components/ListTable/ListTable";
import ListTableRow from "@/Components/ListTable/ListTableRow";
import ListTableCell from "@/Components/ListTable/ListTableCell";
import GuiActivityIndicator from "@/Components/GUI/GuiActivityIndicator";
import GuiMessage from "@/Components/GUI/GuiMessage";
import Pagination from "@/Components/Pagination";
import InputDropDown from "@/Components/Inputs/InputDropDown";
import IconLock from "@/Components/Icons/IconLock";

export default {
    components: {
        IconLock,
        InputDropDown,
        LayoutPage,
        GuiActionsMenu,
        LayoutFilters,
        LayoutFiltersItem,
        ListTable,
        ListTableRow,
        ListTableCell,
        GuiActivityIndicator,
        GuiMessage,
        Pagination,
    },

    data: () => ({
        list: list('/api/roles'),
    }),

    created() {
        this.list.initial();
    },
}
</script>
