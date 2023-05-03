<script setup>
    import { router } from '@inertiajs/core';
    import { ref, watch } from 'vue';

    let props = defineProps({
        menus: Object,
        pageData:Object
    });

    let changeStatus = (id, e) => {

        router.post('/menus/'+ id +'/change-status', {
            status : e.target.value
        }, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
            
        });
    }

    let searchInput = ref(props.pageData.menuSearch);
    let searchTiemOut = null;

    watch(searchInput, () => {

        clearTimeout(searchTiemOut);

        searchTiemOut = setTimeout(() => {
            router.get('/menus', {
                search : searchInput.value
            }, {
                replace: true,
                preserveState: true,
            })
        }, 500)
    });
</script>
<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-9">
                    <h4 class="h4">Menu List</h4>
                </div>
                <div class="col-lg-3">
                    <input type="search" name="search" id="search" class="form-control" placeholder="Search menu..." v-model="searchInput">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="text-end">
                <Link href="/menus/create" class="btn btn-primary mb-3">+ Add Menu</Link>
            </div>
            <table class="table table-borderd" v-if="menus.data.length">
                <thead>
                    <tr>
                        <th colspan="2">Menus</th>
                        <th class="text-end">Total: {{menus.data.length}}</th>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <th>Name</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(menu, index) in menus.data" :key="menu.id" @click="router.get(`/menus/${menu.id}/edit`)">
                        <td width="15%" @click.stop >
                            <select :value="menu.status" class="form-control" @change="changeStatus(menu.id, $event)">
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </td>
                        <td>{{ menu.name }} <span v-if="menu.name_display"> ({{ menu.name_display }})</span></td>
                        <td width="20%">
                            {{ menu.updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="alert alert-warning fade show text-center mt-4" role="alert" v-else>
              <strong>Sorry!</strong> No Menus found <span v-if="pageData.menuSearch">for ({{ pageData.menuSearch }})</span>
            </div>
            <div class=" d-flex justify-content-center py-3">
                <ul class="pagination">
                    <li v-for="(link, index) in menus.links" v-if="menus.links.length > 3" :key="index" class="page-item" :class="{'disabled' : !link.url, 'active': link.active}">
                        <Link :href="link.url ?? null" v-html="link.label" class="page-link" preserve-scroll></Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<style scoped>
    tbody tr {
        cursor: pointer;
    }

    tbody tr:hover {
        font-weight: 500;
    }
</style>