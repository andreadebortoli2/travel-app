<script>
import axios from 'axios';
import { store } from '../../store';

export default {
    name: 'DeleteDay',
    props: {
        day: Object
    },
    data() {
        return {
            store,
        }
    },
    methods: {
        deleteDay(id) {
            // let tripId = this.day.trip_id
            axios.post(store.baseApiUrl + 'delete-day/' + id).then(response => {
                if (response.data.success) {
                    // console.log(response.data.message);
                    let modalBackground = document.querySelector('.modal-backdrop')
                    if (modalBackground) {
                        modalBackground.remove()
                    }

                    this.$router.push({ name: 'home' })
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
    <button type="button" class="btn btn-outline-danger ms-3" data-bs-toggle="modal"
        :data-bs-target="`#delete-day-${day.id}`">
        <div v-html="store.trashIcon"></div>
    </button>

    <div class="modal fade" :id="`delete-day-${day.id}`" tabindex="-1" role="dialog"
        :aria-labelledby="`delete-day-${day.id}-title`" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="`delete-day-${day.id}-title`">
                        Delete day: <span class="text-danger">{{ day.title }}</span>
                    </h5>
                    <button type="button" class="btn-close" :id="`close-delete-day-modal-${day.id}`"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">You're gonna delete it forever! <br> You'll also
                    loose all its days and stops!!</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-danger" @click="deleteDay(day.id)">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>