<script>
import axios from 'axios';
import { store } from '../../store';

export default {
    name: 'DeleteStop',
    props: {
        stop: Object
    },
    data() {
        return {
            store,
        }
    },
    methods: {
        deleteStop(id) {
            axios.post(store.baseApiUrl + 'delete-stop/' + id).then(response => {
                if (response.data.success) {
                    // console.log(response.data.message);

                    document.getElementById(`close-delete-stop-modal-${id}`).click()

                    window.location.reload()
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })
        },
    }
}
</script>

<template>
    <button type="button" class="btn" data-bs-toggle="modal" :data-bs-target="`#delete-stop-${stop.id}`">
        <div v-html="store.trashIcon"></div>
    </button>

    <div class="modal fade" :id="`delete-stop-${stop.id}`" tabindex="-1" role="dialog"
        :aria-labelledby="`delete-stop-${stop.id}-title`" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="`delete-stop-${stop.id}-title`">
                        Delete stop: <span class="text-danger">{{ stop.title }}</span>
                    </h5>
                    <button type="button" class="btn-close" :id="`close-delete-stop-modal-${stop.id}`"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">You're gonna delete it forever! <br> You'll also
                    loose all its stops and stops!!</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-danger" @click="deleteStop(stop.id)">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>