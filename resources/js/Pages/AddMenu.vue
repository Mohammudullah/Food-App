<script setup>
    import { router } from '@inertiajs/core';
    let props = defineProps({
        'errors': Object
    });

    let AddMenu = (e) => {
        let formdata = new FormData(e.target);

        router.post('/menus', formdata, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            forceFormData: true,
        })
    }
</script>
<template>
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="h4">Add Menu</h4>
                </div>
                <div class="card-body">
                    <form action="" @submit.prevent="AddMenu">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <p class="text-danger">{{ errors.name }}</p>
                        </div>
                        <div class="form-group">
                            <label for="name_display">Name Display</label>
                            <input type="text" name="name_display" id="name_display" class="form-control">
                            <p class="text-danger">{{ errors.name_display }}</p>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div>
                                <label class="radio-label">
                                    <input type="radio" value="1" name="status" checked>
                                    <span>Active</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" value="2" name="status">
                                    <span>Inactive</span>
                                </label>
                            </div>
                            <p class="text-danger">{{ errors.status }}</p>
                        </div>

                        <div class="action-btn text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
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