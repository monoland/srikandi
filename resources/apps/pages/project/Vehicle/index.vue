<template>
    <v-page-wrap crud absolute searchable with-progress>
        <template #navigate v-if="$route.query.type">
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
            <div class="d-block overline">informasi kendaraan</div>
            <v-row>
                <v-col cols="4">
                    <v-text-field
                        label="Nomor Rangka"
                        :color="$root.theme"
                        v-model="record.frame_numb"
                    ></v-text-field>
                </v-col>

                <v-col cols="4">
                    <v-text-field
                        label="Nomor Mesin"
                        :color="$root.theme"
                        v-model="record.machine_numb"
                    ></v-text-field>
                </v-col>

                <v-col cols="4">
                    <v-select
                        label="Tahun Perolehan"
                        :items="budgets"
                        :color="$root.theme"
                        v-model="record.year"
                    ></v-select>
                </v-col>

                <v-col cols="4">
                    <v-text-field
                        label="Reff Barang"
                        :color="$root.theme"
                        v-model="record.refs_numb"
                    ></v-text-field>
                </v-col>

                <v-col cols="4">
                    <v-select
                        label="Nama Biro"
                        :items="agencies"
                        :color="$root.theme"
                        v-model="record.agency_id"
                    ></v-select>
                </v-col>

                <v-col cols="4">
                    <v-select
                        label="Kondisi"
                        :items="conditions"
                        :color="$root.theme"
                        v-model="record.condition"
                    ></v-select>
                </v-col>
            </v-row>

            <div class="d-block overline">informasi stnk</div>
            <v-row>
                <v-col cols="4">
                    <v-text-field
                        label="No. Polisi"
                        :color="$root.theme"
                        v-model="record.police_id"
                    ></v-text-field>
                </v-col>

                <v-col cols="8">
                    <v-text-field
                        label="Nama Pemegang"
                        :color="$root.theme"
                        v-model="record.name"
                    ></v-text-field>
                </v-col>

                <v-col cols="4">
                    <v-text-field
                        label="Warna Kendaraan"
                        :color="$root.theme"
                        v-model="record.color"
                    ></v-text-field>
                </v-col>

                <v-col cols="4">
                    <v-date-menu
                        label="Masa Pajak"
                        :color="$root.theme"
                        v-model="record.taxdue"
                    ></v-date-menu>
                </v-col>

                <v-col cols="4">
                    <v-number-field
                        label="Nilai Pajak"
                        :color="$root.theme"
                        v-model="record.taxsum"
                    ></v-number-field>
                </v-col>
            </v-row>
        </v-page-form>
    </v-page-wrap>
</template>

<script>
import { pageMixins } from '@apps/mixins/PageMixins';

export default {
    name: 'page-vehicle',

    mixins: [pageMixins],

    route: [
        { path: 'vehicle', name: 'vehicle', root: 'monoland' },
    ],

    computed: {
        agencies: function() {
            if (this.combos && this.combos.hasOwnProperty('agencies')) {
                return this.combos.agencies;
            }
            
            return [];
        },

        budgets: function() {
            if (this.combos && this.combos.hasOwnProperty('budgets')) {
                return this.combos.budgets;
            }
            
            return [];
        }
    },

    data:() => ({
        conditions: [
            { text: 'Baik', value: 'B' },
            { text: 'Kurang Baik', value: 'KB' },
            { text: 'Rusak Berat', value: 'RB' }
        ],
    }),

    created() {
        this.tableHeaders([
            { text: 'No. Polisi', value: 'police_id', class: 'police-field' },
            { text: 'Nama', value: 'name' },
            { text: 'No. Rangka', value: 'frame_numb' },
            { text: 'No. Mesin', value: 'machine_numb' },
            { text: 'Tahun', value: 'year', align: 'end', class: 'medium-field' },
            { text: 'Kondisi', value: 'condition', align: 'center', class: 'medium-field' },
            { text: 'Updated', value: 'updated_at', class: 'datetime-field' }
        ]);

        this.pageInfo({
            icon: 'people',
            title: 'Kendaraan',
        });

        if (this.$route.query.type) {
            this.dataUrl(`/api/type/${this.$route.query.type}/vehicle`);
            this.setCancelNewForm(() => { return false; });
        } else {
            this.dataUrl(`/api/vehicle`);
            this.setCancelNewForm(() => { return true; });
        }

        this.setRecord({
            id: null,
            name: null,
            police_id: null,
            color: null,
            taxdue: null,
            taxsum: 0,
            frame_numb: null,
            machine_numb: null,
            year: null,
            refs_numb: null,
            agency_id: null,
            condition: null
        });
    },

    watch: {
        '$route.query.type': {
            handler: function(type) {
                if (!type) {
                    this.dataUrl(`/api/vehicle`);
                    this.setCancelNewForm(() => { return true; });
                }
            },

            deep: true
        }
    }
};
</script>