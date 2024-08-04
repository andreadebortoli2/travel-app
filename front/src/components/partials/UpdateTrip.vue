<script>
import axios from 'axios';
import { store } from '../../store';

export default {
    name: 'UpdateTrip',
    props: {
        trip: Object
    },
    data() {
        return {
            store,
            updateTripTitle: '',
            updateTripStartDate: '',
            errors: {},
            loading: false,
        }
    },
    methods: {
        setUpdateValues(trip) {
            this.updateTripTitle = ''
            this.updateTripStartDate = ''
            this.updateTripTitle = trip.title
            this.updateTripStartDate = trip.start_date
        },
        editTrip(id) {
            const data = {
                title: this.updateTripTitle,
                start_date: this.updateTripStartDate
            }
            // console.log(data);

            this.loading = true

            axios.post(store.baseApiUrl + 'update-trip/' + id, data).then(response => {
                if (response.data.success) {
                    this.updateTripTitle = ''
                    this.updateTripStartDate = ''
                    this.errors = {}
                    // console.log(response.data.message);
                    this.$emit('updateTrip')
                    document.getElementById(`close-update-trip-offcanvas-${id}`).click()
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })

            this.loading = false
        },
    },
    emits: ['updateTrip'],
}
</script>

<template>
    <button class="btn" type="button" data-bs-toggle="offcanvas" :data-bs-target="`#edit-trip-${trip.id}`"
        :aria-controls="`edit-trip-${trip.id}`" @click="setUpdateValues(trip)">
        U
    </button>

    <div class="offcanvas offcanvas-end w-50" tabindex="-1" :id="`edit-trip-${trip.id}`"
        :aria-labelledby="`edit-trip-${trip.id}-offcanvas`">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" :id="`edit-trip-${trip.id}-offcanvas`">
                Edit trip: {{ trip.title }}
            </h5>
            <button type="button" class="btn-close" :id="`close-update-trip-offcanvas-${trip.id}`"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form @submit.prevent="editTrip(trip.id)">

                <div class="mm-3">
                    <label for="edit-trip-title" class="form-label">Title / destination*
                        :</label>
                    <input type="text" class="form-control" name="edit-trip-title" id="edit-trip-title"
                        v-model="updateTripTitle" required />
                </div>

                <div class="my-3">
                    <label for="edit-trip-start-date" class="form-label">Start date*
                        :</label>
                    <input type="text" onfocus="(this.type='date')" class="form-control" name="edit-trip-start-date"
                        id="edit-trip-start-date" v-model="updateTripStartDate" required>
                </div>

                <div class="my-3">
                    <button class="form-control" type="submit" :disabled="loading">
                        {{ loading ? 'Updating...' : 'Update the trip' }}
                    </button>
                </div>
            </form>

            <div class="errors text-danger m-3" v-if="Object.keys(this.errors).length !== 0">
                <div v-for="error in errors">{{ error[0] }}</div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>