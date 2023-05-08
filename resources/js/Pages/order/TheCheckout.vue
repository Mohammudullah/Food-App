<script>
    export default {
        layout: EmptyLayout,
    }
</script>
<script setup>
    import EmptyLayout from '../../Shared/EmptyLayout.vue'; 
    import { useString } from '../../Composables/useString';
    import { useMessages } from '../../Composables/useMessages';
    import 'intl-tel-input/build/css/intlTelInput.css';
    import intlTelInput from 'intl-tel-input';
    import { router } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';

    defineProps({
        cartInfo : Object,
        errors: Object
    });

    onMounted(() => {

        const input = document.querySelector("#phone");
        iti = intlTelInput(input, {
            utilsScript: "js/utils.min.js",
            autoInsertDialCode: false
        });
    });

    let placeOrder = (event) => {
        let formdata = new FormData(event.currentTarget);
        formdata.append('phone', iti.getNumber());

        router.post('/place-order', formdata, {
            preserveScroll: true,
            preserveState: true,
        })
    }

    let { addCurrency } = useString();
    let { invalidError } = useMessages();
    let phone = ref('');
    let iti = null;
    
</script>

<template>
    <div class="container">
        <form action="#" @submit.prevent="placeOrder">
            <div class="row">
                <div class="col-lg-6 mx-auto mt-5">
                    <div class="card mt-5">
                        <div class="card-body">
                            <div class="cart-info">
                                <table>
                                    <tr>
                                        <td>Sub Total:</td>
                                        <td class="text-end">{{ addCurrency(cartInfo.subTotal) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Fee:</td>
                                        <td class="text-end">{{ addCurrency(cartInfo.deliveryFee) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Discount:</td>
                                        <td class="text-end">{{ addCurrency(cartInfo.discount) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax:</td>
                                        <td class="text-end">{{ addCurrency(cartInfo.tax) }}</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td>Total:</td>
                                        <td class="text-end">{{ addCurrency(cartInfo.total) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <table class="w-100 mt-3">
                                <tr>
                                    <td class="text-end pe-2">
                                        <label for="name">Name</label>
                                    </td>
                                    <td>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                        <div v-html="invalidError(errors.name)"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end pe-2">
                                        <label for="email">Email</label>
                                    </td>
                                    <td>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                                        <div v-html="invalidError(errors.email)"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end pe-2">
                                        <label for="name">Phone</label>
                                    </td>
                                    <td>
                                        <input type="text" id="phone" class="form-control">
                                        <div v-html="invalidError(errors.phone)"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="select-payment-method">
                        <input type="radio" name="payment_method" id="online" value="online">
                        <label for="online">Online</label>
                        <input type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery">
                        <label for="cash_on_delivery">Cash On Delivery</label>
                    </div>
                    <div class="text-center" v-html="invalidError(errors.payment_method)"></div>

                    <button type="submit" class="btn btn-success w-100 mt-5 mb-4">Place Order</button>
                </div>
            </div>
        </form>
    </div>
</template>
<style scoped>

    .cart-info {
        display: flex;
        justify-content: flex-end;
    }
    .cart-info table {
        width: 200px;
    }

    .select-payment-method {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 50px;
    }

    .select-payment-method label {
        padding: 10px 20px;
        border: 1px solid #ddd;
        cursor: pointer;
    }

    .select-payment-method input {
        display: none;
    }

    .select-payment-method input:checked+label {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>