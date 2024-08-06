<script>
import axios from 'axios';
import { store } from '../../store';

export default {
    name: 'NewTrip',
    data() {
        return {
            store,
            newTripTitle: '',
            newTripStartDate: '',
            errors: {},
            loading: false,
        }
    },

    methods: {
        createNewTrip() {
            const data = {
                title: this.newTripTitle,
                start_date: this.newTripStartDate
            }
            // console.log(data);

            this.loading = true

            axios.post(store.baseApiUrl + 'new-trip', data).then(response => {
                if (response.data.success) {
                    this.newTripTitle = ''
                    this.newTripStartDate = ''
                    this.errors = {}
                    // console.log(response.data.message);
                    this.$emit('newTrip')
                    document.getElementById('close-new-trip-offcanvas').click()
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
    emits: ['newTrip'],
}
</script>

<template>
    <button class="btn btn-outline-light text-primary-emphasis" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#new-trip" aria-controls="new-trip">
        <h2 class="m-0">
            <div v-html="store.addIcon"></div>
        </h2>
    </button>

    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="new-trip" aria-labelledby="new-trip-offcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="new-trip-offcanvas">
                New trip
            </h5>
            <button type="button" class="btn-close" id="close-new-trip-offcanvas" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form @submit.prevent="createNewTrip()">

                <div class="mm-3">
                    <label for="new-trip-title" class="form-label">Title / destination* :</label>
                    <input type="text" class="form-control" name="new-trip-title" id="new-trip-title" placeholder="Rome"
                        v-model="newTripTitle" required />
                </div>

                <div class="my-3">
                    <label for="new-trip-start-date" class="form-label">Start date* :</label>
                    <input type="date" class="form-control" name="new-trip-start-date" id="new-trip-start-date"
                        v-model="newTripStartDate" required>
                </div>

                <div class="my-3">
                    <button class="form-control" type="submit" :disabled="loading">
                        {{ loading ? 'Creating...' : 'Add a new trip' }}
                    </button>
                </div>
            </form>

            <div class="errors text-danger m-3" v-if="Object.keys(this.errors).length !== 0">
                <div v-for="error in errors">{{ error[0] }}</div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.btn {}
</style>