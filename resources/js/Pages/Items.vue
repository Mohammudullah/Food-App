<script setup>
    import { router } from '@inertiajs/core';
    import { ref, watch } from 'vue';
    let props = defineProps({
        items: Object,
        pageData: Object
    });

    let searchInput = ref(props.pageData.itemsSearch);
    let searchTiemOut = null;

    watch(searchInput, () => {

        clearTimeout(searchTiemOut);

        searchTiemOut = setTimeout(() => {
            router.get('/items', {
                search : searchInput.value
            }, {
                replace: true,
                preserveState: true,
            })
        }, 500)
    });

    let changeStatus = (id, e) => {

        router.post('/items/'+ id +'/change-status', {
            status : e.target.value
        }, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
            
        });
    }

</script>
<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-9">
                    <h4 class="h4">Item List</h4>
                </div>
                <div class="col-lg-3">
                    <input v-model="searchInput" type="search" class="form-control" placeholder="Search Item...">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="action-button text-end">
                <Link href="/items/create" class="btn btn-primary" as="button">+ Add Item</Link>
            </div>
            <table class="table table-borderd item-table mt-3" v-if="items.data.length > 0">
                <thead>
                    <tr>
                        <th colspan="2">Items</th> 
                        <th colspan="2" class="text-end">Total : {{ items.total }}</th> 
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in items.data" :key="item.id" @click="router.get(`/items/${item.id}/edit`)">
                        <td width="15%" @click.stop >
                            <select :value="item.status" class="form-control" @change="changeStatus(item.id, $event)">
                                <option value="1">Available</option>
                                <option value="2">Sold out</option>
                                <option value="3">Hidden</option>
                            </select>
                        </td>
                        <td>{{ item.name+(item.name_display ? ` (${item.name_display})` : '') }}</td>
                        <td width="15%">{{ item.price+' USD' }}</td>
                        <td class="text-end"><img v-if="item.photo" :src="`uploads/${item.photo}`" class="item-image"/></td>
                    </tr>
                </tbody>
            </table>
            <div class="alert alert-warning fade show text-center mt-4" role="alert" v-else>
              <strong>Sorry!</strong> No Items found <span v-if="pageData.itemsSearch">for ({{ pageData.itemsSearch }})</span>
            </div>
            <div class=" d-flex justify-content-center py-3">
                <ul class="pagination">
                    <li v-for="(link, index) in items.links" v-if="items.links.length > 3" :key="index" class="page-item" :class="{'disabled' : !link.url, 'active': link.active}">
                        <Link :href="link.url ?? null" v-html="link.label" class="page-link" preserve-scroll></Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<style>
    .item-image {
        width: 70px;
        height: 50px;
        object-fit: cover;
    }

    .item-table tbody tr {
        vertical-align: middle;
        cursor: pointer;
    }

    .item-table tbody tr:hover td {
        font-weight: 500;
    }

</style>