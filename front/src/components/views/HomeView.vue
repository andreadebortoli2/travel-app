<script>
import axios from 'axios'
import { RouterLink } from 'vue-router';

export default {
    name: 'Homeview',
    data() {
        return {
            trips: [],
            singleTrip: {},
            singleTripDays: {},
            newTripTitle: '',
            newTripStartDate: '',
            errors: {},
            loading: false,
        }
    },
    methods: {
        getTrips() {
            axios.get('http://127.0.0.1:8000/api/trips').then(response => {
                this.trips = response.data.trips
                // console.log(this.trips);
            })
        },
        getSingleTrip(id) {
            axios.get('http://127.0.0.1:8000/api/trips/' + id).then(response => {
                this.singleTrip = {}
                this.singleTrip = response.data.trip[0]
                this.singleTripDays = response.data.days
                // console.log(this.singleTrip, this.singleTripDays);
            })
        },
        createNewTrip() {
            const data = {
                title: this.newTripTitle,
                start_date: this.newTripStartDate
            }
            // console.log(data);

            this.loading = true

            axios.post('http://127.0.0.1:8000/api/new-trip', data).then(response => {
                if (response.data.success) {
                    this.newTripTitle = ''
                    this.newTripStartDate = ''
                    this.errors = {}
                    // console.log(response.data.message);
                    this.getTrips()
                    document.getElementById('close-button').click()
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })

            this.loading = false

        }
    },
    mounted() {
        this.getTrips()
    }
}
</script>

<template>
    <section>
        <div class="row">
            <div class="col m-1">
                <div class="d-flex justify-content-between">
                    <h2>Your trips:</h2>
                    <!-- new trip offcanvas -->
                    <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#new-trip"
                        aria-controls="new-trip">
                        <h2>+</h2>
                    </button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="new-trip"
                        aria-labelledby="new-trip-offcanvas">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="new-trip-offcanvas">
                                New trip
                            </h5>
                            <button type="button" class="btn-close" id="close-button" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body">
                            <form @submit.prevent="createNewTrip()">

                                <div class="mm-3">
                                    <label for="name" class="form-label">Title / destination :</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Rome"
                                        v-model="newTripTitle" />
                                </div>

                                <div class="my-3">
                                    <label for="start-date" class="form-label">Start date :</label>
                                    <input type="date" class="form-control" name="start-date" id="start-date"
                                        v-model="newTripStartDate">
                                </div>

                                <div class="my-3">
                                    <button class="form-control" type="submit" :disabled="loading">
                                        {{ loading ? 'Creating...' : 'Create a new trip' }}
                                    </button>
                                </div>
                            </form>

                            <div class="errors text-danger m-3" v-if="Object.keys(this.errors).length !== 0">
                                <div v-for="error in errors">{{ error[0] }}</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-borderless table-success align-middle">
                        <tbody>
                            <tr v-for="trip in trips" @click="getSingleTrip(trip.id)">
                                <td class="w-25">{{ trip.start_date }}</td>
                                <td class="text-primary"><strong>{{ trip.title }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col m-1">
                <h3>{{ singleTrip.title }}</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-borderless table-info align-middle">
                        <tbody>
                            <template v-for="day in singleTripDays">
                                <tr>
                                    <RouterLink :to="{ name: 'day', params: { id: day.id, slug: day.slug } }">
                                        <td class="w-25 p-2">{{ day.date }}</td>
                                        <td class="text-success"><strong>{{ day.title }}</strong></td>
                                    </RouterLink>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped></style>