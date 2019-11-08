<template>
    <v-page-wrap no-trash>
        <v-row>
            <v-col cols="3">
                <v-card>
                    <v-card-text>
                        <div class="d-flex justify-center display-4" :class="$root.theme + '--text'">1000</div>
                    </v-card-text>

                    <v-system-bar :color="$root.theme">
                        <v-spacer></v-spacer>
                        <span class="overline white--text">Pengajuan Service</span>
                        <v-spacer></v-spacer>
                    </v-system-bar>
                </v-card>
            </v-col>

            <v-col cols="3">
                <v-card>
                    <v-card-text>
                        <div class="d-flex justify-center display-4" :class="$root.theme + '--text'">1000</div>
                    </v-card-text>
                    
                    <v-system-bar :color="$root.theme">
                        <v-spacer></v-spacer>
                        <span class="overline white--text">Pengajuan Disetujui</span>
                        <v-spacer></v-spacer>
                    </v-system-bar>
                </v-card>
            </v-col>

            <v-col cols="3">
                <v-card>
                    <v-card-text>
                        <div class="d-flex justify-center display-4" :class="$root.theme + '--text'">1000</div>
                    </v-card-text>
                    
                    <v-system-bar :color="$root.theme">
                        <v-spacer></v-spacer>
                        <span class="overline white--text">Pengajuan Disetujui</span>
                        <v-spacer></v-spacer>
                    </v-system-bar>
                </v-card>
            </v-col>

            <v-col cols="3">
                <v-card>
                    <v-card-text>
                        <div class="d-flex justify-center display-4" :class="$root.theme + '--text'">1000</div>
                    </v-card-text>
                    
                    <v-system-bar :color="$root.theme">
                        <v-spacer></v-spacer>
                        <span class="overline white--text">Pengajuan Disetujui</span>
                        <v-spacer></v-spacer>
                    </v-system-bar>
                </v-card>
            </v-col>
        </v-row>
    </v-page-wrap>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    name: 'page-home',

    route: [
        { path: 'home', name: 'home', root: 'monoland' },
    ],

    created() {
        this.initStore();
        
        this.pageInfo({
            icon: 'home',
            title: 'Beranda',
        });
    },

    data:() => ({
        // 
    }),

    methods: {
        ...mapActions(['pageInfo', 'initStore']),
    }
};
</script>
