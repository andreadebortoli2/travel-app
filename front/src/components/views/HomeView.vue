<script>
import axios from 'axios'
import { RouterLink } from 'vue-router';
import { store } from '../../store';
import NewTrip from '../partials/NewTrip.vue';
import UpdateTrip from '../partials/UpdateTrip.vue';
import DeleteTrip from '../partials/DeleteTrip.vue';
import NewDay from '../partials/NewDay.vue';

export default {
    name: 'Homeview',
    components: {
        NewTrip, UpdateTrip, DeleteTrip, NewDay,
    },
    data() {
        return {
            store,
            trips: [],
            singleTrip: {},
            singleTripDays: {},
            errors: {},
            loading: false,
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
                    <NewTrip @new-trip="getTrips()" />
                </div>

                <!-- left col table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-borderless table-success align-middle">
                        <tbody>
                            <tr v-for="trip in trips" @click="getSingleTrip(trip.id)">
                                <td class="w-25">{{ trip.start_date }}</td>
                                <td class="text-primary"><strong>{{ trip.title }}</strong></td>
                                <td>
                                    <!-- update trip offcanvas -->
                                    <UpdateTrip :trip="trip" @update-trip="getTrips()" />

                                    <!-- delete trip modal -->
                                    <DeleteTrip :trip="trip" @delete-trip="getTrips()" />
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
                        <NewDay :singleTrip="singleTrip" />
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