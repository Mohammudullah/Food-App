<script setup>
    import { router } from '@inertiajs/core';


    let props = defineProps({
        menu: Object,
        items: Object,
        errors: Object
    })

    let updateMenu = () => {
        router.post(`/menus/${props.menu.id}`, {
            ...props.menu,
            '_method' : 'put'
        })
    };
</script>
<template>
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="h4">Edit Menu</h4>
                </div>
                <div class="card-body">
                    <form action="" @submit.prevent="updateMenu">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" v-model="menu.name">
                            <p class="text-danger">{{ errors.name }}</p>
                        </div>
                        <div class="form-group">
                            <label for="name_display">Name Display</label>
                            <input type="text" name="name_display" id="name_display" class="form-control" v-model="menu.name_display">
                            <p class="text-danger">{{ errors.name_display }}</p>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div>
                                <label class="radio-label">
                                    <input type="radio" value="1" name="status" v-model="menu.status">
                                    <span>Active</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" value="2" name="status" v-model="menu.status">
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
        <div class="col-lg-8 mx-auto mt-5">
            <div class="items mt-3">
                <h5 class="h5">Items</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>price</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in items" :key="item.id">
                            <td>{{ item.name }}</td>
                            <td>{{ item.price }}</td>
                            <td>
                                <Link :href="`/items/${item.item_id}/edit`" class="btn btn-info me-2"><i class="fa fa-pencil"></i></Link>
                                <Link :href="`/menu-items/${item.id}/`" method="delete" preserve-scroll class="btn btn-danger"><i class="fa fa-times"></i></Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>