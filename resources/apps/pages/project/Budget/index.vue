<template>
    <v-page-wrap crud absolute searchable with-progress>
        <template #navigate>
            <v-btn-tips @click="$router.go(-1)" label="type" icon="arrow_back" :show="true" />
        </template>

        <v-desktop-table v-if="desktop"
            single
        ></v-desktop-table>

        <v-mobile-table icon="perm_identity" v-else>
            <template v-slot:default="{ item }">
                <v-list-item-content>
                    <v-list-item-title>{{ item.name }}</v-list-item-title>
                    <v-list-item-subtitle class="f-nunito">{{ item.email }}</v-list-item-subtitle>
                </v-list-item-content>
            </template>
        </v-mobile-table>

        <v-page-form small>
            <v-row>
                <v-col cols="4">
                    <v-number-field
                        label="Tahun"
                        :color="$root.theme"
                        v-model="record.year"
                        thousands=""
                    ></v-number-field>
                </v-col>

                <v-col cols="4">
                    <v-number-field
                        label="Pagu"
                        :color="$root.theme"
                        v-model="record.maxi"
                    ></v-number-field>
                </v-col>

                <v-col cols="4">
                    <v-number-field
                        label="Pengingat"
                        :color="$root.theme"
                        v-model="record.warn"
                    ></v-number-field>
                </v-col>
            </v-row>
        </v-page-form>
    </v-page-wrap>
</template>

<script>
import { pageMixins } from '@apps/mixins/PageMixins';

export default {
    name: 'page-budget',

    mixins: [pageMixins],

    route: [
        { path: 'brand/:brand/type/:type/budget', name: 'budget', root: 'monoland' },
    ],

    data:() => ({
        // 
    }),

    created() {
        this.tableHeaders([
            { text: 'Tahun', value: 'year' },
            { text: 'Maximal', value: 'maxi', align: 'end', class: 'count-field' },
            { text: 'Pengingat', value: 'warn', align: 'end', class: 'count-field' },
            { text: 'Updated', value: 'updated_at', class: 'datetime-field' }
        ]);

        this.pageInfo({
            icon: 'people',
            title: 'Pagu Anggaran',
        });

        this.dataUrl(`/api/type/${this.$route.params.type}/budget`);

        this.setRecord({
            id: null,
            year: 0,
            maxi: 0,
            warn: 0,
        });
    }
};
</script>