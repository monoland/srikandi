<template>
    <v-page-wrap crud absolute searchable with-progress>
        <template #navigate>
            <v-btn-tips @click="$router.go(-1)" label="brand" icon="arrow_back" :show="true" />
        </template>
        
        <template #toolbar-default>
            <v-btn-tips @click="openBudget" label="PAGU-ANGGARAN" icon="beenhere" :show="!disabled.link" />
            <v-btn-tips @click="openVehicle" label="DAFTAR-KENDARAAN" icon="directions_bus" :show="!disabled.link" />
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
                <v-col cols="12">
                    <v-text-field
                        label="Nama"
                        hide-details
                        :color="$root.theme"
                        v-model="record.name"
                    ></v-text-field>
                </v-col>

                <v-col cols="12">
                    <v-select
                        label="Jenis"
                        :items="kinds"
                        :color="$root.theme"
                        v-model="record.kind"
                    ></v-select>
                </v-col>
            </v-row>
        </v-page-form>
    </v-page-wrap>
</template>

<script>
import { pageMixins } from '@apps/mixins/PageMixins';

export default {
    name: 'page-type',

    mixins: [pageMixins],

    route: [
        { path: 'brand/:brand/type', name: 'type', root: 'monoland' },
    ],

    data:() => ({
        kinds: ['motor', 'mobil']
    }),

    created() {
        this.tableHeaders([
            { text: 'Name', value: 'name' },
            { text: 'Pagu', value: 'budgets_count', align: 'end', class: 'count-field' },
            { text: 'Kendaraan', value: 'vehicles_count', align: 'end', class: 'count-field' },
            { text: 'Updated', value: 'updated_at', class: 'datetime-field' }
        ]);

        this.pageInfo({
            icon: 'people',
            title: 'Type Kendaraan',
        });

        this.dataUrl(`/api/brand/${this.$route.params.brand}/type`);

        this.setRecord({
            id: null,
            name: null,
            kind: null
        });
    },

    methods: {
        openBudget: function() {
            this.$router.push({ name: 'budget', params: { type: this.record.id } });
        },

        openVehicle: function() {
            this.$router.push({ name: 'vehicle', query: { type: this.record.id } });
        }, 
    }
};
</script>