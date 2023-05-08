<script>
  import EmptyLayout from '../../Shared/EmptyLayout.vue'; 
  import ItemDetailsPopup from '../components/menu/ItemDetailsPopup.vue'; 
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import { useMessages } from '../../Composables/useMessages';
  import 'swiper/css';
  import { router } from '@inertiajs/core';

  export default { 
    
    layout: EmptyLayout,

    setup() {
      let { showErrors, showMessage } = useMessages();
      return {showErrors, showMessage}
    },

    components: {
      Swiper,
      SwiperSlide,
      ItemDetailsPopup
    },

    props: {
      images: Object,
      menus: Object,
      cart: Object,
      errors: Object,
      flash: Object,
    },

    data() {
      return {
        showDetailPopup: false,
        itemDetail: {},
        
      }
    },

    methods: {
      showItemDetail(data) {
        this.showDetailPopup = true;
        this.itemDetail = data;
      },

      cartTotal() {
        let cartTotal = 0;
        this.cart.map((item, index) => {
          cartTotal += item.price*item.quantity
        });

        return cartTotal.toFixed(2);
      },

      checkout() {
        router.get('/checkout');
      }
    },
  };
</script>
<template>
  
  <swiper
    :slides-per-view="1"
    :space-between="0"
  >
    <swiper-slide v-for="(image, index) in images" :key="image.id">
      <div class="slider-image" :style="{'background': `url(${image.src}) center/cover no-repeat`}" ></div>
    </swiper-slide>
  </swiper>

  <section class="mt-5">
    <div class="container">
      <div class="row">
        <div class="col-12" v-html="showErrors(errors)"></div>
        <div class="col-12" v-html=" showMessage(flash) "></div>
        <div class="col-lg-8">

          <template v-for="(menu, index) in menus" :key="menu.id">
            <div class="menu-parent mb-5" v-if="menu.items.length != 0" >
              <h3>{{ menu.name }}</h3>
              <div class="row">

                <template v-for="(item, item_index) in menu.items" :key="item.key">
                  <div class="col-lg-6 mb-4" @click="showItemDetail(item)">
                    <div class="item-wrap d-flex h-100 justify-content-between">
                      <div class="content">
                        <h6 class="item-title">{{ item.name }}</h6>
                        <p class="item-desc">{{ item.name_display }}</p>
                      </div>
                      <div class="price ps-3">
                        ${{ item.price }}
                      </div>
                    </div>
                  </div>
                </template>
                
              </div>
            </div>
          </template>

        </div>
        <div class="col-lg-4">
          <div class="cart-parent position-sticky top-0 pt-5">
            <div class="card">
              <div class="card-header">
                <h5 class="h5 mb-0">Cart</h5>
              </div>
              <div class="card-body">

                <template v-if="cart.length > 0">
                  <div class="cart-items">
                    <div v-for="(item, index) in cart" :key="item.id" class="cart-item">
                      <div class="detail d-flex align-items-center justify-content-between">
                        <div class="name">{{ item.name }} X {{ item.quantity }}</div>
                        <div class="price ps-2">${{ (item.quantity*item.price).toFixed(2) }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="checkout mt-5">
                    <button class="btn btn-success w-100" @click="checkout">Checkout <span class="float-end">${{ cartTotal() }}</span></button>
                  </div>
                </template>
                <p v-else class="text-center mt-5">No Items on your cart</p>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <ItemDetailsPopup :showDetailPopup="showDetailPopup" :item-detail="itemDetail" @hidePopup="showDetailPopup = false"></ItemDetailsPopup>

</template>

<style scoped>
 .slider-image {
  height: 80vh;
 }

 .item-wrap {
  padding: 10px;
  border: 1px solid #ddd;
  cursor: pointer;
 }

 .item-title {
  font-size: 16px;
 }

 .item-desc {
  font-size: 15px;
  font-weight: 300;
  margin-bottom: 0;
 }

 .cart-item {
  padding: 10px 0;
  border-bottom: 1px solid #ddd;
 }

 .cart-item:last-child {
  border-bottom: none;
 }


</style>