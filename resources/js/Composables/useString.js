export function useString() {
    function addCurrency(value, toFixied = 0, currency = '$') {
        value = Number(value);
        let absVal = Math.abs(value);
        absVal = toFixied > 0 ? absVal.toFixed(toFixied) : absVal;

        return value < 0 ? `-${currency}${absVal}` : `${currency}${absVal}`;
    }

    return {addCurrency}
}