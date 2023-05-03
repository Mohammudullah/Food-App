<script setup>
import { reactive, watch } from 'vue';
import { router } from '@inertiajs/vue3';

    defineProps({
        errors: Object
    })

    let newUser = reactive({
        name : '',
        email: '',
        password: '',
        status: 'new'
    });

    //modal open
    let modal = reactive({
        status : 'hide'
    });

    let addUser = () => {
        router.post('/users', {
            name : newUser.name,
            email: newUser.email,
            password: newUser.password
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,

            onStart: () => {
                newUser.status = 'submitting'
            },
            onError: () => {
                newUser.status = 'faild'
            },
            onSuccess: () => {
                newUser.status = 'submitted'
                modal.status = 'hide'
            }
        })
    }

    watch(newUser, () => {
        if(newUser.status == 'submitted') {
            newUser.status = 'new'
            newUser.name = newUser.email = newUser.password = ''
        }
    });
</script>

<template>
    <div class="col-12 text-end mb-3">
        <button class="btn btn-primary" @click="modal.status='show'">+ Add User</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" :class="{'d-block show' : modal.status == 'show'}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add New User</h1>
            </div>
            <div class="modal-body">
                <form action="" id="userAddForm" @submit.prevent="addUser">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" v-model="newUser.name">

                    <p class="text-danger" v-if="errors.name">{{ errors.name }}</p>

                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" v-model="newUser.email">

                    <p class="text-danger" v-if="errors.email">{{ errors.email }}</p>

                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" v-model="newUser.password">

                    <p class="text-danger" v-if="errors.password">{{ errors.password }}</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="modal.status='hide'">Close</button>
                <button type="submit" class="btn btn-primary" form="userAddForm" :disabled="newUser.status == 'submitting'">Save</button>
            </div>
            </div>
        </div>
    </div>
</template>

<style>
    .modal.show {
        background: rgba(0, 0, 0, .5);
    }
</style>