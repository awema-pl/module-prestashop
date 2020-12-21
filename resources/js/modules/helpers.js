/**
 * Example const
 *
 * @const {String} EXAMPLE_CONST
 */

export const EXAMPLE_CONST = 'example-const'


/**
 * Restores flatted single-level object to a nested object
 *
 * @returns {Boolean}
 *
 */

export function exampleFunction() {
    return 'example-function';
}

export function consoleDebug(message, ...optionalParams){
    if (AWEMA_CONFIG.dev === true){
        console.debug(message, optionalParams);
    }
}
