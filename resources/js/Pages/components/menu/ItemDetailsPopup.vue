<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

    let props = defineProps({
        itemDetail: Object,
        showDetailPopup: Boolean,
    })
    let emits = defineEmits(['hidePopup']);

    let quantity = ref(1);

    let quantityInc = (state) => {
        quantity.value = (state == 'increment' ? quantity.value+1 : quantity.value-1);
        quantity.value = quantity.value < 1 ? 1 : quantity.value;
    }

    let hidePopup = () => {
        quantity.value = 1;
        emits('hidePopup')
    }

    let showPrice = () => {
        return (props.itemDetail.price * quantity.value).toFixed(2);
    }
    
    let addToCart = () => {
        router.put('/cart', {
            id: props.itemDetail.id, quantity:quantity.value,
        }, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                hidePopup();
            }
        });
    }
</script>
<template>
    <Transition
        enter-from-class="popup-hidden"
        enter-to-class="popup-showed"
        enter-active-class="popup-duration-300"
        leave-active-class="popup-duration-300"
        leave-from-class="popup-showed"
        leave-to-class="popup-hidden"
    >
    <div class="popup" v-if="showDetailPopup">
        <div class="popup-content">
            <div class="popup-head">
                <h4>Edit Selection</h4>
                <button class="btn" @click="hidePopup"><i class="las la-times"></i></button>
            </div>
            <div class="popup-body">
                <div class="item-details">
                    <img v-if="itemDetail.photo" :src="`/uploads/${itemDetail.photo}`" :alt="itemDetail.name">
                    <div class="item-name">{{ itemDetail.name }}</div>
                    <p class="item-description">{{ itemDetail.name_display }}</p>
                    <div class="item-quantity">
                        <button class="btn" @click="quantityInc('decrement')"><i class="las la-minus"></i></button>
                        <input type="number" class="form-control text-center" v-model="quantity">
                        <button class="btn" @click="quantityInc('increment')"><i class="las la-plus"></i></button>
                    </div>
                    <div class="price">
                        ${{ showPrice() }}
                    </div>
                    <button class="btn btn-success mt-4 mb-2" @click="addToCart">Add To cart</button>
                </div>
            </div>
        </div>
    </div>
  </Transition>
    
</template>