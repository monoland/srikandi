<template>
    <v-page-wrap :enable-delete="enableDelete" :enable-edit="enableEdit" crud absolute searchable with-progress>
        <template #toolbar-default>
            <template v-if="auth.authent === 'operator'">
                <v-btn-tips @click="openDraft" label="KIRIM" icon="send" :show="!disabled.link" v-if="record.status === 'drafted'" />
                <v-btn-tips @click="openPrint" label="CETAK" icon="print" :show="!disabled.link" v-if="record.status === 'approved' || record.status === 'printed'" />
            </template>

            <template v-else-if="auth.authent === 'kabiro'">
                <v-btn-tips @click="openDisposition" label="DISPOSISI" icon="assignment_turned_in" :show="!disabled.link" />
            </template>

            <template v-else-if="auth.authent === 'pptk'">
                <v-btn-tips @click="openSubmission" label="EXAMINE" icon="assignment" :show="!disabled.link" />
            </template>

            <template v-else-if="auth.authent === 'kpa'">
                <v-btn-tips @click="openExamine" label="APPROVAL" icon="check_circle" :show="!disabled.link" />
            </template>

            <template v-else-if="auth.authent === 'tata-usaha'">
                <v-btn-tips @click="openInvoice" label="TAGIHAN" icon="local_atm" :show="!disabled.link" />
            </template>

            <template v-else>
                <v-btn-tips @click="openDraft" label="KIRIM" icon="send" :show="!disabled.link" />
                <v-btn-tips @click="openDisposition" label="DISPOSISI" icon="assignment_turned_in" :show="!disabled.link" />
                <v-btn-tips @click="openSubmission" label="EXAMINE" icon="assignment" :show="!disabled.link" />
                <v-btn-tips @click="openExamine" label="APPROVAL" icon="check_circle" :show="!disabled.link" />
                <v-btn-tips @click="openPrint" label="CETAK" icon="print" :show="!disabled.link" />
                <v-btn-tips @click="openInvoice" label="TAGIHAN" icon="local_atm" :show="!disabled.link" />
            </template>
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

        <!-- draft form -->
        <v-page-form small>
            <v-row>
                <v-col md="10" sm="12">
                    <v-combobox
                        label="No. Polisi"
                        :items="vehicles"
                        :color="$root.theme"
                        :readonly="mode === 'edit'"
                        v-model="record.vehicle"
                    ></v-combobox>
                </v-col>

                <v-col md="2" sm="12">
                    <v-date-menu
                        type="month"
                        label="Periode"
                        :color="$root.theme"
                        :readonly="mode === 'edit'"
                        v-model="record.periode"
                    ></v-date-menu>
                </v-col>
            </v-row>

            <div class="d-block overline">item yang di service</div>

            <v-row>
                <v-col class="pt-0" cols="12">
                    <v-combobox
                        label="Item Service"
                        append-outer-icon="add"
                        :items="items"
                        v-model="currentItem"
                        @click:append-outer="addItem"
                        hide-details
                    ></v-combobox>
                </v-col>

                <v-col cols="12">
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th class="short-field"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <template v-if="hasDetails">
                                    <tr v-for="(item, index) in record.details" :key="index">
                                        <td>{{ item.text }}</td>
                                        <td class="text-end">
                                            <v-icon @click="removeItem(index)">remove_circle_outline</v-icon>
                                        </td>
                                    </tr>
                                </template>

                                <tr v-else>
                                    <td class="text-center overline grey--text" colspan="2">Tidak ada item untuk di tampilkan</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>
        </v-page-form>

        <!-- confirm send draft -->
        <v-page-confirm
            v-model="dialogDraft"
            title="Kirim Draft Pengajuan?"
            subtitle="komfirmasi pengiriman draft"
            @cancel="cancelDraft"
            @submit="postDraft"
        >
            <div class="d-block mt-4">
                Dengan mengirim draft ini berarti Anda sebagai "operator" telah memeriksa dan memastikan isi pengajuan.
            </div>
        </v-page-confirm>

        <!-- disposition dialog -->
        <v-page-dialog small
            submit-name="disposition"
            v-model="dialogDisposition"
            @cancel="cancelDisposition"
            @disposition="postDisposition"
        >
            <v-row>
                <v-col md="10" sm="12">
                    <v-combobox
                        label="No. Polisi"
                        :items="vehicles"
                        :color="$root.theme"
                        readonly
                        v-model="record.vehicle"
                    ></v-combobox>
                </v-col>

                <v-col md="2" sm="12">
                    <v-date-menu
                        type="month"
                        label="Periode"
                        :color="$root.theme"
                        readonly
                        v-model="record.periode"
                    ></v-date-menu>
                </v-col>
            </v-row>

            <div class="d-block overline">item yang di service</div>

            <v-row>
                <v-col cols="12">
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th class="short-field"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <template v-if="hasDetails">
                                    <tr v-for="(item, index) in record.details" :key="index">
                                        <td>{{ item.text }}</td>
                                        <td class="text-end"></td>
                                    </tr>
                                </template>

                                <tr v-else>
                                    <td class="text-center overline grey--text" colspan="2">Tidak ada item untuk di tampilkan</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>
        </v-page-dialog>

        <!-- submission form -->
        <v-page-dialog small
            submit-name="submit"
            v-model="dialogSubmission"
            @cancel="cancelSubmission"
            @submit="postSubmission"
        >
            <v-row>
                <v-col md="10" sm="12">
                    <v-combobox
                        label="No. Polisi"
                        :items="vehicles"
                        :color="$root.theme"
                        v-model="record.vehicle"
                        readonly
                        hide-details
                    ></v-combobox>
                </v-col>

                <v-col md="2" sm="12">
                    <v-date-menu
                        type="month"
                        label="Periode"
                        :color="$root.theme"
                        v-model="record.periode"
                        readonly
                        hide-details
                    ></v-date-menu>
                </v-col>

                <v-col cols="12">
                    <v-combobox
                        label="Pilih Bengkel"
                        :items="garages"
                        :color="$root.theme"
                        v-model="record.garage"
                    ></v-combobox>
                </v-col>
            </v-row>

            <div class="d-block overline">item pengajuan service:</div>

            <v-row>
                <v-col class="pt-0" cols="12">
                    <v-combobox
                        label="Item Service"
                        append-outer-icon="add"
                        :items="items"
                        v-model="currentItem"
                        @click:append-outer="addItem"
                        hide-details
                    ></v-combobox>
                </v-col>

                <v-col cols="12">
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th class="count-field">Terpakai</th>
                                    <th class="count-field">Sisa</th>
                                    <th class="icon-field"></th>
                                    <th class="icon-field"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <template v-if="hasDetails">
                                    <tr v-for="(item, index) in record.details" :key="index">
                                        <td>{{ item.text }}</td>
                                        <td>{{ item.used }}</td>
                                        <td>{{ item.blnc }}</td>
                                        <td><v-icon v-if="item.exmn">{{ item.aprv ? 'check' : 'close' }}</v-icon></td>
                                        <td class="text-end">
                                            <v-icon @click="removeItem(index)">remove_circle_outline</v-icon>
                                        </td>
                                    </tr>
                                </template>

                                <tr v-else>
                                    <td class="text-center overline grey--text" colspan="2">Tidak ada item untuk di tampilkan</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>

            <div class="d-block overline mt-4">catatan pemeriksaan:</div>

            <v-row>
                <v-col class="pt-0" cols="12">
                    <v-textarea
                        v-model="record.notes"
                        rows="3"
                    ></v-textarea>
                </v-col>
            </v-row>
        </v-page-dialog>

        <!-- examine form -->
        <v-page-dialog small
            submit-name="approve"
            v-model="dialogExamine"
            @cancel="cancelExamine"
            @approve="postExamine"
        >
            <v-row>
                <v-col md="10" sm="12">
                    <v-combobox
                        label="No. Polisi"
                        :items="vehicles"
                        :color="$root.theme"
                        v-model="record.vehicle"
                        readonly
                    ></v-combobox>
                </v-col>

                <v-col md="2" sm="12">
                    <v-date-menu
                        type="month"
                        label="Periode"
                        :color="$root.theme"
                        v-model="record.periode"
                        readonly
                    ></v-date-menu>
                </v-col>

                <v-col cols="12">
                    <v-combobox
                        label="Pilih Bengkel"
                        :items="garages"
                        :color="$root.theme"
                        v-model="record.garage"
                        readonly
                    ></v-combobox>
                </v-col>
            </v-row>

            <div class="d-block overline">item pengajuan service:</div>

            <v-row>
                <v-col cols="12">
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th class="count-field">Terpakai</th>
                                    <th class="count-field">Sisa</th>
                                    <th class="icon-field"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <template v-if="hasDetails">
                                    <tr v-for="(item, index) in record.details" :key="index">
                                        <td>{{ item.text }}</td>
                                        <td>{{ item.used }}</td>
                                        <td>{{ item.blnc }}</td>
                                        <td><v-icon>{{ item.aprv ? 'check' : 'close' }}</v-icon></td>
                                    </tr>
                                </template>

                                <tr v-else>
                                    <td class="text-center overline grey--text" colspan="2">Tidak ada item untuk di tampilkan</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>

            <div class="d-block overline mt-4">catatan pemeriksaan:</div>

            <v-row>
                <v-col class="pt-0" cols="12">
                    <v-textarea
                        v-model="record.notes"
                        rows="3"
                        readonly
                    ></v-textarea>
                </v-col>
            </v-row>
        </v-page-dialog>

        <!-- print spk -->
        <v-page-dialog
            title="Surat Perintah Kerja"
            subtitle="preview surat perintah kerja"
            submit-name="print"
            v-model="dialogPrint"
            @print="postPrint"
            @cancel="cancelPrint"
        >
            <v-row id="print-area" class="print-area">
                <v-col cols="12">
                    <div class="d-flex kopsurat">
                        <div class="logo">
                            <img src="/images/logo-holder.png" alt="logo">
                        </div>
                        <div class="describe">
                            <div class="title text-uppercase font-weight-black">pemerintah provinsi banten</div>
                            <div class="display-1 text-uppercase font-weight-black">sekretariat daerah</div>
                            <div class="body-2">Kawasan Pusat Pemerintahan Provinsi Banten (KP3B)</div>
                            <div class="body-2">Jalan Syech Nawawi Al-Bantani, Palima Serang Banten Telp. (0254) 200123 Fax. 200520</div>
                        </div>
                    </div>
                </v-col>

                <v-col cols="12" align="center">
                    <div class="title text-uppercase font-weight-bold">surat perintah kerja</div>
                    <div class="subtitle-2 text-uppercase">nomor: {{ '024/' + record.id + '.' + 'BR-UM.SETDA' + '/' + record.year }}</div>
                </v-col>

                <v-col cols="12">
                    <div class="body-1">Kepada Yth;</div>
                    <div class="body-1"><span class="field d-inline-block" style="width: 100px;">Perusahaan</span><span class="value font-weight-bold">: {{ record.garage ? record.garage.text : '' }}</span></div>
                    <div class="body-1"><span class="field d-inline-block" style="width: 100px;">Alamat</span><span class="value font-weight-bold">: {{ record.garage ? record.garage.address : '' }}</span></div>
                    <div class="body-1"><span class="field d-inline-block" style="width: 100px;">Telepon</span><span class="value font-weight-bold">: {{ record.garage ? record.garage.phone : '' }}</span></div>
                    <p>&nbsp;</p>
                    <div class="body-1">Untuk melaksanakan service kendaraan roda 2 (dua) dan atau kendaraan roda 4 (empat) dan atau kendaraan roda 6 (enam) milik Pemerintah Provinsi Banten, sesuai data berikut ini:</div>
                    <div class="body-1"><span class="field d-inline-block" style="width: 100px;">Merek</span><span class="value font-weight-bold">: {{ record.vehicle ? record.vehicle.brand : '' }}</span></div>
                    <div class="body-1"><span class="field d-inline-block" style="width: 100px;">Type</span><span class="value font-weight-bold">: {{ record.vehicle ? record.vehicle.type : '' }}</span></div>
                    <div class="body-1"><span class="field d-inline-block" style="width: 100px;">No. Polisi</span><span class="value font-weight-bold">: {{ record.vehicle ? record.vehicle.police_id : ''  }}</span></div>
                    <div class="body-1"><span class="field d-inline-block" style="width: 100px;">Pemegang</span><span class="value font-weight-bold">: {{ record.vehicle ? record.vehicle.name : '' }}</span></div>
                    <p>&nbsp;</p>
                    <v-simple-table class="mt-2">
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th class="body-1 pl-0">Dengan rincian sebagai berikut:</th>
                                </tr>
                            </thead>

                            <tbody>
                                <template v-if="hasDetails">
                                    <tr v-for="(item, index) in record.details" :key="index">
                                        <td class="pl-0">{{ item.text }}</td>
                                    </tr>
                                </template>

                                <tr v-else>
                                    <td class="text-center overline grey--text" colspan="2">Tidak ada item untuk di tampilkan</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                    <p>&nbsp;</p>
                </v-col>
                <v-col cols="6">
                    <qr-code :size="96" :text="domain + '/' + this.record.id + '/verification'"></qr-code>
                </v-col>
                <v-col cols="6" align="center">
                    <div class="body-1">Serang, {{ record.created_at }}</div>
                    <div class="body-1 text-uppercase font-weight-bold">kepala biro umum</div>
                    <div class="body-1 text-uppercase font-weight-bold">setda provinsi banten</div>
                    <p class="signature mb-0">
                        <img src="/images/signature-holder.png" alt="TTD" style="height: 136px;">
                    </p>
                    <div class="body-1 font-weight-bold" style="text-decoration: underline;">Drs. H. AHMAD SYAUKANI, M.Si</div>
                    <div class="body-1">Pembina Utama Muda</div>
                    <div class="body-1">NIP. 19740422 199303 1 001</div>
                </v-col>
            </v-row>
        </v-page-dialog>

        <!-- invoice form -->
        <v-page-dialog
            title="Invoice Pembayaran"
            subtitle="surat tagihan pembayaran bengkel"
            v-model="dialogInvoice"
            @submit="postInvoice"
            @cancel="cancelInvoice"
        >
            <v-row>
                <v-col cols="3">
                    <v-text-field
                        label="No. Invoice"
                        v-model="record.inv_refsinv"
                    ></v-text-field>
                </v-col>
                
                <v-col cols="6"></v-col>

                <v-col cols="3">
                    <v-date-menu
                        label="Tgl. Invoice"
                        v-model="record.inv_dateinv"
                    ></v-date-menu>
                </v-col>
            </v-row>

            <div class="d-block overline mt-4">item tagihan service:</div>

            <v-row>
                <v-col class="pt-0" cols="9">
                    <v-combobox
                        label="Item Service"
                        :items="items"
                        v-model="itemInvoice.item"
                        hide-details
                    ></v-combobox>
                </v-col>

                <v-col class="pt-0 pr-2" cols="1">
                    <v-number-field
                        label="Qty"
                        v-model="itemInvoice.qty"
                        hide-details
                    ></v-number-field>
                </v-col>

                <v-col class="pt-0 pl-0" cols="2">
                    <v-number-field
                        append-icon="add"
                        label="@ Harga"
                        v-model="itemInvoice.price"
                        @click:append="addInvoiceItem"
                        hide-details
                    ></v-number-field>
                </v-col>

                <v-col cols="12">
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th class="short-field text-center">Kendali</th>
                                    <th class="short-field text-end">Qty</th>
                                    <th class="number-field text-end">@Harga</th>
                                    <th class="number-field text-end">Subtotal</th>
                                    <th class="icon-field"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <template v-if="hasDetailsInvoice">
                                    <tr v-for="(item, index) in record.inv_details" :key="index">
                                        <td>{{ item.text }}</td>
                                        <td class="text-center">{{ item.notes }}</td>
                                        <td class="text-end">{{ item.qty }}</td>
                                        <td class="text-end">{{ $root.formatCurrency(item.price) }}</td>
                                        <td class="text-end">{{ $root.formatCurrency(item.amount) }}</td>
                                        <td class="text-end">
                                            <v-icon @click="removeInvoiceItem(index)">remove_circle_outline</v-icon>
                                        </td>
                                    </tr>
                                </template>

                                <tr v-else>
                                    <td class="text-center overline grey--text" colspan="6">Tidak ada item untuk di tampilkan</td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="9"></v-col>
                <v-col cols="3">
                    <v-number-field
                        align-right
                        label="Subtotal"
                        v-model="record.inv_subtotal"
                        readonly
                        hide-details
                    ></v-number-field>
                </v-col>

                <v-col class="pt-0" cols="9"></v-col>
                <v-col class="pt-0" cols="3">
                    <v-number-field
                        align-right
                        label="Diskon"
                        v-model="record.inv_disc"
                        hide-details
                    ></v-number-field>
                </v-col>

                <v-col class="pt-0" cols="9"></v-col>
                <v-col class="pt-0" cols="3">
                    <v-number-field
                        align-right
                        label="Pajak"
                        v-model="record.inv_tax"
                        readonly
                        hide-details
                    ></v-number-field>
                </v-col>

                <v-col class="pt-0" cols="9"></v-col>
                <v-col class="pt-0" cols="3">
                    <v-number-field
                        align-right
                        label="Total"
                        v-model="record.inv_total"
                        readonly
                    ></v-number-field>
                </v-col>
            </v-row>
        </v-page-dialog>
    </v-page-wrap>
