<script setup>
import { ref, watch, reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3'
import AddNewUser from './components/AddNewUser.vue'
import EditUser from './components/EditUser.vue';

    let props = defineProps({
        users: Object,
        pageData: Object,
        errors: Object
    })

    let editModal = ref('hide');
    let userForEdit = reactive({});

    let searchInput = ref(props.pageData.userSearch);
    let searchTiemOut = null;

    watch(searchInput, () => {

        clearTimeout(searchTiemOut);

        searchTiemOut = setTimeout(() => {
            router.get('/users', {
                search : searchInput.value
            }, {
                replace: true,
                preserveState: true
            })
        }, 500)
    });

    //delete user
    let deleteUser = (id) => {
        if (window.confirm("Do you Want to remove this user?")) {
            router.delete(`users/${id}`, {
                preserveState: true,
                preserveScroll: true,
                replace: true,

                onError: () => {
                    alert('Unsuccessful to remove this user');
                },
                onSuccess: () => {
                    alert('User removed successfully');
                }
            })
        }
    }
</script>
<template>
        {{ $page.component }}
        <h1>Hello {{ $page.props.user.username }}</h1>
        <h1>This is Users page</h1>

        <div class="row">
            <div class="col-10 mx-auto mt-5">
                <AddNewUser :errors="errors"></AddNewUser>
                <div class="row">
                    <div class="col-md-5 ms-auto mb-3">
                        <input type="search" name="search" id="search" class="form-control" placeholder="Search Here..." v-model="searchInput">
                    </div>
                    <div class="col-12">
                        <table class="table" v-if="users.data.length >= 1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in users.data" :key="user.id">
                                    <td>{{ index + users.from }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.created }}</td>
                                    <td class="text-end">
                                        <!-- <Link as="button" :href="`/users/${user.id}/edit/`" class="btn btn-warning me-2"  preserve-scroll preserve-state>Edit</Link> -->
                                        <button class="btn btn-warning me-2" @click="editModal = 'show'; userForEdit = user">Edit</button>
                                        <button class="btn btn-danger" @click="deleteUser(user.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else class="alert alert-info text-center mt-5" role="alert">
                            No user found {{ pageData.userSearch ? `for '${pageData.userSearch}'` : '' }}
                        </div>

                        <div class=" d-flex justify-content-center py-3">
                            <ul class="pagination">
                                <li v-for="(link, index) in users.links" v-if="users.links.length > 3" :key="index" class="page-item" :class="{'disabled' : !link.url, 'active': link.active}">
                                    <Link :href="link.url ?? null" v-html="link.label" class="page-link" preserve-scroll></Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <EditUser :edit-modal="editModal" :user-selected="userForEdit" :errors="errors" @hideModal="editModal = 'hide'"></EditUser>
</template>