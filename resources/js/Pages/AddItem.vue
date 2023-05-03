<script setup>
    import { router } from '@inertiajs/core';

    defineProps({
        'errors' : Object
    })

    let AddItem = (event) => {
        let formdata = new FormData(event.target);

        router.post('/items', 
            formdata
        , {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            forceFormData: true,

            onStart: () => {
                
            },
            onError: () => {

            },
            onSuccess: () => {
                
            }
        });
    }
</script>
<template>
    <div class="card">
        <div class="card-header">
            <h4 class="h4">Add new item</h4>
        </div>
        <div class="card-body">
            <form action="#" @submit.prevent="AddItem">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">Item Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <p class="text-danger">{{ errors.name }}</p>
                        </div>
                        <div class="form-group">
                            <label for="name_display">Name Display</label>
                            <input type="text" name="name_display" id="name_display" class="form-control">
                            <p class="text-danger">{{ errors.name_display }}</p>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">USD</span>
                                <input type="number" name="price" id="price" class="form-control" value="0" step="any"> 
                            </div>
                            <p class="text-danger">{{ errors.price }}</p>
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" id="sku" class="form-control">
                            <p class="text-danger">{{ errors.sku }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div>
                                <label class="radio-label">
                                    <input type="radio" value="1" name="status" checked>
                                    <span>Available</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" value="2" name="status">
                                    <span>Sold out</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" value="3" name="status">
                                    <span>Hidden</span>
                                </label>
                            </div>
                            <p class="text-danger">{{ errors.status }}</p>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <div>
                                <label class="radio-label">
                                    <input type="radio" value="1" name="notes" checked>
                                    <span>Enable</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" value="2" name="notes">
                                    <span>Disable</span>
                                </label>
                                <p class="text-danger">{{ errors.notes }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sku">Photo</label>
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
</style>