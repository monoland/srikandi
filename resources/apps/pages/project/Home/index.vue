<template>
    <v-page-wrap no-trash>
        <v-row>
            <v-col cols="3">
                <v-card>
                    <v-card-text>
                        <div class="d-flex justify-center display-4" :class="$root.theme + '--text'">{{ countOfType.motor }}</div>
                    </v-card-text>

                    <v-system-bar :color="$root.theme">
                        <v-spacer></v-spacer>
                        <span class="overline white--text">jumlah motor</span>
                        <v-spacer></v-spacer>
                    </v-system-bar>
                </v-card>
            </v-col>

            <v-col cols="3">
                <v-card>
                    <v-card-text>
                        <div class="d-flex justify-center display-4" :class="$root.theme + '--text'">{{ countOfType.mobil }}</div>
                    </v-card-text>
                    
                    <v-system-bar :color="$root.theme">
                        <v-spacer></v-spacer>
                        <span class="overline white--text">jumlah mobil</span>
                        <v-spacer></v-spacer>
                    </v-system-bar>
                </v-card>
            </v-col>

            <v-col cols="3">
                <v-card>
                    <v-card-text>
                        <div class="d-flex justify-center display-4" :class="$root.theme + '--text'">{{ countOfService.motor }}</div>
                    </v-card-text>
                    
                    <v-system-bar :color="$root.theme">
                        <v-spacer></v-spacer>
                        <span class="overline white--text">Service Motor</span>
                        <v-spacer></v-spacer>
                    </v-system-bar>
                </v-card>
            </v-col>

            <v-col cols="3">
                <v-card>
                    <v-card-text>
                        <div class="d-flex justify-center display-4" :class="$root.theme + '--text'">{{ countOfService.mobil }}</div>
                    </v-card-text>
                    
                    <v-system-bar :color="$root.theme">
                        <v-spacer></v-spacer>
                        <span class="overline white--text">Service Mobil</span>
                        <v-spacer></v-spacer>
                    </v-system-bar>
                </v-card>
            </v-col>
        </v-row>
    </v-page-wrap>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
    name: 'page-home',

    route: [
        { path: 'home', name: 'home', root: 'monoland' },
    ],

    computed: {
        ...mapState([
            'http'
        ]),
    },

    created() {
        this.initStore();
        
        this.pageInfo({
            icon: 'home',
            title: 'Beranda',
        });
    },

    data:() => ({
        countOfType: {
            motor: 0,
            mobil: 0
        },

        countOfService: {
            motor: 0,
            mobil: 0
        }
    }),

    mounted() {
        this.dashboardType();
        this.dashboardService();
    },

    methods: {
        ...mapActions(['pageInfo', 'initStore']),

        dashboardType: async function() {
            let { data } = await this.http.get('/api/dashboard/type');
            this.countOfType.motor = data.motor;
            this.countOfType.mobil = data.mobil;
        },

        dashboardService: async function() {
            let { data } = await this.http.get('/api/dashboard/service');
            this.countOfService.motor = data.motor;
            this.countOfService.mobil = data.mobil;
        }
    }
};
</script>
