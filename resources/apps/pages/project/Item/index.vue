<template>
    <v-page-wrap crud absolute searchable with-progress>
        <template #navigate>
            <v-btn-tips @click="$router.go(-1)" label="segment" icon="arrow_back" :show="true" />
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
                <v-col cols="6">
                    <v-text-field
                        label="Nama"
                        :color="$root.theme"
                        v-model="record.name"
                    ></v-text-field>
                </v-col>

                <v-col cols="1">
                    <v-text-field
                        label="Unit"
                        :color="$root.theme"
                        v-model="record.unit"
                    ></v-text-field>
                </v-col>

                <v-col cols="3">
                    <v-select
                        label="Jenis"
                        :items="kinds"
                        :color="$root.theme"
                        v-model="record.kind"
                    ></v-select>
                </v-col>

                <v-col cols="2">
                    <v-text-field
                        label="Batas"
                        :color="$root.theme"
                        v-model="record.maxi"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-page-form>
    </v-page-wrap>
</template>

<script>
import { pageMixins } from '@apps/mixins/PageMixins';

export default {
    name: 'page-item',

    mixins: [pageMixins],

    route: [
        { path: 'segment/:segment/item', name: 'item', root: 'monoland' },
    ],

    data:() => ({
        kinds: [
            { text: 'Motor', value: 'motor' },
            { text: 'Mobil', value: 'mobil' }
        ]
    }),

    created() {
        this.tableHeaders([
            { text: 'Name', value: 'name' },
            { text: 'Jenis', value: 'kind', align: 'end', class: 'count-field' },
            { text: 'Batas', value: 'maxi', align: 'end', class: 'count-field' },
            { text: 'Updated', value: 'updated_at', class: 'datetime-field' }
        ]);

        this.pageInfo({
            icon: 'people',
            title: 'Item Service',
        });

        this.dataUrl(`/api/segment/${this.$route.params.segment}/item`);

        this.setRecord({
            id: null,
            name: null,
            unit: null,
            maxi: null,
            kind: null
        });
    }
};
</script>