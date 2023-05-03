<script setup>
    import { reactive, watch, computed } from 'vue';
    import { router } from '@inertiajs/core';

    let props = defineProps({
        errors: Object,
        userSelected: Object,
        editModal: String
    });

    let user = computed(() => JSON.parse(JSON.stringify(props.userSelected)));
    let password = '';

    let editUser = () => {
        router.put(`/users/${user.value.id}`, {
            name: user.value.name,
            email: user.value.email,
            password: password,
        }, 
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,

            onStart: () => {
                
            },
            onError: () => {

            },
            onSuccess: () => {
                $emit('hideModal')
            }
        })
    }
</script>
<template>
    <!-- Modal -->
    <div class="modal fade" :class="{'d-block show' : editModal == 'show'}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit User</h1>
            </div>
            <div class="modal-body">
                <form action="" id="userEditForm" @submit.prevent="editUser">
                    <label for="userName">Name</label>
                    <input type="text" class="form-control" id="userName" name="userName" v-model="user.name">

                    <p class="text-danger" v-if="errors.name">{{ errors.name }}</p>

                    <label for="userEmail">Email</label>
                    <input type="email" class="form-control" id="userEmail" name="userEmail" v-model="user.email">

                    <p class="text-danger" v-if="errors.email">{{ errors.email }}</p>

                    <label for="userPassword">Password</label>
                    <input type="password" class="form-control" id="userPassword" name="userPassword" v-model="password">

                    <p class="text-danger" v-if="errors.password">{{ errors.password }}</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="$emit('hideModal')">Close</button>
                <button type="submit" class="btn btn-primary" form="userEditForm" :disabled="user.status == 'submitting'">Save</button>
            </div>
            </div>
        </div>
    </div>
</template>