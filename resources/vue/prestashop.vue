<template>
<div>
    <p>Translation key <code>PRESTASHOP_EXAMPLE</code> from <code>prestashop/resources/lang/**/js.php</code>: {{$lang.PRESTASHOP_EXAMPLE}}</p>
    <button class="form-builder__send btn" @click="testDebug">Test console log for debug</button>
    <p>From config JS file: {{this.example_data}}</p>
    <p>Example function: {{this.exampleFromFunction}}</p>
    <p>
        <button class="form-builder__send btn" @click="testLoading">Test loading</button>
        <span v-if="isLoading">is loading...</span>
    </p>
</div>
</template>

<script>
import prestashopMixin from '../js/mixins/prestashop'
import {consoleDebug} from '../js/modules/helpers'

let _uniqSectionId = 0;

export default {

    name: 'prestashop',

    mixins: [ prestashopMixin ],

    props: {
        name: {
            type: String,
            default() {
                return `prestashop-${ _uniqSectionId++ }`
            }
        },

        default: Object,

        storeData: String,
    },


    computed: {
        prestashop() {
            return this.$store.state.prestashop[this.name]
        },

        isLoading() {
            return this.prestashop && this.prestashop.isLoading
        },
    },

    created() {

        let data = this.storeData ? this.$store.state[this.storeData] : (this.default || {})

        this.$store.commit('prestashop/create', {
            name: this.name,
            data
        })
    },

    mounted() {

    },

    methods: {
        testDebug(){
            consoleDebug('message', ['data1'], ['data2'])
        },

        testLoading(){
            if ( this.isLoading) return;

            AWEMA.emit(`prestashop::${this.name}:before-test-loading`)

            this.$store.dispatch('prestashop/testLoading', {
                name: this.name
            }).then( data => {
                consoleDebug('data', data);
                this.$emit('success', data.data)
                this.$store.$set(this.name, this.$get(data, 'data', {}))
            })
        }
    },


    beforeDestroy() {

    }
}
</script>
