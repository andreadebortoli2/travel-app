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
            updateTripTitle: '',
            updateTripStartDate: ''
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

            axios.post('http://127.0.0.1:8000/api/update-trip/' + id, data).then(response => {
                if (response.data.success) {
                    this.updateTripTitle = ''
                    this.updateTripStartDate = ''
                    this.errors = {}
                    // console.log(response.data.message);
                    this.getTrips()
                    document.getElementById('close-update-trip-offcanvas').click()
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
                            <button type="button" class="btn-close" id="close-new-trip-offcanvas"
                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body">
                            <form @submit.prevent="createNewTrip()">

                                <div class="mm-3">
                                    <label for="name" class="form-label">Title / destination :</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Rome"
                                        v-model="newTripTitle" required />
                                </div>

                                <div class="my-3">
                                    <label for="start-date" class="form-label">Start date :</label>
                                    <input type="date" class="form-control" name="start-date" id="start-date"
                                        v-model="newTripStartDate" required>
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

                <!-- left col table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-borderless table-success align-middle">
                        <tbody>
                            <tr v-for="trip in trips" @click="getSingleTrip(trip.id)">
                                <td class="w-25">{{ trip.start_date }}</td>
                                <td class="text-primary"><strong>{{ trip.title }}</strong></td>
                                <td>
                                    <button class="btn" type="button" data-bs-toggle="offcanvas"
                                        :data-bs-target="`#edit-trip-${trip.id}`"
                                        :aria-controls="`edit-trip-${trip.id}`" @click="setUpdateValues(trip)">
                                        U
                                    </button>
                                    <!-- update trip offcanvas -->
                                    <div class="offcanvas offcanvas-end" tabindex="-1" :id="`edit-trip-${trip.id}`"
                                        :aria-labelledby="`edit-trip-${trip.id}-offcanvas`">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" :id="`edit-trip-${trip.id}-offcanvas`">
                                                Edit trip: {{ trip.title }}
                                            </h5>
                                            <button type="button" class="btn-close" id="close-update-trip-offcanvas"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>

                                        <div class="offcanvas-body">
                                            <form @submit.prevent="editTrip(trip.id)">

                                                <div class="mm-3">
                                                    <label for="name" class="form-label">Title / destination :</label>
                                                    <input type="text" class="form-control" name="name" id="name"
                                                        v-model="updateTripTitle" required />
                                                </div>

                                                <div class="my-3">
                                                    <label for="start-date" class="form-label">Start date :</label>
                                                    <input type="text" onfocus="(this.type='date')" class="form-control"
                                                        name="start-date" id="start-date" v-model="updateTripStartDate"
                                                        required>
                                                </div>

                                                <div class="my-3">
                                                    <button class="form-control" type="submit" :disabled="loading">
                                                        {{ loading ? 'Updating...' : 'Update the trip' }}
                                                    </button>
                                                </div>
                                            </form>

                                            <div class="errors text-danger m-3"
                                                v-if="Object.keys(this.errors).length !== 0">
                                                <div v-for="error in errors">{{ error[0] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- delete trip modal -->
            </div>
            <!-- right col -->
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