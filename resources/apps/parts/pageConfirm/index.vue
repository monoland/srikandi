<template>
    <v-dialog 
        :value="state" 
        max-width="332px"
        persistent 
    >
        <v-card 
            class="v-card--widget mt-4 mx-auto" 
            style="border-radius: 4px;"
            elevation="0" 
        >
            <v-sheet
                class="v-sheet--offset py-4 px-4 pb-3 mx-auto"
                color="deep-orange"
                max-width="calc(100% - 32px)"
            >
                <span class="d-block line-height1 title font-weight-regular mb-1 white--text">{{ title }}</span>
                <span class="d-block f-nunito caption font-weight-regular text-uppercase white--text">{{ subtitle }}</span>
            </v-sheet>

            <v-card-text class="px-4" style="text-align: justify;">
                <span>
                    <slot></slot>
                </span>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text :color="$root.theme" @click="$emit(submitName)">{{ submitName }}</v-btn>
                <v-btn text :color="$root.theme" @click="$emit(cancelName)">{{ cancelName }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>    
</template>

<script>
export default {
    name: 'v-page-confirm',

    computed: {
        
    },

    props: {
        title: {
            type: String,
            default: ''
        },

        subtitle: {
            type: String,
            default: ''
        },

        submitName: {
            type: String,
            default: 'submit'
        },

        cancelName: {
            type: String,
            default: 'cancel'
        },

        value: Boolean
    },

    data:() => ({
        state: false
    }),

    created() {
        this.state = this.value;
    },

    watch: {
        value: function(state) {
            this.state = state;
        }
    }
};
</script>
