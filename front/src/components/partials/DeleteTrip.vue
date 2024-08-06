<script>
import axios from 'axios';
import { store } from '../../store';

export default {
    name: 'DeleteTrip',
    props: {
        trip: Object
    },
    data() {
        return {
            store,
        }
    },
    methods: {
        deleteTrip(id) {
            axios.post(store.baseApiUrl + 'delete-trip/' + id).then(response => {
                if (response.data.success) {
                    this.errors = {}
                    // console.log(response.data.message);
                    this.singleTrip = {}
                    this.$emit('deleteTrip')
                    document.getElementById(`close-delete-trip-modal-${id}`).click()
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })
        },
    },
    emits: ['deleteTrip']
}
</script>

<template>
    <button type="button" class="btn btn-outline-danger ms-3" data-bs-toggle="modal"
        :data-bs-target="`#delete-trip-${trip.id}`">
        <div v-html="store.trashIcon"></div>
    </button>

    <div class="modal fade" :id="`delete-trip-${trip.id}`" tabindex="-1" role="dialog"
        :aria-labelledby="`delete-trip-${trip.id}-title`" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="`delete-trip-${trip.id}-title`">
                        Delete trip: <span class="text-danger">{{ trip.title }}</span>
                    </h5>
                    <button type="button" class="btn-close" :id="`close-delete-trip-modal-${trip.id}`"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">You're gonna delete it forever! <br> You'll also
                    loose all its days and stops!!</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-danger" @click="deleteTrip(trip.id)">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>