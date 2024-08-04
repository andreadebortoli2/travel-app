<script>
import axios from 'axios'
import { RouterLink } from 'vue-router';
import { store } from '../../store';

export default {
    name: 'Homeview',
    data() {
        return {
            store,
            trips: [],
            singleTrip: {},
            singleTripDays: {},
            newTripTitle: '',
            newTripStartDate: '',
            errors: {},
            loading: false,
            updateTripTitle: '',
            updateTripStartDate: '',
            newDayTitle: '',
            newDayDate: '',
            newDayDescription: '',
        }
    },
    methods: {
        getTrips() {
            axios.get(store.baseApiUrl + 'trips').then(response => {
                this.trips = response.data.trips
                // console.log(this.trips);
            })
        },
        getSingleTrip(id) {
            axios.get(store.baseApiUrl + 'trips/' + id).then(response => {
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

            axios.post(store.baseApiUrl + 'new-trip', data).then(response => {
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

            axios.post(store.baseApiUrl + 'update-trip/' + id, data).then(response => {
                if (response.data.success) {
                    this.updateTripTitle = ''
                    this.updateTripStartDate = ''
                    this.errors = {}
                    // console.log(response.data.message);
                    this.getTrips()
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
        deleteTrip(id) {
            axios.post(store.baseApiUrl + 'delete-trip/' + id).then(response => {
                if (response.data.success) {
                    this.errors = {}
                    // console.log(response.data.message);
                    this.singleTrip = {}
                    this.getTrips()
                    document.getElementById(`close-delete-trip-modal-${id}`).click()
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })
        },
        createNewDay() {
            const data = {
                trip_id: this.singleTrip.id,
                title: this.newDayTitle,
                date: this.newDayDate,
                description: this.newDayDescription
            }
            // console.log(data);

            this.loading = true

            axios.post(store.baseApiUrl + 'new-day', data).then(response => {
                if (response.data.success) {
                    this.newDayTitle = ''
                    this.newDayDate = ''
                    this.newDayDescription = ''
                    this.errors = {}
                    // console.log(response.data.message);

                    let dayId = response.data.route.day_id
                    let dayDate = response.data.route.day_date
                    this.$router.push({ name: 'day', params: { id: dayId, date: dayDate } })
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
            <!-- left col -->
            <div class="col m-1">
                <div class="d-flex justify-content-between">
                    <h2>Your trips:</h2>
                    <!-- new trip offcanvas -->
                    <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#new-trip"
                        aria-controls="new-trip">
                        <h2>+</h2>
                    </button>

                    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="new-trip"
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
                                    <label for="new-trip-title" class="form-label">Title / destination* :</label>
                                    <input type="text" class="form-control" name="new-trip-title" id="new-trip-title"
                                        placeholder="Rome" v-model="newTripTitle" required />
                                </div>

                                <div class="my-3">
                                    <label for="new-trip-start-date" class="form-label">Start date* :</label>
                                    <input type="date" class="form-control" name="new-trip-start-date"
                                        id="new-trip-start-date" v-model="newTripStartDate" required>
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
                </div>

                <!-- left col table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-borderless table-success align-middle">
                        <tbody>
                            <tr v-for="trip in trips" @click="getSingleTrip(trip.id)">
                                <td class="w-25">{{ trip.start_date }}</td>
                                <td class="text-primary"><strong>{{ trip.title }}</strong></td>
                                <td>
                                    <!-- update trip offcanvas trigger button -->
                                    <button class="btn" type="button" data-bs-toggle="offcanvas"
                                        :data-bs-target="`#edit-trip-${trip.id}`"
                                        :aria-controls="`edit-trip-${trip.id}`" @click="setUpdateValues(trip)">
                                        U
                                    </button>
                                    <!-- delete  trip modal trigger button -->
                                    <button type="button" class="btn" data-bs-toggle="modal"
                                        :data-bs-target="`#delete-trip-${trip.id}`">
                                        Del
                                    </button>
                                    <!-- update trip offcanvas body -->
                                    <div class="offcanvas offcanvas-end w-50" tabindex="-1" :id="`edit-trip-${trip.id}`"
                                        :aria-labelledby="`edit-trip-${trip.id}-offcanvas`">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" :id="`edit-trip-${trip.id}-offcanvas`">
                                                Edit trip: {{ trip.title }}
                                            </h5>
                                            <button type="button" class="btn-close"
                                                :id="`close-update-trip-offcanvas-${trip.id}`"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>

                                        <div class="offcanvas-body">
                                            <form @submit.prevent="editTrip(trip.id)">

                                                <div class="mm-3">
                                                    <label for="edit-trip-title" class="form-label">Title / destination*
                                                        :</label>
                                                    <input type="text" class="form-control" name="edit-trip-title"
                                                        id="edit-trip-title" v-model="updateTripTitle" required />
                                                </div>

                                                <div class="my-3">
                                                    <label for="edit-trip-start-date" class="form-label">Start date*
                                                        :</label>
                                                    <input type="text" onfocus="(this.type='date')" class="form-control"
                                                        name="edit-trip-start-date" id="edit-trip-start-date"
                                                        v-model="updateTripStartDate" required>
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
                                    <!-- delete trip modal body -->
                                    <div class="modal fade" :id="`delete-trip-${trip.id}`" tabindex="-1" role="dialog"
                                        :aria-labelledby="`delete-trip-${trip.id}-title`" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md"
                                            role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" :id="`delete-trip-${trip.id}-title`">
                                                        Delete trip: <span class="text-danger">{{ trip.title }}</span>
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        :id="`close-delete-trip-modal-${trip.id}`"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">You're gonna delete it forever! <br> You'll also
                                                    loose all its days and stops!!</div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-success"
                                                        data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="button" class="btn btn-danger"
                                                        @click="deleteTrip(trip.id)">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- right col -->
            <div class="col m-1">
                <div v-if="singleTrip">
                    <div class="d-flex justify-content-between">
                        <h3>{{ singleTrip.title }}</h3>
                        <!-- new day offcanvas -->
                        <div v-if="singleTrip.id">
                            <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#new-day"
                                aria-controls="new-day">
                                <h2>+</h2>
                            </button>

                            <div class="offcanvas offcanvas-start w-50" tabindex="-1" id="new-day"
                                aria-labelledby="new-day-offcanvas">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="new-day-offcanvas">
                                        New day
                                    </h5>
                                    <button type="button" class="btn-close" id="close-new-day-offcanvas"
                                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>


                                <div class="errors text-danger m-3" v-if="Object.keys(this.errors).length !== 0">
                                    <div v-for="error in errors">{{ error[0] }}</div>
                                </div>

                                <div class="offcanvas-body">
                                    <form @submit.prevent="createNewDay()">

                                        <div class="mm-3">
                                            <label for="new-day-title" class="form-label">Title / destination :</label>
                                            <input type="text" class="form-control" name="new-day-title"
                                                id="new-day-title" placeholder="Rome" v-model="newDayTitle" />
                                        </div>

                                        <div class="my-3">
                                            <label for="new-day-date" class="form-label">Date* :</label>
                                            <input type="date" class="form-control" name="new-day-date"
                                                id="new-day-date" v-model="newDayDate" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new-day-description" class="form-label">Description :</label>
                                            <textarea class="form-control" name="new-day-description"
                                                id="new-day-description" rows="3"
                                                v-model="newDayDescription"></textarea>
                                        </div>


                                        <div class="my-3">
                                            <button class="form-control" type="submit" :disabled="loading">
                                                {{ loading ? 'Creating...' : 'Add a new day' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-borderless table-info align-middle">
                            <tbody>
                                <template v-for="day in singleTripDays">
                                    <tr>
                                        <RouterLink :to="{ name: 'day', params: { id: day.id, date: day.date } }">
                                            <td class="w-25 p-2">{{ day.date }}</td>
                                            <td class="text-success" v-if="day.title"><strong>{{ day.title }}</strong>
                                            </td>
                                        </RouterLink>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped></style>