</template>

<script>
import { pageMixins } from '@apps/mixins/PageMixins';
import { mapGetters, mapActions } from 'vuex';

export default {
    name: 'page-service',

    mixins: [pageMixins],

    route: [
        { path: 'service', name: 'service', root: 'monoland' },
    ],

    computed: {
        ...mapGetters([ 'mode', 'serverUrl' ]),

        enableEdit: function() {
            if (this.auth.authent === 'operator') {
                return this.record && this.record.status === 'drafted';
            }

            return false;
        },

        enableDelete: function() {
            if (this.auth.authent === 'operator') {
                return this.record && this.record.status === 'drafted';
            } else if (this.auth.authent === 'pptk') {
                return this.record && this.record.status === 'submissioned';
            }

            return false;
        },

        domain: function() {
            return window.location.hostname;
        },
        
        garages: function() {
            if (this.combos && this.combos.hasOwnProperty('garages')) {
                return this.combos.garages;
            }
            
            return [];
        },

        vehicles: function() {
            if (this.combos && this.combos.hasOwnProperty('vehicles')) {
                return this.combos.vehicles;
            }
            
            return [];
        },

        hasDetails: function() {
            return this.record && this.record.details && this.record.details.length > 0;
        },

        hasDetailsInvoice: function() {
            return this.record && this.record.inv_details && this.record.inv_details.length > 0;
        },

        subtotal: function() {
            if (this.record && this.record.inv_details) {
                return this.record.inv_details.reduce((total, detail) => {
                    return total + parseFloat(parseInt(detail.qty) * parseFloat(detail.price));
                }, 0);
            } else {
                return 0;
            }
        },

        tax: function() {
            let calc = 0; 
            
            if (this.record) {
                calc = (parseFloat(this.record.inv_subtotal) - parseFloat(this.record.inv_disc)) * 10/100;

                if (isNaN(calc)) return 0;
            }

            return calc;
        },

        total: function() {
            let calc = 0;
            
            if (this.record) {
                calc = parseFloat(this.record.inv_subtotal) - parseFloat(this.record.inv_disc) - parseFloat(this.record.inv_tax);
                if (isNaN(calc)) return 0;
            }

            return calc;
        }
    },

    data:() => ({
        items: [],
        temps: [],

        currentItem: null,
        itemInvoice: {
            item: null,
            qty: 0,
            price: 0
        },

        dialogDraft: false,
        dialogDisposition: false,
        dialogSubmission: false,
        dialogExamine: false,
        dialogPrint: false,
        dialogInvoice: false
    }),

    created() {
        this.tableHeaders([
            { text: 'No.Pol', value: 'police_id' },
            { text: 'Periode', value: 'periode', class: 'medium-field' },
            { text: 'Status', value: 'status', class: 'status-field' },
            { text: 'Updated', value: 'updated_at', class: 'datetime-field' }
        ]);

        this.pageInfo({
            icon: 'people',
            title: 'Service',
        });

        this.dataUrl(`/api/service`);

        this.setRecord({
            id: null,
            vehicle: null,
            periode: null,
            year: null,
            garage: null,
            notes: null,
            details: [],

            inv_id: null,
            inv_refsinv: null,
            inv_dateinv: null,
            inv_details: [],
            inv_subtotal: 0,
            inv_discount: 0,
            inv_tax: 0,
            inv_total: 0
        });
    },

    methods: {
        ...mapActions(['clearSelected']),

        addItem: function() {
            try {
                if (this.record.vehicle && this.record.periode && this.currentItem) {
                    let idx = this.record.details.findIndex(obj => obj.value === this.currentItem.value);

                    if (idx === -1) {
                        this.record.details.push(this.currentItem);
                    }
                    
                    this.currentItem = null;
                } else {
                    this.currentItem = null;
                }    
            } catch (error) {
                this.$store.dispatch('errors', error);   
            }
        },

        removeItem: function(index) {
            this.temps.push( this.record.details[index] );
            this.record.details.splice(index, 1);
        },

        addInvoiceItem: function() {
            if (this.itemInvoice.item) {
                let idx = this.record.inv_details.findIndex(obj => obj.value === this.itemInvoice.item.value);

                if (idx === -1) {
                    this.record.inv_details.push({
                        blnc: this.itemInvoice.item.blnc,
                        kind: this.itemInvoice.item.kind,
                        price: this.itemInvoice.price,
                        qty: this.itemInvoice.qty,
                        amount: parseInt(this.itemInvoice.price) * parseInt(this.itemInvoice.qty),
                        text: this.itemInvoice.item.text,
                        unit: this.itemInvoice.item.unit,
                        used: this.itemInvoice.item.used + 1,
                        value: this.itemInvoice.item.value,
                        notes: parseInt(this.itemInvoice.item.used) + 1 + '/' + parseInt(this.itemInvoice.item.blnc)
                    });
                }
                
                this.itemInvoice = { item: null, qty: 0, price: 0 };
            } else {
                this.itemInvoice = { item: null, qty: 0, price: 0 };
            }    
        },

        removeInvoiceItem: function(index) {
            this.record.inv_details.splice(index, 1);
        },

        openDraft: function() {
            this.dialogDraft = true;
        },

        cancelDraft: function() {
            this.dialogDraft = false;
            this.clearSelected();
        },

        postDraft: async function() {
            try {
                let { data } = await this.http.put(this.serverUrl + '/' + this.record.id + '/draft');

                // find and update current service
                let idx = this.records.findIndex(obj => obj.id === data.id);
                if (idx !== -1) this.records[idx].status = data.status;

                this.$store.dispatch('message', 'proses pengiriman berhasil!');
            } catch (error) {
                this.$store.dispatch('errors', error);
            } finally {
                this.dialogDraft = false;
                this.clearSelected();
            }
        },
        
        openDisposition: function() {
            this.dialogDisposition = true;
        },

        cancelDisposition: function() {
            this.dialogDisposition = false;
            this.clearSelected();
        },

        postDisposition: async function() {
            try {
                let { data } = await this.http.put(this.serverUrl + '/' + this.record.id + '/disposition');

                // find and update current service
                let idx = this.records.findIndex(obj => obj.id === data.id);
                if (idx !== -1) this.records[idx].status = data.status;

                this.$store.dispatch('message', 'proses pengiriman berhasil!');
            } catch (error) {
                this.$store.dispatch('errors', error);
            } finally {
                this.dialogDisposition = false;
                this.clearSelected();
                this.recordFetch();
            }
        },

        openSubmission: function() {
            this.dialogSubmission = true;
        },

        cancelSubmission: function() {
            this.dialogSubmission = false;

            let restore = Object.assign([], this.temps);

            for (let i = 0; i < restore.length; i++) {
                this.record.details.push(restore[i])
            }

            this.temps = [];
            this.clearSelected();
            this.recordFetch();
        },

        postSubmission: async function() {
            try {
                let { data } = await this.http.put(
                    this.serverUrl + '/' + this.record.id + '/submission', 
                    this.record
                );

                // find and update current service
                let idx = this.records.findIndex(obj => obj.id === data.id);
                if (idx !== -1) {
                    this.records[idx].status = data.status;
                    this.records[idx].garage = data.garage;
                    this.records[idx].details = data.details;
                }

                this.$store.dispatch('message', 'proses pengiriman berhasil!');
            } catch (error) {
                this.$store.dispatch('errors', error);
            } finally {
                this.dialogSubmission = false;
                this.clearSelected();
                this.recordFetch();
            }
        },
        
        openExamine: function() {
            this.dialogExamine = true;
        },

        cancelExamine: function() {
            this.dialogExamine = false;
            this.clearSelected();
        },

        postExamine: async function() {
            try {
                let { data } = await this.http.put(this.serverUrl + '/' + this.record.id + '/examine');

                // find and update current service
                let idx = this.records.findIndex(obj => obj.id === data.id);
                if (idx !== -1) this.records[idx].status = data.status;

                this.$store.dispatch('message', 'proses pengiriman berhasil!');
            } catch (error) {
                this.$store.dispatch('errors', error);
            } finally {
                this.dialogExamine = false;
                this.clearSelected();
                this.recordFetch();
            }
        },
        
        openPrint: function() {
            this.dialogPrint = true;
        },

        cancelPrint: function() {
            this.dialogPrint = false;
            this.clearSelected();
        },

        postPrint: async function() {
            try {
                let { data } = await this.http.put(this.serverUrl + '/' + this.record.id + '/approve');

                // find and update current service
                let idx = this.records.findIndex(obj => obj.id === data.id);
                if (idx !== -1) this.records[idx].status = data.status;

                let win = window.open('', 'PRINT', 'height=600,width=800');
                    win.document.write('<html>');
                    win.document.write('<head>');
                    win.document.write('<title>Print Preview</title>');
                    win.document.write('</head>');
                    win.document.write('<body>');
                    win.document.write('<div data-app="true" class="v-application v-application--is-ltr theme--light">');
                    win.document.write('<div class="v-application--wrap">');
                    win.document.write('<main class="v-content">');
                    win.document.write('<div class="v-content__wrap">');
                    win.document.write('<div class="row print-area live-view" style="padding-top: 0px; background-color: #ffffff;">');
                    win.document.write(document.getElementById('print-area').innerHTML);
                    win.document.write('</div>');
                    win.document.write('</div>');
                    win.document.write('</main>');
                    win.document.write('</div>');
                    win.document.write('</div>');
                    win.document.write('</body>');
                    win.document.write('</html>');

                let css = win.document.createElement('link');
                    css.type = 'text/css';
                    css.rel = 'stylesheet';
                    css.href = '/styles/monoland.css?version=1'; 
                    css.media = 'all';
                    win.document.getElementsByTagName("head")[0].appendChild(css);

                setTimeout(() => {
                    win.document.close();
                    win.focus();
                    win.print();
                    win.close();
                }, 1500);
            } catch (error) {
                this.$store.dispatch('errors', error);
            } finally {
                this.dialogPrint = false;
                this.clearSelected();
                this.recordFetch();
            }
        },
        
        openInvoice: function() {
            this.dialogInvoice = true;
        },

        cancelInvoice: function() {
            this.dialogInvoice = false;
            this.clearSelected();
        },

        postInvoice: async function() {
            try {
                await this.http.post(
                    this.serverUrl + '/' + this.record.id + '/invoice', 
                    {
                        id: this.record.inv_id,
                        refsinv: this.record.inv_refsinv,
                        dateinv: this.record.inv_dateinv,
                        details: this.record.inv_details,
                        subtotal: this.record.inv_subtotal,
                        discount: this.record.inv_discount,
                        tax: this.record.inv_tax,
                        total: this.record.inv_total
                    }
                );

                this.$store.dispatch('message', 'proses pengiriman berhasil!');
            } catch (error) {
                this.$store.dispatch('errors', error);
            } finally {
                this.dialogInvoice = false;
                this.clearSelected();
                this.recordFetch();
            }
        },
    },

    watch: {
        'record.vehicle': {
            handler: async function(vehicle) {
                if (vehicle) {
                    let { data } = await this.http.get('/api/vehicle/' + vehicle.value + '/items');
                    
                    this.items = data;
                }
            },

            deep: true
        },

        subtotal: function(newValue) {
            this.record.inv_subtotal = newValue;
        },

        tax: function(newValue) {
            this.record.inv_tax = newValue;
        },

        total: function(newValue) {
            this.record.inv_total = newValue;
        }
    }
};
</script>