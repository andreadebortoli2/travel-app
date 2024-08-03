<script>
import axios from 'axios';
import tt from "@tomtom-international/web-sdk-maps"
import { RouterLink } from 'vue-router';

export default {
    name: 'DayView',
    components: {
        Map
    },
    data() {
        return {
            day: {},
            updateDayTitle: '',
            updateDayDate: '',
            updateDayDescription: '',
            errors: {},
            loading: false,
            stops: [],
            map: null
        }
    },
    methods: {
        async getSingleDay() {
            await axios.get('http://127.0.0.1:8000/api/day' + this.$route.params.id).then(response => {
                this.day = response.data.day[0]
                this.stops = response.data.stops
                // console.log(this.day, this.stops);
            })
        },
        // day CRUD
        setUpdateValues(day) {
            this.updateDayTitle = ''
            this.updateDayDate = ''
            this.updateDayDescription = ''
            this.updateDayTitle = day.title
            this.updateDayDate = day.date
            this.updateDayDescription = day.description
        },
        editDay(id) {
            const data = {
                trip_id: this.day.trip_id,
                title: this.updateDayTitle,
                date: this.updateDayDate,
                description: this.updateDayDescription
            }
            // console.log(data);

            this.loading = true

            axios.post('http://127.0.0.1:8000/api/update-day/' + id, data).then(response => {
                if (response.data.success) {
                    this.updateDayTitle = ''
                    this.updateDayDate = ''
                    this.updateDayDescription = ''
                    this.errors = {}
                    // console.log(response.data.message);
                    this.getSingleDay()
                    document.getElementById(`close-update-day-offcanvas-${id}`).click()
                }
            }).catch(error => {
                console.log(error);
                if (error) {
                    this.errors = error.response.data.errors
                }
            })

            this.loading = false
        },
        deleteDay(id) {
            let tripId = this.day.trip_id
            axios.post('http://127.0.0.1:8000/api/delete-day/' + id).then(response => {
                if (response.data.success) {
                    this.day = {}
                    this.stops = []
                    this.errors = {}
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
        // map functions
        addMap() {
            const map = tt.map({
                key: "koaCbZL6M2ThGOlvwAqsz9z3lopU60iG",
                container: "map",
            })

            return map
        },
        addMarkers(map) {
            this.stops.forEach(stop => {
                // console.log(stop.position_longitude, stop.position_latitude);
                let position = [stop.position_longitude, stop.position_latitude]
                let marker = new tt.Marker().setLngLat(position).addTo(map)
                let popup = new tt.Popup().setText(stop.name)
                marker.setPopup(popup)
            })
        }
    },
    async mounted() {
        this.addMap()
        await this.getSingleDay()
        this.addMarkers(this.addMap())
    }
}
</script>

<template>
    <section>
        <div class="row">
            <div class="col">
                <!-- left col -->
                <div class="d-flex justify-content-between">
                    <h2 class="w-50">
                        {{ day.date }}
                    </h2>
                    <h2 v-if="day.title">
                        {{ day.title }}
                    </h2>
                </div>
                <div class="d-flex justify-content-end">
                    <!-- update day offcanvas trigger button -->
                    <button class="btn" type="button" data-bs-toggle="offcanvas" :data-bs-target="`#edit-day-${day.id}`"
                        :aria-controls="`edit-day-${day.id}`" @click="setUpdateValues(day)">
                        U
                    </button>
                    <!-- delete  day modal trigger button -->
                    <button type="button" class="btn" data-bs-toggle="modal" :data-bs-target="`#delete-day-${day.id}`">
                        Del
                    </button>
                    <!-- update day offcanvas body -->
                    <div class="offcanvas offcanvas-end w-50" tabindex="-1" :id="`edit-day-${day.id}`"
                        :aria-labelledby="`edit-day-${day.id}-offcanvas`">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" :id="`edit-day-${day.id}-offcanvas`">
                                Edit day: {{ day.title }}
                            </h5>
                            <button type="button" class="btn-close" :id="`close-update-day-offcanvas-${day.id}`"
                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body">
                            <form @submit.prevent="editDay(day.id)">

                                <div class="mm-3">
                                    <label for="edit-day-title" class="form-label">Title / destination
                                        :</label>
                                    <input type="text" class="form-control" name="edit-day-title" id="edit-day-title"
                                        v-model="updateDayTitle" />
                                </div>

                                <div class="my-3">
                                    <label for="edit-day-date" class="form-label">Date*
                                        :</label>
                                    <input type="text" onfocus="(this.type='date')" class="form-control"
                                        name="edit-day-date" id="edit-day-date" v-model="updateDayDate" required>
                                </div>

                                <div class="mb-3">
                                    <label for="update-day-description" class="form-label">Description :</label>
                                    <textarea class="form-control" name="update-day-description"
                                        id="update-day-description" rows="3" v-model="updateDayDescription"></textarea>
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
                    <!-- delete day modal body -->
                    <div class="modal fade" :id="`delete-day-${day.id}`" tabindex="-1" role="dialog"
                        :aria-labelledby="`delete-day-${day.id}-title`" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md"
                            role="document">
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
                                    <button type="button" class="btn btn-danger"
                                        @click="deleteDay(day.id)">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p>
                    {{ day.description }}
                </p>
                <RouterLink :to="{ name: 'add-stop', params: { id: day.id, date: day.date } }">Add a stop</RouterLink>
                <template v-if="stops">
                    <template v-for="stop in stops">
                        <h5>{{ stop.name }} <span v-if="stop.rating"> - {{ stop.rating }}/5</span></h5>
                        <p>{{ stop.notes }}</p>
                    </template>
                </template>
            </div>
            <div class="col">
                <!-- right col -->
                <div id='map'></div>
            </div>
        </div>
    </section>
</template>

<style scoped>
#map {
    width: 100%;
    height: 75vh;
    margin: 1rem auto 0;
}
</style>