import { exampleFunction } from '../modules/helpers'
import _config from './../modules/config'

export default {

    props: {
        example_data: {
            type: String,
            default() {
                return this._config.example_data
            }
        }
    },

    inject: {

    },

    computed: {

        exampleFromFunction() {
            return exampleFunction();
        },
    },

    beforeCreate() {
        this._config = Object.assign( _config, AWEMA.utils.object.get(AWEMA_CONFIG, 'prestashop', {}) )
    }
}
