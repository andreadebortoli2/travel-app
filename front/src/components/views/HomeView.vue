<script>
import axios from 'axios'
import { RouterLink } from 'vue-router';

export default {
    name: 'Homeview',
    data() {
        return {
            trips: [],
            singleTrip: {},
            singleTripDays: {}
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