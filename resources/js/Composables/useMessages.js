export function useMessages(){

    /**
     * generate messages for errors
     */
    function showErrors(errors) {
        let alerts = '';

        Object.entries(errors).map(([index, error]) => {
            alerts += `<div class="alert alert-warning" role="alert">
                        ${error}
                    </div>`;
        })

        return alerts;
    }

    /**
     * generate flash message
     */
    function showMessage(message) {

        if(message.message == null) {
            return;
        }

        let alert = '';

        let type = message.type == null ? 'success' : message.type;

        alert = `<div class="alert alert-${type}" role="alert">
                        ${message.message}
                    </div>`;

        return alert;
    }

    /**
     * Show error for invalid input
     */
    function invalidError(error) {
        if(error) {
            return `<div class="form-input-error">${error}</div>`;
        }
    }

    return {showErrors, showMessage, invalidError};
}