<script setup>
    import { router } from '@inertiajs/core';

    let props = defineProps({
        'errors' : Object,
        'item'  : Object,
    });

    let item = props.item;

    let EditItem = (event) => {
        let formdata = new FormData(event.target);
        formdata.append('_method', 'put');

        router.post(`/items/${props.item.id}`, 
            formdata
        , {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            forceFormData: true,
        });
    }
</script>
<template>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4 class="h4">Edit ({{ item.name }})</h4>
                <Link :href="`/items/${item.id}`" method="delete" as="button" class="btn btn-danger"><i class="fa fa-trash"></i></Link>
            </div>
        </div>
        <div class="card-body">
            <form action="#" @submit.prevent="EditItem">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">Item Name</label>
                            <input type="text" name="name" id="name" class="form-control" v-model="item.name">
                            <p class="text-danger">{{ errors.name }}</p>
                        </div>
                        <div class="form-group">
                            <label for="name_display">Name Display</label>
                            <input type="text" name="name_display" id="name_display" class="form-control" v-model="item.name_display">
                            <p class="text-danger">{{ errors.name_display }}</p>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">USD</span>
                                <input type="number" name="price" id="price" class="form-control" step="any" v-model="item.price"> 
                            </div>
                            <p class="text-danger">{{ errors.price }}</p>
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" id="sku" class="form-control" v-model="item.sku">
                            <p class="text-danger">{{ errors.sku }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div>
                                <label class="radio-label">
                                    <input type="radio" value="1" name="status" v-model="item.status">
                                    <span>Available</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" value="2" name="status" v-model="item.status">
                                    <span>Sold out</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" value="3" name="status" v-model="item.status">
                                    <span>Hidden</span>
                                </label>
                            </div>
                            <p class="text-danger">{{ errors.status }}</p>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <div>
                                <label class="radio-label">
                                    <input type="radio" value="1" name="notes" v-model="item.notes">
                                    <span>Enable</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" value="2" name="notes" v-model="item.notes">
                                    <span>Disable</span>
                                </label>
                                <p class="text-danger">{{ errors.notes }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sku">Photo</label>
                            <div v-if="item.old_photo" class="mb-2 item-photo">
                                <img :src="'/uploads/'+item.old_photo" alt="item.photo">
                            </div>
                            <input type="file" name="photo" id="photo" class="form-control">
                            <p class="text-danger">{{ errors.photo }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<style>
    .radio-label span {
        display: inline-block;
        border: 1px solid #DDD;
        padding: 10px 20px;
        text-align: center;
        margin-right: 10px;
        cursor: pointer;
        border-radius: 5px;
    }

    input[name="status"], input[name="notes"] {
        display: none;
    }

    input[name="status"]:checked + span, input[name="notes"]:checked + span {
        background: #686868;
        color: #FFF;
    }

    .item-photo img {
        width: 100%;
        object-fit: cover;
    }
</style>