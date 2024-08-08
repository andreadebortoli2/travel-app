<script>
import axios from 'axios';
import tt from "@tomtom-international/web-sdk-maps"
import { RouterLink } from 'vue-router';
import { store } from '../../store';
import UpdateDay from '../partials/UpdateDay.vue';
import DeleteDay from '../partials/DeleteDay.vue';
import DeleteStop from '../partials/DeleteStop.vue';

export default {
    name: 'DayView',
    components: {
        UpdateDay, DeleteDay, DeleteStop
    },
    data() {
        return {
            store,
            day: {},
            stops: [],
        }
    },
    methods: {
        async getSingleDay() {
            await axios.get(store.baseApiUrl + 'day' + this.$route.params.id).then(response => {
                this.day = response.data.day[0]
                this.stops = response.data.stops
                // console.log(this.day, this.stops);
            })
        },
        // map functions
        addMapSm() {
            const mapSm = tt.map({
                key: store.mapKey,
                container: 'map-sm',
            })

            return mapSm
        },
        addMapLg() {
            const mapLg = tt.map({
                key: store.mapKey,
                container: 'map-lg',
            })

            return mapLg
        },
        addMarkers(mapSm, mapLg) {
            if (this.stops.length > 0) {
                console.log(mapSm.mapLg);

                let longitudes = []
                let latitudes = []

                this.stops.forEach(stop => {
                    // console.log(stop.position_longitude, stop.position_latitude);
                    longitudes.push(stop.position_longitude)
                    latitudes.push(stop.position_latitude)
                    let position = [stop.position_longitude, stop.position_latitude]
                    let markerS = new tt.Marker().setLngLat(position).addTo(mapSm)
                    let markerL = new tt.Marker().setLngLat(position).addTo(mapLg)
                    let popup = new tt.Popup().setText(stop.name)
                    markerS.setPopup(popup)
                    markerL.setPopup(popup)
                })

                let ne = [Math.max(...longitudes), Math.max(...latitudes)]
                let sw = [Math.min(...longitudes), Math.min(...latitudes)]

                var llb = new tt.LngLatBounds(sw, ne)

                if (this.stops.length === 1) {
                    mapSm.setZoom(8)
                    mapSm.setCenter(ne)
                    mapLg.setZoom(8)
                    mapLg.setCenter(ne)
                } else {
                    mapSm.setZoom(0)
                    mapSm.fitBounds(llb, {
                        padding: { top: 40, bottom: 10, left: 25, right: 25 }
                    })
                    mapLg.setZoom(0)
                    mapLg.fitBounds(llb, {
                        padding: { top: 40, bottom: 10, left: 25, right: 25 }
                    })
                }
            }
        }
    },
    async mounted() {
        await this.getSingleDay()
        this.addMarkers(this.addMapSm(), this.addMapLg())
    }
}
</script>

<template>
    <section id="day">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2">
                <div class="col mb-3">
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
                        <!-- update day offcanvas -->
                        <UpdateDay :day="day" @update-day="getSingleDay()" />

                        <!-- delete day modal -->
                        <DeleteDay :day="day" />
                    </div>
                    <p>
                        {{ day.description }}
                    </p>
                    <RouterLink :to="{ name: 'add-stop', params: { id: day.id, date: day.date } }">
                        <button class="btn btn-outline-light text-primary-emphasis mb-3" type="button">
                            <h2 class="m-0">
                                <div v-html="store.addIcon"></div>
                            </h2>
                        </button>
                    </RouterLink>

                    <!-- map small col -->
                    <div class="col" id="map-sm-container">
                        <div id='map-sm'></div>
                    </div>

                    <div class="stops">
                        <template v-if="stops">
                            <div class="accordion" id="stops-accordion">
                                <template v-for="stop in stops">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" :id="`heading-stop-${stop.id}`">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" :data-bs-target="`#collapse-stop-${stop.id}`"
                                                aria-expanded="true" :aria-controls="`collapse-stop-${stop.id}`">
                                                <i class="fa-solid fa-location-dot me-3"></i>
                                                <strong>{{ stop.name }}</strong>
                                            </button>
                                        </h2>
                                        <div :id="`collapse-stop-${stop.id}`"
                                            class="accordion-collapse collapse bg-success-subtle"
                                            :aria-labelledby="`heading-stop-${stop.id}`"
                                            data-bs-parent="#stops-accordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col col-9">
                                                        <p>{{ stop.notes }}</p>
                                                        <template v-for="star in stop.rating">
                                                            <i class="fa-solid fa-star"></i>
                                                        </template>
                                                        <template v-for="emptyStar in 5 - stop.rating">
                                                            <i class="fa-regular fa-star"></i>
                                                        </template>
                                                        <div class="d-flex">
                                                            <button class="btn">
                                                                <RouterLink
                                                                    :to="{ name: 'update-stop', params: { id: stop.id, date: day.date } }">
                                                                    <button class="btn btn-outline-light" type="button">
                                                                        <div v-html="store.editIcon"></div>
                                                                    </button>
                                                                </RouterLink>
                                                            </button>
                                                            <!-- delete stop modal -->
                                                            <DeleteStop :stop="stop" />
                                                        </div>
                                                    </div>
                                                    <div class="col col-3 p-0">
                                                        <div class="image-frame">
                                                            <template v-if="stop.image">

                                                                <!-- Modal trigger button -->
                                                                <button type="button" class="btn image-modal-btn p-0"
                                                                    data-bs-toggle="modal"
                                                                    :data-bs-target="`#modal-stop-${stop.id}-image`">
                                                                    <img class="modal-btn-image"
                                                                        :src="store.imageBaseUrl + stop.image" alt="">
                                                                </button>

                                                                <!-- Modal Body -->
                                                                <div class="modal fade"
                                                                    :id="`modal-stop-${stop.id}-image`" tabindex="-1"
                                                                    role="dialog" aria-labelledby="modalTitleId"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg"
                                                                        role="document">
                                                                        <div class="modal-content bg-secondary-subtle">
                                                                            <div class="modal-body pt-0">
                                                                                <button type="button"
                                                                                    class="btn-close m-1"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Close"></button>
                                                                                <img :src="store.imageBaseUrl + stop.image"
                                                                                    alt="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <template v-else>
                                                                <img class="image-placeholder"
                                                                    src="/landscape-placeholder.svg" alt="">
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- right col -->
                <div class="col" id="map-lg-container">
                    <div id='map-lg'></div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.fa-star {
    color: goldenrod;
}

.image-frame {
    border: 3px solid white;
    border-radius: 1rem;
    aspect-ratio: 1;
    width: 100%;

    .image-modal-btn {
        width: 100%;
        border-radius: 1rem;

        .modal-btn-image {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 0.8rem;
        }
    }

    .modal {
        width: fit-content;
        height: fit-content;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        overflow: hidden;

        .modal-body {

            img {
                max-width: 80vw;
                max-height: 80vh;
                display: block;
                width: 100%;
                height: 100%;
                object-fit: contain;
            }
        }
    }

    .image-placeholder {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 0.8rem;
    }
}
</style>